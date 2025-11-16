<?php

namespace App\Livewire\Reports;

use App\Exports\FinancialReportExport;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\Transfer;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class BranchFinancialReport extends Component
{
    public $branches = [];
    public $selectedBranches = [];
    public $startDate;
    public $endDate;
    public $reportData = [];
    public $showReport = false;
    public Branch $branch;

    public function mount()
    {
        $this->branches = Branch::all();
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->format('Y-m-d');
    }

    public function generateReport()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'selectedBranches' => 'required|array|min:1',
        ]);

        $this->reportData = $this->collectReportData();
        $this->showReport = true;
    }

    private function collectReportData()
    {
        $data = [];
        $branchIds = $this->selectedBranches;

        // If no branches are selected, use all branches
        if (empty($branchIds)) {
            $branchIds = collect($this->branches)->pluck('id')->toArray();
        }

        foreach ($branchIds as $branchId) {
            $branch = Branch::find($branchId);

            // Skip if branch not found
            if (!$branch) continue;

            $branchData = [
                'branch' => $branch,
                'assets' => $this->getAccountsByCategory($branchId, 'asset'),
                'liabilities' => $this->getAccountsByCategory($branchId, 'liability'),
                'equity' => $this->getAccountsByCategory($branchId, 'equity'),
                'revenue' => $this->getAccountsByCategory($branchId, 'revenue'),
                'expenses' => $this->getAccountsByCategory($branchId, 'expense'),
                'transactions' => $this->getTransactionsSummary($branchId),
                'summary' => $this->getFinancialSummary($branchId),
            ];

            $data[$branchId] = $branchData;
        }

        // Add company-wide totals
        $data['totals'] = $this->calculateTotals($data);

        return $data;
    }

    private function getAccountsByCategory($branchId, $category)
    {
        return Account::where('branch_id', $branchId)
            ->where('category', $category)
            ->get()
            ->map(function($account) {
                // Get transactions within date range for this account
                $transactions = Transaction::where(function($query) use ($account) {
                    $query->where('debit_account_id', $account->id)
                        ->orWhere('credit_account_id', $account->id);
                })
                ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
                ->get();

                // Calculate account activity
                $debits = $transactions->where('debit_account_id', $account->id)->sum('amount');
                $credits = $transactions->where('credit_account_id', $account->id)->sum('amount');

                // Calculate net change based on account type
                $netChange = match($account->category) {
                    'asset', 'expense' => $debits - $credits,
                    'liability', 'equity', 'revenue' => $credits - $debits,
                    default => 0,
                };

                return [
                    'id' => $account->id,
                    'account_number' => $account->account_number,
                    'account_name' => $account->account_name,
                    'type' => $account->type,
                    'opening_balance' => $this->getOpeningBalance($account),
                    'debits' => $debits,
                    'credits' => $credits,
                    'net_change' => $netChange,
                    'ending_balance' => $this->getOpeningBalance($account) + $netChange,
                ];
            })
            ->toArray();
    }

    private function getOpeningBalance($account)
    {
        // Get balance at start date by calculating transactions before start date
        $previousDebits = Transaction::where('debit_account_id', $account->id)
            ->where('transaction_date', '<', $this->startDate)
            ->sum('amount');

        $previousCredits = Transaction::where('credit_account_id', $account->id)
            ->where('transaction_date', '<', $this->startDate)
            ->sum('amount');

        // Calculate based on account type
        return match($account->category) {
            'asset', 'expense' => $previousDebits - $previousCredits,
            'liability', 'equity', 'revenue' => $previousCredits - $previousDebits,
            default => 0,
        };
    }

    private function getTransactionsSummary($branchId)
    {
        $transactions = Transaction::where('branch_id', $branchId)
            ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
            ->get();

        // Group transactions by type
        $summary = [];
        foreach ($transactions as $transaction) {
            $type = $transaction->transaction_type;

            if (!isset($summary[$type])) {
                $summary[$type] = [
                    'count' => 0,
                    'total' => 0,
                ];
            }

            $summary[$type]['count']++;
            $summary[$type]['total'] += $transaction->amount;
        }

        return $summary;
    }

    private function getFinancialSummary($branchId)
    {
        $assets = $this->sumAccountBalances($this->getAccountsByCategory($branchId, 'asset'));
        $liabilities = $this->sumAccountBalances($this->getAccountsByCategory($branchId, 'liability'));
        $equity = $this->sumAccountBalances($this->getAccountsByCategory($branchId, 'equity'));
        $revenue = $this->sumAccountBalances($this->getAccountsByCategory($branchId, 'revenue'));
        $expenses = $this->sumAccountBalances($this->getAccountsByCategory($branchId, 'expense'));

        return [
            'total_assets' => $assets,
            'total_liabilities' => $liabilities,
            'total_equity' => $equity + $revenue - $expenses,
            'total_liabilities_and_equity' => $liabilities + $equity + $revenue - $expenses,
            'total_revenue' => $revenue,
            'total_expenses' => $expenses,
            'net_income' => $revenue - $expenses,
        ];
    }

    private function sumAccountBalances($accounts)
    {
        return array_sum(array_column($accounts, 'ending_balance'));
    }

    private function calculateTotals($data)
    {
        $totalAssets = 0;
        $totalLiabilities = 0;
        $totalEquity = 0;
        $totalRevenue = 0;
        $totalExpenses = 0;

        foreach ($data as $branchId => $branchData) {
            // Skip the totals entry
            if ($branchId === 'totals') continue;

            $totalAssets += $branchData['summary']['total_assets'];
            $totalLiabilities += $branchData['summary']['total_liabilities'];
            $totalEquity += $branchData['summary']['total_equity'];
            $totalRevenue += $branchData['summary']['total_revenue'];
            $totalExpenses += $branchData['summary']['total_expenses'];
        }

        return [
            'total_assets' => $totalAssets,
            'total_liabilities' => $totalLiabilities,
            'total_equity' => $totalEquity,
            'total_liabilities_and_equity' => $totalLiabilities + $totalEquity,
            'total_revenue' => $totalRevenue,
            'total_expenses' => $totalExpenses,
            'net_income' => $totalRevenue - $totalExpenses,
        ];
    }

    public function exportPDF()
    {
        $data = [
            'reportData' => $this->reportData,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'branches' => Branch::whereIn('id', $this->selectedBranches)->get(),
        ];

        // Use the correct facade or instance for DomPDF
        $pdf = Pdf::loadView('exports.financial-report-pdf', $data);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->stream();
        }, 'financial-report.pdf');
    }

    public function exportExcel()
    {
        if (empty($this->reportData)) {
            $this->generateReport(); // Ensure data is populated
        }

        return Excel::download(new FinancialReportExport($this->reportData), 'financial-report.xlsx');
    }



    public function render()
    {
        return view('livewire.reports.branch-financial-report');
    }
}
