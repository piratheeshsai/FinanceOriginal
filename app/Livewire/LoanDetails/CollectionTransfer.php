<?php

namespace App\Livewire\LoanDetails;

use App\Models\Account;
use App\Models\StaffCollectionStatus;
use App\Models\Transfer;
use App\Models\User;
use DB;
use Exception;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Log;

class CollectionTransfer extends Component
{
    public $selectedStaff;
    public $totalCollectedAmount = 0;
    public $Remark;
    public $staffList;
    public $toField = 'Cashier Account';
    public $selectedCollector = null;


    protected $rules = [
        'selectedCollector' => 'required', // Use selectedCollector
        'totalCollectedAmount' => 'required|numeric|min:0',
        'Remark' => 'nullable|string',
    ];


    public function mount()
    {
        $user = Auth::user();

        if ($user->hasPermissionTo('view all branches')) {
            $this->staffList = StaffCollectionStatus::where('status', 'Pending')
                ->whereHas('collection', function ($query) {
                    $query->whereNotNull('collector_id');
                })
                ->with('collection.collector')
                ->get()
                ->mapWithKeys(function ($status) {
                    return [$status->collection->collector_id => $status->collection->collector->name];
                })
                ->unique();
        } elseif ($user->hasPermissionTo('view own branch all data')) {
            $this->staffList = StaffCollectionStatus::where('status', 'Pending')
                ->whereHas('collection', function ($query) use ($user) {
                    $query->whereHas('loan.center', function ($subQuery) use ($user) {
                        $subQuery->where('branch_id', $user->branch_id);
                    });
                })
                ->with('collection.collector')
                ->get()
                ->mapWithKeys(function ($status) {
                    return [$status->collection->collector_id => $status->collection->collector->name];
                })
                ->unique();
        } else {
            $this->staffList = StaffCollectionStatus::where('status', 'Pending')
                ->whereHas('collection', function ($query) use ($user) {
                    $query->where('collector_id', $user->id);
                })
                ->with('collection.collector')
                ->get()
                ->mapWithKeys(function ($status) {
                    return [$status->collection->collector_id => $status->collection->collector->name];
                })
                ->unique();
        }


        // Set the default selected collector (optional)
        $staffArray = $this->staffList->toArray();

        if (!empty($staffArray)) {
            $this->selectedCollector = array_key_first($staffArray);
            $this->updateCollectedAmount();
        }
    }
    public function updateCollectedAmount()
    {
        if (!$this->selectedCollector) {
            $this->totalCollectedAmount = 0;
            return;
        }

        $this->totalCollectedAmount = StaffCollectionStatus::where('status', 'Pending')
            ->whereHas('collection', function ($query) {
                $query->where('collector_id', $this->selectedCollector);
            })
            ->with('collection')
            ->get()
            ->sum(function ($status) {
                return $status->collection->collected_amount ?? 0;
            });
    }



    public function transferToCashier()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Debug: Log selected collector and total collected amount
            Log::info('Selected Collector:', ['collector_id' => $this->selectedCollector]);
            Log::info('Total Collected Amount:', ['amount' => $this->totalCollectedAmount]);

            // Fetch the selected collector
            $collector = User::find($this->selectedCollector);
            if (!$collector) {
                throw new Exception('Collector not found.');
            }

            // Debug: Log collector details
            Log::info('Collector Details:', ['collector' => $collector]);

            // Get the branch of the selected collector
            $branchId = $collector->branch_id;

            // Debug: Log branch ID
            Log::info('Branch ID:', ['branch_id' => $branchId]);

            // Fetch the cashier account based on the collector's branch
            $cashierAccount = Account::where('branch_id', $branchId)
                ->where('type', 'cash')
                ->firstOrFail();

            $collectionAccount = Account::where('branch_id', $branchId)
                ->where('type', 'collection_cash')
                ->firstOrFail();


            Log::info('Cashier Account:', ['cashier_account' => $cashierAccount]);

            // Create transfer record for principal amount
            $transfer = Transfer::create([
                'branch_id' => $branchId,
                'from_account_id' => $collectionAccount->id, // No collection account, so set to null
                'to_account_id' => $cashierAccount->id,
                'amount' => $this->totalCollectedAmount,
                'description' => 'collected amount Transfer to cashier',
                'status' => 'pending',
                'created_by' => auth()->id(),
                'collector_id' => $this->selectedCollector,
            ]);


            // Debug: Log transfer details
            Log::info('Transfer Created:', ['transfer' => $transfer]);


            StaffCollectionStatus::where('status', 'Pending')
                ->whereHas('collection', function ($query) {
                    $query->where('collector_id', $this->selectedCollector);
                })
                ->update([
                    'status' => 'Waiting to Accept',
                    'transfers_id' => $transfer->id
                ]);

            DB::commit();

            // Reset form fields
            $this->reset(['Remark', 'totalCollectedAmount']);

            // Show success message
            session()->flash('message', 'Collections successfully transferred to cashier.');
        } catch (Exception $e) {
            DB::rollback();
            Log::error('Transfer to Cashier Error: ' . $e->getMessage());
            session()->flash('error', 'Failed to transfer collections. Please try again.');
        }
    }
    public function render()
    {


        return view(
            'livewire.loan-details.collection-transfer'

        );
    }
}
