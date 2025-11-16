<?php

namespace App\Livewire\Dashboard;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Collection;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\LoanApproval;
use App\Models\LoanCollectionSchedule;
use App\Models\Payment;
use App\Models\PettyCashRequest;
use App\Models\Transaction;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Carbon;

class MainDashboard extends Component
{
    public $monthlyTotals;
    public $branches;
    public $selectedBranch = 'all';
    public $monthlyLoanDistribution;
    public $monthlyRevenue;
    public $monthlyExpense;
    public $monthlyProfit;
    public $weeklyUserCollections;
    public $recentActivities = [];

    // Dashboard metrics
    public $todayCollection;
    public $todayPrincipalCollected;
    public $todayInterestCollected;
    public $todayPendingCollection;
    public $totalCustomers;
    public $totalActiveLoans;
    public $cashInHand;
    public $totalUsers;

    protected $listeners = ['branchUpdated'];

    // Constants for activity tracking
    const TRACKED_MODELS = [
        Customer::class,
        Collection::class,
        LoanApproval::class,
        Payment::class,
        PettyCashRequest::class,
        Transaction::class
    ];

    const MODEL_ICONS = [
        'Customer' => 'fa-user',
        'Collection' => 'fa-money-bill',
        'LoanApproval' => 'fa-check-circle',
        'Payment' => 'fa-credit-card',
        'PettyCashRequest' => 'fa-wallet',
        'Transaction' => 'fa-exchange-alt'
    ];

    const MODEL_COLORS = [
        'Customer' => 'primary',
        'Collection' => 'success',
        'LoanApproval' => 'info',
        'Payment' => 'warning',
        'PettyCashRequest' => 'danger',
        'Transaction' => 'dark'
    ];

    public function mount()
    {
        $user = Auth::user();

        // Check permissions and set branches accordingly
        if ($user->hasPermissionTo('view all branches')) {
            $this->branches = Branch::all();
        } else {
            $this->branches = Branch::where('id', $user->branch_id)->get();
            $this->selectedBranch = $user->branch_id;
        }

        $this->loadDashboardData();
    }

    private function loadDashboardData()
    {
        $cacheKey = "dashboard_{$this->selectedBranch}_" . Auth::id();
        $cacheTTL = 5; // Cache for 4 minutes

        // Try to get data from cache first
        if (Cache::has($cacheKey)) {
            $data = Cache::get($cacheKey);
            $this->monthlyTotals = $data['monthlyTotals'];
            $this->monthlyLoanDistribution = $data['monthlyLoanDistribution'];
            $this->monthlyRevenue = $data['monthlyRevenue'];
            $this->monthlyExpense = $data['monthlyExpense'];
            $this->monthlyProfit = $data['monthlyProfit'];
            $this->weeklyUserCollections = $data['weeklyUserCollections'];

            // Dashboard metrics
            $this->todayCollection = $data['todayCollection'];
            $this->todayPrincipalCollected = $data['todayPrincipalCollected'];
            $this->todayInterestCollected = $data['todayInterestCollected'];
            $this->todayPendingCollection = $data['todayPendingCollection'];
            $this->totalCustomers = $data['totalCustomers'];
            $this->totalActiveLoans = $data['totalActiveLoans'];
            $this->cashInHand = $data['cashInHand'];
            $this->totalUsers = $data['totalUsers'];
        } else {
            // Calculate data and store in cache
            $this->loadMonthlyData();
            $this->loadDashboardMetrics();

            Cache::put($cacheKey, [
                'monthlyTotals' => $this->monthlyTotals,
                'monthlyLoanDistribution' => $this->monthlyLoanDistribution,
                'monthlyRevenue' => $this->monthlyRevenue,
                'monthlyExpense' => $this->monthlyExpense,
                'monthlyProfit' => $this->monthlyProfit,
                'weeklyUserCollections' => $this->weeklyUserCollections,

                // Dashboard metrics
                'todayCollection' => $this->todayCollection,
                'todayPrincipalCollected' => $this->todayPrincipalCollected,
                'todayInterestCollected' => $this->todayInterestCollected,
                'todayPendingCollection' => $this->todayPendingCollection,
                'totalCustomers' => $this->totalCustomers,
                'totalActiveLoans' => $this->totalActiveLoans,
                'cashInHand' => $this->cashInHand,
                'totalUsers' => $this->totalUsers,
            ], now()->addMinutes($cacheTTL));
        }

        // Always load fresh activities
        $this->recentActivities = $this->getRecentActivities();
    }

    private function loadMonthlyData()
    {
        $this->monthlyTotals = $this->getMonthlyTotals();
        $this->monthlyLoanDistribution = $this->getMonthlyLoanDistribution();
        $this->monthlyRevenue = $this->getMonthlyFinancials('revenue');
        $this->monthlyExpense = $this->getMonthlyFinancials('expense');
        $this->monthlyProfit = $this->calculateMonthlyProfit();
        $this->weeklyUserCollections = $this->getWeeklyUserCollections();
    }

    private function loadDashboardMetrics()
    {
        $today = now()->format('Y-m-d');

        // Today's Collection Metrics
        $todayCollections = $this->getTodayCollections();
        $this->todayCollection = $todayCollections['total'];
        $this->todayPrincipalCollected = $todayCollections['principal'];
        $this->todayInterestCollected = $todayCollections['interest'];

        // Today's Pending Collection
        $this->todayPendingCollection = $this->getTodayPendingCollection();

        // Customer and Loan Counts
        $this->totalCustomers = $this->getTotalCustomers();
        $this->totalActiveLoans = $this->getTotalActiveLoans();

        // Cash in Hand
        $this->cashInHand = $this->getCashInHand();

        // Total Users
        $this->totalUsers = $this->getTotalUsers();
    }

    private function getTodayCollections()
    {
        $today = now()->format('Y-m-d');

        $query = Collection::query()
            ->whereDate('collection_date', $today)
            ->join('loan', 'collections.loan_id', '=', 'loan.id')
            ->join('centers', 'loan.center_id', '=', 'centers.id');

        if ($this->shouldFilterByBranch()) {
            $query->where('centers.branch_id', $this->selectedBranch);
        }

        $collections = $query->selectRaw('SUM(collections.collected_amount) as total,
                          SUM(collections.principal_amount) as principal,
                          SUM(collections.interest_amount) as interest')
                    ->first();

        // Previous day for comparison
        $yesterday = now()->subDay()->format('Y-m-d');
        $previousQuery = Collection::query()
            ->whereDate('collection_date', $yesterday)
            ->join('loan', 'collections.loan_id', '=', 'loan.id')
            ->join('centers', 'loan.center_id', '=', 'centers.id');

        if ($this->shouldFilterByBranch()) {
            $previousQuery->where('centers.branch_id', $this->selectedBranch);
        }

        $previousCollections = $previousQuery->selectRaw('SUM(collections.collected_amount) as total,
                                  SUM(collections.principal_amount) as principal,
                                  SUM(collections.interest_amount) as interest')
                            ->first();

        $totalGrowth = $previousCollections->total > 0
            ? (($collections->total - $previousCollections->total) / $previousCollections->total) * 100
            : 100;

        $principalGrowth = $previousCollections->principal > 0
            ? (($collections->principal - $previousCollections->principal) / $previousCollections->principal) * 100
            : 100;

        $interestGrowth = $previousCollections->interest > 0
            ? (($collections->interest - $previousCollections->interest) / $previousCollections->interest) * 100
            : 100;

        return [
            'total' => [
                'amount' => $collections->total ?? 0,
                'growth' => round($totalGrowth, 2)
            ],
            'principal' => [
                'amount' => $collections->principal ?? 0,
                'growth' => round($principalGrowth, 2)
            ],
            'interest' => [
                'amount' => $collections->interest ?? 0,
                'growth' => round($interestGrowth, 2)
            ]
        ];
    }



private function getTodayPendingCollection()
{
    $today = now()->toDateString(); // Get only today's date

    $query = LoanCollectionSchedule::query()
        ->where('description', 'Installment Payment')
        ->where('pending_due', '>', 0)
        ->whereDate('date', $today) // Only take today's pending due
        ->join('loan', 'loan_collection_schedules.loan_id', '=', 'loan.id')
        ->join('centers', 'loan.center_id', '=', 'centers.id');

    if ($this->shouldFilterByBranch()) {
        $query->where('centers.branch_id', $this->selectedBranch);
    }

    $pendingAmount = $query->sum('pending_due'); // Sum of only today's pending dues

    return [
        'amount' => $pendingAmount,
        'growth' => 0
    ];
}

    private function getTotalCustomers()
    {
        $query = Customer::query()
            ->join('centers', 'customers.center_id', '=', 'centers.id');

        if ($this->shouldFilterByBranch()) {
            $query->where('centers.branch_id', $this->selectedBranch);
        }

        $currentCount = $query->count();

        // Get count from a month ago for comparison
        $lastMonth = now()->subMonth();
        $previousQuery = Customer::query()
            ->join('centers', 'customers.center_id', '=', 'centers.id')
            ->where('customers.created_at', '<', $lastMonth);

        if ($this->shouldFilterByBranch()) {
            $previousQuery->where('centers.branch_id', $this->selectedBranch);
        }

        $previousCount = $previousQuery->count();

        $growth = $previousCount > 0
            ? (($currentCount - $previousCount) / $previousCount) * 100
            : 100;

        return [
            'count' => $currentCount,
            'growth' => round($growth, 2)
        ];
    }

    private function getTotalActiveLoans()
{
    $query = Loan::query()
        ->join('centers', 'loan.center_id', '=', 'centers.id')
        ->join('loan_approvals', 'loan.id', '=', 'loan_approvals.loan_id')
        ->where('loan_approvals.status', 'active'); // Use approval status

    if ($this->shouldFilterByBranch()) {
        $query->where('centers.branch_id', $this->selectedBranch);
    }

    $currentCount = $query->count();

    // Get count from a month ago
    $lastMonth = now()->subMonth();
    $previousQuery = Loan::query()
        ->join('centers', 'loan.center_id', '=', 'centers.id')
        ->join('loan_approvals', 'loan.id', '=', 'loan_approvals.loan_id')
        ->where('loan_approvals.status', 'active')
        ->where('loan.created_at', '<', $lastMonth);

    if ($this->shouldFilterByBranch()) {
        $previousQuery->where('centers.branch_id', $this->selectedBranch);
    }

    $previousCount = $previousQuery->count();

    $growth = $previousCount > 0
        ? (($currentCount - $previousCount) / $previousCount) * 100
        : 100;

    return [
        'count' => $currentCount,
        'growth' => round($growth, 2)
    ];
}

    private function getCashInHand()
    {
        if ($this->selectedBranch === 'all') {
            $totalBalance = Account::where('type', 'cash')
                ->where('category', 'asset')
                ->where('account_name', 'LIKE', 'Cash in Hand%')
                ->sum('balance');

            // Get balance from a month ago for comparison
            $lastMonth = now()->subMonth();
            $previousBalance = Account::where('type', 'cash')
                ->where('category', 'asset')
                ->where('account_name', 'LIKE', 'Cash in Hand%')
                ->where('updated_at', '<', $lastMonth)
                ->sum('balance');
        } else {
            $totalBalance = Account::where('type', 'cash')
                ->where('category', 'asset')
                ->where('branch_id', $this->selectedBranch)
                ->where('account_name', 'LIKE', 'Cash in Hand%')
                ->sum('balance');

            // Get balance from a month ago for comparison
            $lastMonth = now()->subMonth();
            $previousBalance = Account::where('type', 'cash')
                ->where('category', 'asset')
                ->where('branch_id', $this->selectedBranch)
                ->where('account_name', 'LIKE', 'Cash in Hand%')
                ->where('updated_at', '<', $lastMonth)
                ->sum('balance');
        }

        $growth = $previousBalance > 0
            ? (($totalBalance - $previousBalance) / $previousBalance) * 100
            : 100;

        return [
            'amount' => $totalBalance,
            'growth' => round($growth, 2)
        ];
    }

    private function getTotalUsers()
    {
        $query = User::query();

        if ($this->shouldFilterByBranch() && $this->selectedBranch !== 'all') {
            $query->where('branch_id', $this->selectedBranch);
        }

        $currentCount = $query->count();

        // Get count from a month ago for comparison
        $lastMonth = now()->subMonth();
        $previousQuery = User::query()
            ->where('created_at', '<', $lastMonth);

        if ($this->shouldFilterByBranch() && $this->selectedBranch !== 'all') {
            $previousQuery->where('branch_id', $this->selectedBranch);
        }

        $previousCount = $previousQuery->count();

        $growth = $previousCount > 0
            ? (($currentCount - $previousCount) / $previousCount) * 100
            : 100;

        return [
            'count' => $currentCount,
            'growth' => round($growth, 2)
        ];
    }

    public function refreshActivities()
    {
        $this->recentActivities = $this->getRecentActivities();
    }

    private function getRecentActivities()
    {
        $cacheKey = "activities_{$this->selectedBranch}_" . Auth::id();
        $cacheTTL = 3; // Cache for 2 minutes

        return Cache::remember($cacheKey, now()->addMinutes($cacheTTL), function () {
            $query = Activity::whereIn('subject_type', self::TRACKED_MODELS)
                ->with(['subject', 'causer'])
                ->latest()
                ->limit(20); // Fetch just enough to filter by branch

            // Filter by branch if needed
            if ($this->shouldFilterByBranch()) {
                $activities = $query->get()->filter(function ($activity) {
                    if (!$activity->subject) {
                        return false;
                    }

                    return $this->isActivityInSelectedBranch($activity);
                });
            } else {
                $activities = $query->get();
            }

            // Group by model type and take the most recent
            $latestActivities = collect();
            foreach (self::TRACKED_MODELS as $modelType) {
                $latest = $activities->where('subject_type', $modelType)->first();
                if ($latest) {
                    $latestActivities->push($latest);
                }
            }

            // Format the activities for display
            return $latestActivities->map(function ($activity) {
                $modelName = class_basename($activity->subject_type);
                $createdAt = Carbon::parse($activity->created_at);

                return [
                    'id' => $activity->id,
                    'model' => $modelName,
                    'description' => $activity->description,
                    'details' => $this->getActivityDetails($activity),
                    'icon' => self::MODEL_ICONS[$modelName] ?? 'fa-bell',
                    'color' => self::MODEL_COLORS[$modelName] ?? 'secondary',
                    'created_at' => $createdAt->format('d M H:i A'),
                    'created_at_diff' => $createdAt->diffForHumans(),
                ];
            })->sortByDesc('id')->take(6)->values()->toArray();
        });
    }

    private function isActivityInSelectedBranch($activity)
    {
        $subjectType = $activity->subject_type;
        $subject = $activity->subject;

        if ($subjectType === Customer::class) {
            return $subject->center && $subject->center->branch_id == $this->selectedBranch;
        }

        if ($subjectType === Collection::class || $subjectType === LoanApproval::class) {
            return $subject->loan &&
                $subject->loan->center &&
                $subject->loan->center->branch_id == $this->selectedBranch;
        }

        if (in_array($subjectType, [Payment::class, PettyCashRequest::class, Transaction::class])) {
            return $subject->branch_id == $this->selectedBranch;
        }

        return false;
    }

    private function getActivityDetails($activity)
    {
        $modelName = class_basename($activity->subject_type);
        $event = ucfirst($activity->description); // Capitalize first letter
        $userName = $activity->causer ? ($activity->causer->name ?? 'Unknown User') : 'System';

        if (!$activity->subject) {
            return "{$event} by {$userName}: Record no longer exists";
        }

        switch ($modelName) {
            case 'Customer':
                return "{$event} by {$userName}: " . $activity->subject->full_name . ' - ' . $activity->subject->customer_no;

            case 'Collection':
                $amount = number_format($activity->subject->collected_amount, 2);
                $loanNumber = $activity->subject->loan->loan_number ?? 'Unknown';
                return "{$event} by {$userName}: Amount: $amount, Loan: $loanNumber";

            case 'LoanApproval':
                $status = ucfirst($activity->subject->status);
                $loanNumber = $activity->subject->loan->loan_number ?? 'Unknown';
                return "{$event} by {$userName}: Status: $status, Loan: $loanNumber";

            case 'Payment':
                $amount = number_format($activity->subject->total_amount, 2);
                $category = $activity->subject->paymentCategory->name ?? 'Unknown';
                return "{$event} by {$userName}: Amount: $amount, Category: $category";

            case 'PettyCashRequest':
                $amount = number_format($activity->subject->amount, 2);
                $status = ucfirst($activity->subject->status);
                return "{$event} by {$userName}: Amount: $amount, Status: $status";

            case 'Transaction':
                $amount = number_format($activity->subject->amount, 2);
                $type = $activity->subject->transaction_type;
                return "{$event} by {$userName}: Amount: $amount, Type: $type";

            default:
                return "{$event} by {$userName}: No details available";
        }
    }

    private function getWeeklyUserCollections()
    {
        $userId = Auth::id();
        $startOfWeek = now()->startOfWeek();
        $weeklyData = array_fill(0, 7, 0); // Initialize with zeros for all days

        $collections = Collection::where('collector_id', $userId)
            ->whereBetween('collection_date', [
                $startOfWeek->format('Y-m-d'),
                $startOfWeek->copy()->addDays(6)->format('Y-m-d')
            ])
            ->selectRaw('DAYOFWEEK(collection_date) as day_of_week, SUM(collected_amount) as total')
            ->groupBy('day_of_week')
            ->get();

        foreach ($collections as $collection) {
            $dayIndex = ($collection->day_of_week + 5) % 7;
            $weeklyData[$dayIndex] = $collection->total;
        }

        return $weeklyData;
    }

    private function getMonthlyFinancials($category)
    {
        $query = Transaction::query()
            ->when($category === 'revenue', function ($q) {
                return $q->join('accounts', 'transactions.credit_account_id', '=', 'accounts.id')
                        ->where('accounts.category', '=', 'revenue');
            })
            ->when($category === 'expense', function ($q) {
                return $q->join('accounts', 'transactions.debit_account_id', '=', 'accounts.id')
                        ->where('accounts.category', '=', 'expense');
            })
            ->selectRaw('MONTH(transactions.transaction_date) as month, SUM(transactions.amount) as total')
            ->whereYear('transactions.transaction_date', now()->year);

        if ($this->shouldFilterByBranch()) {
            $query->where('transactions.branch_id', $this->selectedBranch);
        }

        $financials = $query->groupBy('month')
            ->pluck('total', 'month');

        return $this->adjustMonthlyData($financials);
    }

    private function calculateMonthlyProfit()
    {
        $profit = [];
        for ($i = 0; $i < 12; $i++) {
            $profit[$i] = $this->monthlyRevenue[$i] - $this->monthlyExpense[$i];
        }
        return $profit;
    }

    private function getMonthlyLoanDistribution()
    {
        $query = Loan::query()
            ->join('centers', 'loan.center_id', '=', 'centers.id')
            ->selectRaw('MONTH(loan.created_at) as month, SUM(loan.loan_amount) as total')
            ->whereYear('loan.created_at', now()->year);

        if ($this->shouldFilterByBranch()) {
            $query->where('centers.branch_id', $this->selectedBranch);
        }

        $loans = $query->groupBy('month')
            ->pluck('total', 'month');

        return $this->adjustMonthlyData($loans);
    }

    private function getMonthlyTotals()
    {
        $query = Collection::query()
            ->join('loan', 'collections.loan_id', '=', 'loan.id')
            ->join('centers', 'loan.center_id', '=', 'centers.id')
            ->selectRaw('MONTH(collections.collection_date) as month, SUM(collections.collected_amount) as total')
            ->whereYear('collections.collection_date', now()->year);

        if ($this->shouldFilterByBranch()) {
            $query->where('centers.branch_id', $this->selectedBranch);
        }

        $collections = $query->groupBy('month')
            ->pluck('total', 'month');

        return $this->adjustMonthlyData($collections);
    }

    public function updatedSelectedBranch()
    {
        // Prevent unauthorized branch selection
        if (!Auth::user()->hasPermissionTo('view all branches')) {
            $this->selectedBranch = Auth::user()->branch_id;
            return;
        }

        // Clear cache for this branch/user combination
        Cache::forget("dashboard_{$this->selectedBranch}_" . Auth::id());
        Cache::forget("activities_{$this->selectedBranch}_" . Auth::id());

        // Reload all dashboard data
        $this->loadDashboardData();

        $this->dispatch('branchUpdated');


    }

    private function shouldFilterByBranch()
    {
        return $this->selectedBranch !== 'all' || !Auth::user()->hasPermissionTo('view all branches');
    }

    private function adjustMonthlyData($data)
    {
        $adjustedTotals = array_fill(0, 12, 0);
        foreach ($data as $month => $total) {
            $adjustedTotals[$month - 1] = $total;
        }
        return $adjustedTotals;
    }

    public function render()
    {
        $activeUsersCount = User::where('last_seen_at', '>=', now()->subMinute())->count();
        return view('livewire.dashboard.main-dashboard', [
            'activeUsersCount' => $activeUsersCount,
        ]);
    }
}
