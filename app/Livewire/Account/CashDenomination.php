<?php

namespace App\Livewire\Account;

use App\Models\Account;
use App\Models\Branch;
use App\Models\CashDenomination as ModelsCashDenomination;
use App\Models\DailyCashSummary;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class CashDenomination extends Component
{
    public $account;
    public $branchId;
    public $accountId;
    public $date;


    public $notes = [
        5000 => 0.0,
        2000 => 0.0,
        1000 => 0.0,
        500 => 0.0,
        100 => 0.0,
        50 => 0.0,
        20 => 0.0,
        10 => 0.0,
    ];
    public $coins = [
        10 => 0.0,
        5 => 0.0,
    ];

    public $totalAmount = 0;
    public $systemBalance = 0;
    public $difference = 0;
    public $remarks = '';

    protected $rules = [
        'notes.*' => 'numeric|min:0',
        'coins.*' => 'numeric|min:0',
        'remarks' => 'nullable|string|max:255',
    ];

    public function mount($branchId = null)
    {
        try {

            $this->date = $this->date ?? now()->toDateString();  // Ensure date is set

            $this->branchId = auth()->user()->branch_id;

            // Verify branch exists
            $branch = Branch::findOrFail($this->branchId);

            // Get cash account with explicit checks
            $this->account = Account::where('branch_id', $this->branchId)
                ->where('type', 'cash')
                ->firstOrFail(); // Critical change

            $this->accountId = $this->account->id;

            $this->loadSystemBalance();

            $this->loadExistingDenomination();

        } catch (\Exception $e) {
            Log::error('Component init failed', [
                'user' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            abort(403, 'Cash system configuration error');
        }
    }


    public function loadSystemBalance() {
        if ($this->accountId) {
            $account = Account::findOrFail($this->accountId);
            $this->systemBalance = $account->balance;

            Log::debug('Balance Check', [
                'Raw DB Value' => $account->getRawOriginal('balance'),
                'Model Value' => $account->balance,
                'PHP Type' => gettype($account->balance)
            ]);
            Log::debug('System balance loaded', ['balance' => $this->systemBalance]);
        }
    }


    public function loadExistingDenomination()
    {
        try {
            Log::debug('Loading existing denominations', [
                'branch' => $this->branchId,
                'account' => $this->accountId,
                'date' => $this->date
            ]);

            $existingRecord = DailyCashSummary::with('denominations')
                ->where('branch_id', $this->branchId)
                ->where('account_id', $this->accountId)
                ->where('date', $this->date)
                ->first();

            if ($existingRecord) {
                Log::debug('Found existing record', ['id' => $existingRecord->id]);

                foreach ($existingRecord->denominations as $denom) {
                    if ($denom->is_coin) {
                        $this->coins[$denom->value] = (float)$denom->count;
                    } else {
                        $this->notes[$denom->value] = (float)$denom->count;
                    }
                }

                $this->remarks = $existingRecord->remarks;
                $this->calculateTotal();
            }
        } catch (\Exception $e) {
            Log::error('Denomination load error', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to load existing counts');
        }
    }

    public function calculateTotal()
    {
        try {
            Log::debug('Calculating totals', [
                'notes' => $this->notes,
                'coins' => $this->coins
            ]);

            $noteTotal = 0;
            foreach ($this->notes as $denomination => $count) {
                $noteTotal += $denomination * (float)$count;
            }

            $coinTotal = 0;
            foreach ($this->coins as $denomination => $count) {
                $coinTotal += $denomination * (float)$count;
            }

            $this->totalAmount = $noteTotal + $coinTotal;
            $this->difference = $this->totalAmount - $this->systemBalance;

            Log::debug('Totals calculated', [
                'total' => $this->totalAmount,
                'system' => $this->systemBalance,
                'difference' => $this->difference
            ]);

        } catch (\Exception $e) {
            Log::error('Calculation error', ['error' => $e->getMessage()]);
            session()->flash('error', 'Calculation error: ' . $e->getMessage());
        }
    }

    public function saveCount()
    {
        try {
            $this->validate();
            Log::debug('Validation passed');

            if (!$this->branchId || !$this->accountId) {
                $msg = 'Missing branch or account configuration';
                Log::error($msg, ['branch' => $this->branchId, 'account' => $this->accountId]);
                throw new \Exception($msg);
            }

            Log::debug('Saving cash summary', [
                'branch' => $this->branchId,
                'account' => $this->accountId,
                'date' => $this->date
            ]);

            $cashSummary = DailyCashSummary::firstOrNew([
                'branch_id' => $this->branchId,
                'account_id' => $this->accountId,
                'date' => $this->date,
            ]);

            $cashSummary->fill([
                'counted_amount' => $this->totalAmount,
                'system_amount' => $this->systemBalance,
                'difference' => $this->difference,
                'remarks' => $this->remarks,
                'counted_by' => auth()->id()
            ])->save();

            Log::debug('Summary saved', ['id' => $cashSummary->id]);

            // Handle denominations
            if ($cashSummary->id) {
                Log::debug('Clearing old denominations');
                ModelsCashDenomination::where('daily_cash_summary_id', $cashSummary->id)->delete();

                Log::debug('Saving new denominations', [
                    'notes' => $this->notes,
                    'coins' => $this->coins
                ]);

                foreach ($this->notes as $value => $count) {
                    if ($count > 0) {
                        ModelsCashDenomination::create([
                            'daily_cash_summary_id' => $cashSummary->id,
                            'value' => $value,
                            'count' => $count,
                            'is_coin' => false,
                        ]);
                    }
                }

                foreach ($this->coins as $value => $count) {
                    if ($count > 0) {
                        ModelsCashDenomination::create([
                            'daily_cash_summary_id' => $cashSummary->id,
                            'value' => $value,
                            'count' => $count,
                            'is_coin' => true,
                        ]);
                    }
                }
            }

            session()->flash('message', 'Cash count saved successfully!');
            Log::info('Cash count saved', ['user' => auth()->id(), 'summary' => $cashSummary->id]);

        } catch (\Exception $e) {
            Log::error('Save error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Save failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.account.cash-denomination', [
            'account' => $this->account
        ]);
    }
}
