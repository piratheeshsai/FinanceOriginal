<?php

namespace App\Livewire\Account;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProfitLossReport extends Component
{
    public $startDate;
    public $endDate;
    public $selectedBranch = 'all';
    public $branches;

    public function mount()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->branches = Branch::all();
    }

    public function calculateProfitLoss()
    {
        // Optimized query using raw SQL for better performance
        $results = DB::select('
            WITH RevenueData AS (
                SELECT SUM(t.amount) as total_revenue
                FROM transactions t
                JOIN accounts a ON t.credit_account_id = a.id
                WHERE t.transaction_date BETWEEN ? AND ?
                AND a.type IN (\'interest_income\', \'document_charge_income\', \'late_fee_income\')
                ' . ($this->selectedBranch != 'all' ? 'AND a.branch_id = ?' : '') . '
            ),
            ExpenseData AS (
                SELECT SUM(t.amount) as total_expenses
                FROM transactions t
                JOIN accounts a ON t.debit_account_id = a.id
                WHERE t.transaction_date BETWEEN ? AND ?
                AND a.type IN (\'salary_expense\', \'rent_expense\', \'utilities_expense\',
                              \'office_supplies_expense\', \'petty_cash_expenses\', \'other_expenses\')
                ' . ($this->selectedBranch != 'all' ? 'AND a.branch_id = ?' : '') . '
            )
            SELECT
                COALESCE(rd.total_revenue, 0) as total_revenue,
                COALESCE(ed.total_expenses, 0) as total_expenses,
                COALESCE(rd.total_revenue, 0) - COALESCE(ed.total_expenses, 0) as net_profit
            FROM RevenueData rd, ExpenseData ed
        ', $this->getQueryParams());

        return [
            'total_revenue' => $results[0]->total_revenue ?? 0,
            'total_expenses' => $results[0]->total_expenses ?? 0,
            'net_profit' => $results[0]->net_profit ?? 0
        ];
    }

    public function calculateBreakdown()
    {
        // Optimized revenue breakdown query
        $revenueBreakdown = DB::table('transactions as t')
            ->join('accounts as a', 't.credit_account_id', '=', 'a.id')
            ->whereBetween('t.transaction_date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->whereIn('a.type', [
                'interest_income', 'document_charge_income', 'late_fee_income'
            ])
            ->when($this->selectedBranch != 'all', function($query) {
                return $query->where('a.branch_id', $this->selectedBranch);
            })
            ->select('a.type', DB::raw('SUM(t.amount) as total'))
            ->groupBy('a.type')
            ->pluck('total', 'type')
            ->toArray();

        // Optimized expense breakdown query
        $expenseBreakdown = DB::table('transactions as t')
            ->join('accounts as a', 't.debit_account_id', '=', 'a.id')
            ->whereBetween('t.transaction_date', [
                Carbon::parse($this->startDate)->startOfDay(),
                Carbon::parse($this->endDate)->endOfDay()
            ])
            ->whereIn('a.type', [
                'salary_expense', 'rent_expense', 'utilities_expense',
                'office_supplies_expense', 'petty_cash_expenses', 'other_expenses'
            ])
            ->when($this->selectedBranch != 'all', function($query) {
                return $query->where('a.branch_id', $this->selectedBranch);
            })
            ->select('a.type', DB::raw('SUM(t.amount) as total'))
            ->groupBy('a.type')
            ->pluck('total', 'type')
            ->toArray();

        return [
            'revenue' => $revenueBreakdown,
            'expenses' => $expenseBreakdown
        ];
    }

    private function getQueryParams()
    {
        $params = [
            Carbon::parse($this->startDate)->startOfDay(),
            Carbon::parse($this->endDate)->endOfDay()
        ];

        if ($this->selectedBranch != 'all') {
            $params[] = $this->selectedBranch;
        }

        $params[] = Carbon::parse($this->startDate)->startOfDay();
        $params[] = Carbon::parse($this->endDate)->endOfDay();

        if ($this->selectedBranch != 'all') {
            $params[] = $this->selectedBranch;
        }

        return $params;
    }

    public function render()
    {
        $profitLossData = $this->calculateProfitLoss();
        $breakdown = $this->calculateBreakdown();

        return view('livewire.account.profit-loss-report', [
            'profitLossData' => $profitLossData,
            'revenueBreakdown' => $breakdown['revenue'],
            'expenseBreakdown' => $breakdown['expenses'],
            'branches' => $this->branches
        ]);
    }
}
