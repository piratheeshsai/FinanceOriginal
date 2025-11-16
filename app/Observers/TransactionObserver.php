<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\Account;
use Illuminate\Support\Facades\DB;

class TransactionObserver
{
    /**
     * Adjust account balance based on category and debit/credit
     */
    private function adjustBalance(Account $account, float $amount, bool $isDebit): void
    {
        if ($isDebit) {
            // Debit rules
            if (in_array($account->category, ['asset', 'expense'])) {
                $account->balance += $amount; // Assets/expenses increase with debit
            } else {
                $account->balance -= $amount; // Liabilities/equity/revenue decrease with debit
            }
        } else {
            // Credit rules
            if (in_array($account->category, ['liability', 'equity', 'revenue'])) {
                $account->balance += $amount; // Liabilities/equity/revenue increase with credit
            } else {
                $account->balance -= $amount; // Assets/expenses decrease with credit
            }
        }
    }

    /**
     * Handle the Transaction "created" event
     */
    public function created(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $debitAccount = $transaction->debitAccount;
            $creditAccount = $transaction->creditAccount;

            $this->adjustBalance($debitAccount, $transaction->amount, true);
            $this->adjustBalance($creditAccount, $transaction->amount, false);

            $debitAccount->save();
            $creditAccount->save();
        });
    }

    /**
     * Handle the Transaction "updated" event
     */
    public function updated(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            // Get original values before update
            $originalDebitAccount = Account::find($transaction->getOriginal('debit_account_id'));
            $originalCreditAccount = Account::find($transaction->getOriginal('credit_account_id'));
            $originalAmount = $transaction->getOriginal('amount');

            // Reverse original transaction
            if ($originalDebitAccount && $originalCreditAccount) {
                $this->adjustBalance($originalDebitAccount, -$originalAmount, true);
                $this->adjustBalance($originalCreditAccount, -$originalAmount, false);
                $originalDebitAccount->save();
                $originalCreditAccount->save();
            }

            // Apply new transaction
            $newDebitAccount = $transaction->debitAccount;
            $newCreditAccount = $transaction->creditAccount;
            $newAmount = $transaction->amount;

            $this->adjustBalance($newDebitAccount, $newAmount, true);
            $this->adjustBalance($newCreditAccount, $newAmount, false);
            $newDebitAccount->save();
            $newCreditAccount->save();
        });
    }

    /**
     * Handle the Transaction "deleted" event
     */
    public function deleted(Transaction $transaction): void
    {
        DB::transaction(function () use ($transaction) {
            $debitAccount = $transaction->debitAccount;
            $creditAccount = $transaction->creditAccount;

            // Reverse the transaction
            $this->adjustBalance($debitAccount, -$transaction->amount, true);
            $this->adjustBalance($creditAccount, -$transaction->amount, false);

            $debitAccount->save();
            $creditAccount->save();
        });
    }

    // ... restored() and forceDeleted() can remain empty
}
