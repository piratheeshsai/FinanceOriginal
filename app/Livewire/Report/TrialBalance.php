<?php

namespace App\Livewire\Report;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Transaction;
use Livewire\Component;

class TrialBalance extends Component
{


    public $branches = [];
    public $selectedBranch = null;
    public $startDate;
    public $endDate;
    public $trialBalanceData = [];
    public $totalDebit = 0;
    public $totalCredit = 0;

    public function mount()
    {
        // Set default dates to current month
        $this->startDate = now()->startOfMonth()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');

        // Load branches for filter
        $this->branches = Branch::all();
    }

    public function generateTrialBalance()
    {
        $this->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $query = Account::query();

        // Apply branch filter if selected
        if ($this->selectedBranch) {
            $query->where('branch_id', $this->selectedBranch);
        }

        // Get all accounts
        $accounts = $query->get();

        $this->trialBalanceData = [];
        $this->totalDebit = 0;
        $this->totalCredit = 0;

        foreach ($accounts as $account) {
            // Calculate transactions within date range
            $debits = Transaction::where('debit_account_id', $account->id)
                ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
                ->sum('amount');

            $credits = Transaction::where('credit_account_id', $account->id)
                ->whereBetween('transaction_date', [$this->startDate, $this->endDate])
                ->sum('amount');

            // Calculate net balance based on account category
            $debitBalance = 0;
            $creditBalance = 0;

            // Handle different account types according to accounting principles
            if (in_array($account->category, ['asset', 'expense'])) {
                // Asset and expense accounts have debit balance
                $netBalance = $debits - $credits;
                if ($netBalance > 0) {
                    $debitBalance = $netBalance;
                } else {
                    $creditBalance = abs($netBalance);
                }
            } else {
                // Liability, equity, and revenue accounts have credit balance
                $netBalance = $credits - $debits;
                if ($netBalance > 0) {
                    $creditBalance = $netBalance;
                } else {
                    $debitBalance = abs($netBalance);
                }
            }

            // Only include accounts with activity or balance
            if ($debitBalance > 0 || $creditBalance > 0) {
                $this->trialBalanceData[] = [
                    'account_number' => $account->account_number,
                    'account_name' => $account->account_name,
                    'category' => $account->category,
                    'type' => $account->type,
                    'debit' => $debitBalance,
                    'credit' => $creditBalance,
                ];

                $this->totalDebit += $debitBalance;
                $this->totalCredit += $creditBalance;
            }
        }
    }




    public function exportPdf()
    {
        $pdf = app()->make('dompdf.wrapper');
        $branchName = $this->selectedBranch ? Branch::find($this->selectedBranch)->name : 'All Branches';

        $html = view('exports.trial-balance-pdf', [
            'trialBalanceData' => $this->trialBalanceData,
            'totalDebit' => $this->totalDebit,
            'totalCredit' => $this->totalCredit,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
            'branchName' => $branchName
        ])->render();

        $pdf->loadHTML($html);

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->stream();
        }, 'trial_balance_' . now()->format('Y-m-d') . '.pdf');
    }




    public function render()
    {


        return view('livewire.report.trial-balance');

    }
}


// return view('livewire.report.trial-balance')
//             ->extends('layouts.app')
//             ->section('content');
