<?php

namespace App\Livewire\LoanDetails;

use App\Models\Account;
use App\Models\Collection;
use App\Models\CollectionInvoice;
use App\Models\LoanCollectionSchedule;
use App\Models\LoanProgress;
use App\Models\Transaction;
use Livewire\Component;
use DB;
use Exception;
use Log;

class Collections extends Component
{


    public $loanId;
    public $isEditing = false;
    public $editingCollection;
    public $collectionDate;
    public $collectedAmount = '';
    public $collectionMethod = '';

    public $notes;

    protected $listeners = [
        'refreshCollections' => '$refresh',
        'collectionUpdated' => '$refresh',
        'editCancelled' => '$refresh',
        'deleteCollection' => 'deleteCollection',
    ];



    public function deleteCollection($collectionId)
    {
        try {
            DB::transaction(function () use ($collectionId) {
                Log::info('Starting collection deletion process', ['collection_id' => $collectionId]);

                // Get collection details before deletion
                $collection = Collection::findOrFail($collectionId);
                $transactions = Transaction::where('collection_id', $collection->id)->get();
                foreach ($transactions as $transaction) {
                    $transaction->delete(); // Observer is triggered
                }


                // Delete related invoices first
                CollectionInvoice::where('collection_id', $collectionId)->delete();

                // Find and delete corresponding Payment Received+ entry
                $paymentEntry = LoanCollectionSchedule::where('loan_id', $collection->loan_id)
                    ->where('date', $collection->collection_date)
                    ->where('description', 'Payment Received +')
                    ->where('paid', $collection->collected_amount)
                    ->first();

                if ($paymentEntry) {
                    $paymentEntry->delete();
                }

                // Delete the collection record
                $collection->delete();

                // Recalculate schedule pending dues
                $this->recalculateScheduleAfterDeletion();

                // Update loan progress
                $this->updateLoanProgressAfterDeletion();

                Log::info('Collection and related records deleted successfully');
                session()->flash('success', 'Collection and related invoices deleted successfully');
            });
        } catch (Exception $e) {
            Log::error('Error deleting collection: ' . $e->getMessage());
            session()->flash('error', 'Error deleting collection: ' . $e->getMessage());
        }
    }





    public function mount($loanId)
    {
        $this->loanId = $loanId;
    }



    public function startEdit($collectionId)
    {
        $collection = Collection::find($collectionId);
        $this->dispatch('openEditForm', ['collection' => $collection]);
    }


    public function generateInvoice($collectionId, $type)
    {
        $invoice = CollectionInvoice::where('collection_id', $collectionId)
            ->where('type', $type)
            ->first();

        if (!$invoice) {
            // If no invoice exists, create one
            $collection = Collection::findOrFail($collectionId);
            $invoice = CollectionInvoice::create([
                'invoice_number' => 'INV-' . now()->year . str_pad(CollectionInvoice::count() + 1, 4, '0', STR_PAD_LEFT),
                'loan_id' => $collection->loan_id,
                'collection_id' => $collection->id,
                'collected_amount' => $collection->collected_amount,
                'type' => $type
            ]);
        }

        // Redirect to appropriate invoice method
        if ($type === 'pos') {
            return redirect()->route('invoice.print', ['id' => $invoice->id]);
        } elseif ($type === 'a4') {
            return redirect()->route('invoice.download', ['id' => $invoice->id]);
        }
    }

    // public function deleteCollection($collectionId)
    // {
    //     try {
    //         DB::transaction(function () use ($collectionId) {
    //             Log::info('Starting collection deletion process', ['collection_id' => $collectionId]);

    //             // Get collection details before deletion
    //             $collection = Collection::findOrFail($collectionId);

    //             // Delete related invoices first
    //             CollectionInvoice::where('collection_id', $collectionId)->delete();

    //             // Find and delete corresponding Payment Received+ entry
    //             $paymentEntry = LoanCollectionSchedule::where('loan_id', $this->loanId)
    //                 ->where('date', $collection->collection_date)
    //                 ->where('description', 'Payment Received +')
    //                 ->where('paid', $collection->collected_amount)
    //                 ->first();

    //             if ($paymentEntry) {
    //                 $paymentEntry->delete();
    //             }

    //             // Delete the collection record
    //             $collection->delete();

    //             // Recalculate schedule pending dues
    //             $this->recalculateScheduleAfterDeletion();

    //             // Update loan progress
    //             $this->updateLoanProgressAfterDeletion();

    //             Log::info('Collection and related records deleted successfully');
    //             session()->flash('success', 'Collection and related invoices deleted successfully');
    //         });
    //     } catch (\Exception $e) {
    //         Log::error('Error deleting collection: ' . $e->getMessage());
    //         session()->flash('error', 'Error deleting collection: ' . $e->getMessage());
    //     }
    // }



    private function recalculateScheduleAfterDeletion()
    {
        $allRecords = LoanCollectionSchedule::where('loan_id', $this->loanId)
            ->orderBy('date', 'asc')
            ->get();

        $runningBalance = 0;

        foreach ($allRecords as $record) {
            if ($record->description === 'Payment Received +') {
                $runningBalance -= $record->paid;
            } else {
                $runningBalance += $record->due;

                $record->pending_due = $runningBalance;

                if ($runningBalance <= 0) {
                    $record->status = 'paid';
                } elseif ($record->date < now() && $runningBalance > 0) {
                    $record->status = 'Arrears';
                } else {
                    $record->status = 'pending';
                }

                $record->save();
            }
        }
    }

    private function updateLoanProgressAfterDeletion()
    {
        $schedules = LoanCollectionSchedule::where('loan_id', $this->loanId)->get();

        $totalPaid = $schedules->where('description', 'Payment Received +')->sum('paid');
        $totalDue = $schedules->where('description', 'Repayment')->sum('due');

        $loanProgress = LoanProgress::where('loan_id', $this->loanId)->first();

        if ($loanProgress) {
            $loanProgress->total_paid_amount = $totalPaid;
            $loanProgress->balance = $loanProgress->total_amount - $totalPaid;

            if ($loanProgress->balance <= 0) {
                $loanProgress->status = 'Complete';
                $loanProgress->last_due_date = now();
            } else {
                $loanProgress->status = 'Active';
                $loanProgress->last_due_date = null;
            }

            $loanProgress->save();

            Log::info('Loan progress updated after deletion', [
                'loan_id' => $this->loanId,
                'total_paid' => $totalPaid,
                'balance' => $loanProgress->balance,
                'status' => $loanProgress->status
            ]);
        }
    }

    public function render()
    {
        $collections = Collection::where('loan_id', $this->loanId)
            ->orderBy('collection_date', 'asc')
            ->get();

        return view('livewire.loan-details.collections', [
            'collections' => $collections
        ]);
    }
}
