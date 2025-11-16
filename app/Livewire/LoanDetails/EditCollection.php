<?php

namespace App\Livewire\LoanDetails;

use App\Models\Collection;
use App\Models\LoanCollectionSchedule;
use App\Models\LoanProgress;
use DB;
use Livewire\Component;
use Log;

class EditCollection extends Component
{

    public $isOpen = false;
    public $collection;
    public $loanId;
    public $collectionDate;
    public $collectedAmount = '';
    public $collectionMethod = '';
    public $notes;

    protected $listeners = ['openEditForm'];

    public function openEditForm($data)
    {
        $collection = Collection::find($data['collection']['id']);
        $this->collection = $collection;
        $this->loanId = $collection->loan_id;
        $this->collectionDate = $collection->collection_date;
        $this->collectedAmount = $collection->collected_amount;
        $this->collectionMethod = $collection->collection_method;
        $this->notes = $collection->notes;
        $this->isOpen = true;
    }

    public function save()
    {
        $this->validate([
            'collectionDate' => 'required|date',
            'collectedAmount' => 'required|numeric',
            'collectionMethod' => 'required'
        ]);

        try {
            DB::transaction(function () {
                // Store old values for updating schedule
                $oldAmount = $this->collection->collected_amount;
                $oldDate = $this->collection->collection_date;

                // Update collection record
                $this->collection->update([
                    'collection_date' => $this->collectionDate,
                    'collected_amount' => $this->collectedAmount,
                    'collection_method' => $this->collectionMethod,
                    'notes' => $this->notes
                ]);


                // Update corresponding Payment Received+ entry
                $this->updatePaymentSchedule($oldDate, $oldAmount);

                // Recalculate schedule pending dues
                $this->recalculateSchedule();

                // Update loan progress
                $this->updateLoanProgress();

                Log::info('Collection updated successfully', [
                    'collection_id' => $this->collection->id,
                    'old_amount' => $oldAmount,
                    'new_amount' => $this->collectedAmount
                ]);

                session()->flash('success', 'Collection updated successfully');
            });

            $this->isOpen = false;
            $this->resetFields();
            $this->dispatch('collectionUpdated');

        } catch (\Exception $e) {
            Log::error('Error updating collection: ' . $e->getMessage());
            session()->flash('error', 'Error updating collection: ' . $e->getMessage());
        }
    }




    private function updatePaymentSchedule($oldDate, $oldAmount)
    {
        // Find and update corresponding Payment Received+ entry
        $paymentEntry = LoanCollectionSchedule::where('loan_id', $this->loanId)
            ->where('date', $oldDate)
            ->where('description', 'Payment Received +')
            ->where('paid', $oldAmount)
            ->first();

        if ($paymentEntry) {
            $paymentEntry->update([
                'date' => $this->collectionDate,
                'paid' => $this->collectedAmount
            ]);
        } else {
            // Create new payment entry if not found
            LoanCollectionSchedule::create([
                'loan_id' => $this->loanId,
                'date' => $this->collectionDate,
                'description' => 'Payment Received +',
                'paid' => $this->collectedAmount,
                'due' => 0,
                'pending_due' => 0
            ]);
        }
    }

    private function recalculateSchedule()
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

    private function updateLoanProgress()
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

            Log::info('Loan progress updated after edit', [
                'loan_id' => $this->loanId,
                'total_paid' => $totalPaid,
                'balance' => $loanProgress->balance,
                'status' => $loanProgress->status
            ]);
        }
    }

    public function cancel()
    {
        $this->isOpen = false;
        $this->resetFields();
        $this->dispatch('editCancelled');
    }

    private function resetFields()
    {
        $this->collection = null;
        $this->collectionDate = null;
        $this->collectedAmount = '';
        $this->collectionMethod = '';
        $this->notes = '';
    }
    public function render()
    {
        return view('livewire.loan-details.edit-collection');
    }
}
