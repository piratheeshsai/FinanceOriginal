<?php

namespace App\Livewire\Account;

use Livewire\Component;

use App\Models\Account;
use App\Models\StaffCollectionStatus;
use App\Models\Transfer;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\WithPagination;

class CashTransfer extends Component
{
    public $from_account_id;
    public $to_account_id;
    public $amount;
    public $description;
    public $transfer_date;



    public $days = 10;

    use WithPagination;
    public $fromAccounts = [];
    public $toAccounts = [];


    protected $rules = [
        'from_account_id' => 'required|exists:accounts,id',
        'to_account_id' => 'required|exists:accounts,id|different:from_account_id',
        'amount' => 'required|numeric|min:0.01',
        'transfer_date' => 'required|date|before_or_equal:today',
        'description' => 'nullable|string|max:255',
    ];

    public function mount()
    {
        // $this->transfer_date = date('Y-m-d');
        $this->loadAccounts();
        // $this->loadPendingTransfers();
    }





    // protected function loadAccounts()
    // {
    //     $userBranch = Auth::user()->branch_id;
    //     $user = Auth::user();

    //     $fromAccountsCollection = collect();
    //     $toAccountsCollection = collect();

    //     $permissions = [
    //         'transfer from cash' => ['from' => ['cash'], 'to' => ['bank']],
    //         'transfer from branch bank' => ['from' => ['bank'], 'to' => ['cash']],
    //         'transfer from cash drawer' => ['from' => ['cash_drawer'], 'to' => ['cash']],
    //         'transfer from petty cash' => ['from' => ['petty_cash'], 'to' => ['cash']],
    //         'transfer from collection cash' => ['from' => ['collection_cash'], 'to' => ['cash']],
    //     ];

    //     if ($user->hasPermissionTo('view all branches')) {
    //         $this->fromAccounts = Account::where('branch_id', $userBranch)
    //             ->where('category', 'asset')
    //             ->whereIn('type', ['cash', 'bank', 'petty_cash', 'cash_drawer', 'collection_cash'])
    //             ->get();

    //         $this->toAccounts = Account::where('branch_id', $userBranch)
    //             ->where('category', 'asset')
    //             ->whereIn('type', ['cash', 'bank', 'petty_cash', 'cash_drawer'])
    //             ->get();
    //     } else {
    //         foreach ($permissions as $permission => $types) {
    //             if ($user->hasPermissionTo($permission)) {
    //                 $fromAccounts = Account::where('branch_id', $userBranch)
    //                     ->where('category', 'asset')
    //                     ->whereIn('type', $types['from'])
    //                     ->get();

    //                 $toAccounts = Account::where('branch_id', $userBranch)
    //                     ->where('category', 'asset')
    //                     ->whereIn('type', $types['to'])
    //                     ->get();

    //                 $fromAccountsCollection = $fromAccountsCollection->concat($fromAccounts);
    //                 $toAccountsCollection = $toAccountsCollection->concat($toAccounts);
    //             }
    //         }

    //         $this->fromAccounts = $fromAccountsCollection;
    //         $this->toAccounts = $toAccountsCollection;
    //     }
    // }

    protected function loadAccounts()
    {
        $userBranch = Auth::user()->branch_id;
        $user = Auth::user();

        $fromAccountsCollection = collect();
        $toAccountsCollection = collect();

        $permissions = [
            'transfer from cash' => ['from' => ['cash'], 'to' => ['bank']],
            'transfer from branch bank' => ['from' => ['bank'], 'to' => ['cash']],
            'transfer from cash drawer' => ['from' => ['cash_drawer'], 'to' => ['cash']],
            'transfer from petty cash' => ['from' => ['petty_cash'], 'to' => ['cash']],
            'transfer from collection cash' => ['from' => ['collection_cash'], 'to' => ['cash']],
        ];

        // Check if user has Companies Fund Transfer permission
        if ($user->hasPermissionTo('Companies Fund Transfer')) {
            // If user has this permission, show all asset accounts without branch restriction
            $this->fromAccounts = Account::where('category', 'asset')
                ->whereIn('type', ['cash', 'bank', 'petty_cash', 'cash_drawer', 'collection_cash'])
                ->get();

            $this->toAccounts = Account::where('category', 'asset')
                ->whereIn('type', ['cash', 'bank', 'petty_cash', 'cash_drawer'])
                ->get();
        } elseif ($user->hasPermissionTo('view all branches')) {
            $this->fromAccounts = Account::where('branch_id', $userBranch)
                ->where('category', 'asset')
                ->whereIn('type', ['cash', 'bank', 'petty_cash', 'cash_drawer', 'collection_cash'])
                ->get();

            $this->toAccounts = Account::where('branch_id', $userBranch)
                ->where('category', 'asset')
                ->whereIn('type', ['cash', 'bank', 'petty_cash', 'cash_drawer'])
                ->get();
        } else {
            foreach ($permissions as $permission => $types) {
                if ($user->hasPermissionTo($permission)) {
                    $fromAccounts = Account::where('branch_id', $userBranch)
                        ->where('category', 'asset')
                        ->whereIn('type', $types['from'])
                        ->get();

                    $toAccounts = Account::where('branch_id', $userBranch)
                        ->where('category', 'asset')
                        ->whereIn('type', $types['to'])
                        ->get();

                    $fromAccountsCollection = $fromAccountsCollection->concat($fromAccounts);
                    $toAccountsCollection = $toAccountsCollection->concat($toAccounts);
                }
            }

            $this->fromAccounts = $fromAccountsCollection;
            $this->toAccounts = $toAccountsCollection;
        }
    }

    public function submit()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Verify both accounts belong to the same branch
            $fromAccount = Account::findOrFail($this->from_account_id);
            $toAccount = Account::findOrFail($this->to_account_id);

            if ($fromAccount->branch_id !== $toAccount->branch_id) {
                DB::rollBack(); // Rollback changes if validation fails
                $this->addError('to_account_id', 'Accounts must belong to the same branch');
                return;
            }

            // Create the transfer record
            Transfer::create([
                'from_account_id' => $this->from_account_id,
                'to_account_id' => $this->to_account_id,
                'amount' => $this->amount,
                'description' => $this->description,
                'transfer_date' => $this->transfer_date,
                'status' => 'pending',
                'created_by' => Auth::id(),
                'branch_id' => Auth::user()->branch_id,
            ]);

            DB::commit(); // Commit transaction if everything is successful

            $this->resetForm();
            $this->dispatch('show-success-alert', message: 'Transfer submitted for approval.');
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback if there's an error
            $this->addError('general', 'An error occurred. Please try again.');
        }
    }


    private function resetForm()
    {
        $this->reset([
            'from_account_id',
            'to_account_id',
            'amount',
            'description',
            'transfer_date'
        ]);
        $this->transfer_date = date('Y-m-d');
        $this->loadPendingTransfers();
        $this->loadAccounts();
    }

    public function approveTransfer($transferId)
    {
        try {
            $transfer = Transfer::findOrFail($transferId);

            // Authorization check
            $user = Auth::user();

            $isCollectionTC = $transfer->fromAccount->type == 'collection_cash' &&
                $user->hasPermissionTo('Loan C.T approval') &&
                $transfer->branch_id == $user->branch_id;

            if (!$user->hasPermissionTo('Approve All Transfer') && !$isCollectionTC) {
                throw new \Exception('Unauthorized action');
            }


            DB::transaction(function () use ($transfer) {
                // Reload accounts with lock to prevent concurrent updates
                $fromAccount = Account::lockForUpdate()->find($transfer->from_account_id);
                $toAccount = Account::lockForUpdate()->find($transfer->to_account_id);

                // Validate balance
                if ($fromAccount->balance < $transfer->amount) {
                    throw new \Exception('Insufficient balance in source account');
                }




                // Create transaction record
                Transaction::create([
                    'branch_id' => $transfer->branch_id,
                    'debit_account_id' => $transfer->to_account_id,
                    'credit_account_id' => $transfer->from_account_id,
                    'amount' => $transfer->amount,
                    'description' => $transfer->description ?? "Transfer from {$fromAccount->account_name} to {$toAccount->account_name}",
                    'transaction_type' => 'fund_transfer',
                    'transaction_date' => now(),
                    'created_by' => Auth::id(),
                    'status' => 'approved'
                ]);

                // Update transfer status
                $transfer->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'approved_at' => now()
                ]);

                StaffCollectionStatus::where('transfers_id', $transfer->id)
                ->where('status', 'Waiting to Accept')
                ->update(['status' => 'Transferred']);
            });

            // Refresh pending transfers list
            // $this->loadPendingTransfers();

            // Show success notification
            $this->dispatch('show-success-alert', message: 'Transfer approved successfully');
        } catch (\Exception $e) {

            report($e);
            $this->dispatch('show-error-alert', message: $e->getMessage());
        }
    }

    public function rejectTransfer($transferId)
    {
        $transfer = Transfer::findOrFail($transferId);

        $transfer->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        StaffCollectionStatus::where('transfers_id', $transfer->id)
            ->where('status', 'Waiting to Accept')
            ->update(['status' => 'Pending']);


        $this->dispatch('show-success-alert', message: 'Transfer rejected successfully');
    }



    public function render()
{
    $latestTransfers = Transfer::query()
        ->with(['fromAccount', 'toAccount', 'createdBy'])
        ->latest() // Order by created_at DESC
        ->when(!Auth::user()->hasPermissionTo('view all branches'), function ($q) {
            $q->where('branch_id', Auth::user()->branch_id);
        })
        ->take(50) // Get only the last 50 records first
        ->get(); // Fetch the records

    // Now apply pagination to the retrieved 50 records
    $transfers = new \Illuminate\Pagination\LengthAwarePaginator(
        $latestTransfers->forPage(request()->get('page', 1), 10), // Paginate with 10 per page
        $latestTransfers->count(),
        10,
        request()->get('page', 1),
        ['path' => request()->url()]
    );

    return view('livewire.account.cash-transfer', [
        'transfers' => $transfers
    ]);
}

}
