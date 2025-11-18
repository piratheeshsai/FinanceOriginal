<?php

namespace App\Livewire\Reports;

use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Livewire\Component;

class BalanceSheet extends Component
{


    public $asOfDate;
    public $branchId = null;
    public $showDetails = false;

    public function mount()
    {
        $this->asOfDate = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        // Get accounts with proper branch isolation
       // Strict account isolation based on branch selection
       $accounts = Account::when($this->branchId, function($query) {
        // Branch view: only the selected branch's accounts
        return $query->where('branch_id', $this->branchId);
    }, function($query) {
        // Global view: only global accounts
        $query->whereNull('branch_id');
    })
    ->orderBy('category')
    ->orderBy('type')
    ->orderBy('account_name')
    ->get();

    // Separate account types
    $assetAccounts = $accounts->where('category', 'asset')->where('type', '!=', 'cash_drawer');;
    $liabilityAccounts = $accounts->where('category', 'liability');

    // Handle equity accounts based on context
    $equityAccounts = $this->branchId
        ? $accounts->where('type', 'branch_capital')
        : $accounts->where('type', 'capital');

    // Calculate income/expenses for current context
    $revenueAccounts = $accounts->where('category', 'revenue');
    $expenseAccounts = $accounts->where('category', 'expense');
    $netIncome = $revenueAccounts->sum('balance') - $expenseAccounts->sum('balance');
    $revenueAccounts = $accounts->where('category', 'revenue');
    $expenseAccounts = $accounts->where('category', 'expense');

    // Add these calculations
    $totalRevenue = $revenueAccounts->sum('balance');
    $totalExpenses = $expenseAccounts->sum('balance');
    $netIncome = $totalRevenue - $totalExpenses;
    // Calculate totals
    $totalAssets = $assetAccounts->sum('balance');
    $totalLiabilities = $liabilityAccounts->sum('balance');

    // Get capital and owner draw accounts
    $capitalAccount = $accounts->where('type', $this->branchId ? 'branch_capital' : 'capital')->first();
    $ownerDrawAccount = $accounts->where('type', 'owner_draw')->first();

    // Calculate total equity correctly
    $totalEquity = ($capitalAccount->balance ?? 0) - ($ownerDrawAccount->balance ?? 0) + $netIncome;

        return view('livewire.reports.balance-sheet', [
            'assetAccounts' => $assetAccounts,
            'liabilityAccounts' => $liabilityAccounts,
            'equityAccounts' => $equityAccounts,
            'revenueAccounts' => $revenueAccounts,
            'expenseAccounts' => $expenseAccounts,
            'totalAssets' => $totalAssets,
            'totalLiabilities' => $totalLiabilities,
            'totalEquity' => $totalEquity,
            'netIncome' => $netIncome,
            'balancesMatch' => (round($totalAssets, 2) === round(($totalLiabilities + $totalEquity), 2)),
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'capital' => $capitalAccount->balance ?? 0,
            'ownerDraw' => $ownerDrawAccount->balance ?? 0,
        ]);
    }


    public function toggleDetails()
    {
        $this->showDetails = !$this->showDetails;
    }
}
