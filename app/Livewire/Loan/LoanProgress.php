<?php


namespace App\Livewire\Loan;

use App\Models\LoanProgress as ModelsLoanProgress;
use App\Models\Branch;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class LoanProgress extends Component
{
    use WithPagination;

    public $perPage = 20;
    public $search = '';
    public $branchFilter = 'all';
    public $branches = [];

    public function mount()
    {
        $this->branches = Branch::orderBy('name')->get();
    }

    public function render()
    {
        $user = Auth::user();


            $query = ModelsLoanProgress::query()
            ->with(['loan', 'loan.customer', 'loan.center.branch', 'loan.loanCollections'])
            ->orderBy('created_at', 'desc');

        // Apply Branch Restriction
        if (!$user->hasPermissionTo('View All Branch Loans')) {
            $userBranchId = $user->branch_id;

            $query->whereHas('loan.center.branch', function ($q) use ($userBranchId) {
                $q->where('id', $userBranchId);
            });
        }

        // Apply Branch Filter
        if ($this->branchFilter !== 'all') {
            $query->whereHas('loan.center.branch', function ($q) {
                $q->where('id', $this->branchFilter);
            });
        }

        // Apply Search Filter
        if (!empty($this->search)) {
            $query->whereHas('loan.customer', function ($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('nic', 'like', '%' . $this->search . '%');
            });
        }

        $loanProgress = $query->paginate($this->perPage);

        return view('livewire.loan.loan-progress', [
            'loanProgress' => $loanProgress,
            'start' => ($loanProgress->currentPage() - 1) * $loanProgress->perPage(),
            'branches' => $this->branches,
            'branchFilter' => $this->branchFilter,
        ]);
    }

    public function exportCsv()
    {
        $user = Auth::user();

        $query = ModelsLoanProgress::query()
            ->with(['loan', 'loan.customer', 'loan.center.branch', 'loan.loanCollections'])
            ->orderBy('created_at', 'desc');

        if (!$user->hasPermissionTo('View All Branch Loans')) {
            $userBranchId = $user->branch_id;
            $query->whereHas('loan.center.branch', function ($q) use ($userBranchId) {
                $q->where('id', $userBranchId);
            });
        }

        // Apply Branch Filter
        if ($this->branchFilter !== 'all') {
            $query->whereHas('loan.center.branch', function ($q) {
                $q->where('id', $this->branchFilter);
            });
        }

        if (!empty($this->search)) {
            $query->whereHas('loan.customer', function ($q) {
                $q->where('full_name', 'like', '%' . $this->search . '%')
                  ->orWhere('nic', 'like', '%' . $this->search . '%');
            });
        }

        $loanProgress = $query->get();

        $csvData = [];
        $csvData[] = [
            '#', 'Loan No', 'Customer', 'Principal', 'Total', 'Principal Collected', 'Interest Collected', 'Principal Balance', 'Paid', 'Balance', 'Status', 'Branch'
        ];

        // Initialize totals
        $totalPrincipal = 0;
        $totalTotal = 0;
        $totalPrincipalCollected = 0;
        $totalInterestCollected = 0;
        $totalPaid = 0;
        $totalBalance = 0;

        foreach ($loanProgress as $index => $item) {
            $loan = $item->loan;
            $customer = $loan?->customer;
            $branch = $loan?->center?->branch;

            $principal = $loan ? $loan->loan_amount : 0;
            $total = $item->total_amount ?? 0;
            $principalCollected = $loan ? $loan->loanCollections->sum('principal_amount') : 0;
            $interestCollected = $loan ? $loan->loanCollections->sum('interest_amount') : 0;
            $principalBalance = $principal - $principalCollected;
            $paid = $item->total_paid_amount ?? 0;
            $balance = $item->balance ?? 0;

            // Add to totals
            $totalPrincipal += $principal;
            $totalTotal += $total;
            $totalPrincipalCollected += $principalCollected;
            $totalInterestCollected += $interestCollected;
            $totalPaid += $paid;
            $totalBalance += $balance;

            $csvData[] = [
                $index + 1,
                $loan?->loan_number ?? '',
                $customer ? ($customer->full_name . ' (' . $customer->nic . ')') : '',
                number_format($principal, 2),
                number_format($total, 2),
                number_format($principalCollected, 2),
                number_format($interestCollected, 2),
                number_format($principalBalance, 2),
                number_format($paid, 2),
                number_format($balance, 2),
                $item->status ?? '',
                $branch?->name ?? '',
            ];
        }

        // Add summary row
        $csvData[] = [
            '', '', 'Total',
            number_format($totalPrincipal, 2),
            number_format($totalTotal, 2),
            number_format($totalPrincipalCollected, 2),
            number_format($totalInterestCollected, 2),
            number_format($totalPrincipal - $totalPrincipalCollected, 2), // Principal Balance total
            number_format($totalPaid, 2),
            number_format($totalBalance, 2),
            '', ''
        ];

        $filename = 'loan_progress_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://temp', 'r+');
        foreach ($csvData as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response()->streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
