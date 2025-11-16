<?php

namespace App\Livewire\LoanDetails;

use App\Models\Account;
use App\Models\Collection;
use App\Models\Loan;
use App\Models\LoanCollectionSchedule;
use App\Models\LoanProgress;
use App\Models\StaffCollectionStatus;
use App\Models\Transaction;
use App\Services\LoanCollectionService;
use App\Services\SMSService;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;
use Log;

class CollectDue extends Component
{
    public $loanId;
    public $repaymentAmount;
    public $repaymentMethod;
    public $collectionDate;
    public $collectedBy;
    public $description;

    protected $rules = [
        'repaymentAmount' => 'required|numeric|min:1',
        'repaymentMethod' => 'required|string',
        'collectionDate' => 'required|date',
        'description' => 'nullable|string',
    ];


    private $collectionService;


    public function boot(LoanCollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }


    public function mount($loanId)
    {
        $this->loanId = $loanId;
        $this->collectedBy = auth()->user()->name;
        $this->collectionDate = now()->format('Y-m-d');
    }

    #[On('confirmQuickSubmit')]
    public function submit()
    {
        Log::info('Validation started', ['repaymentAmount' => $this->repaymentAmount, 'repaymentMethod' => $this->repaymentMethod]);
        $this->validate();
        Log::info('Validation passed');

        try {
            $result = $this->collectionService->processRepayment(
                $this->loanId,
                $this->repaymentAmount,
                $this->repaymentMethod,
                $this->collectionDate,
                $this->description
            );

            // Reset form fields
            $this->reset(['repaymentAmount', 'repaymentMethod', 'description']);

            // Show success message
            $this->dispatch('show-success-alert', message: 'Payment recorded successfully and SMS sent!');
        } catch (Exception $e) {
            Log::error('Transaction error: ' . $e->getMessage());
            $this->dispatch('show-error-alert',
                title: 'Payment Error',
                message: $e->getMessage(),
                icon: 'error'
            );
            return;
        }
    }

    public function render()
    {
        $schedules = LoanCollectionSchedule::where('loan_id', $this->loanId)
            ->orderBy('date', 'asc')
            ->get();

        return view('livewire.loan-details.collect-due', [
            'schedules' => $schedules,
            'totalPaid' => $schedules->where('description', 'Payment Received +')->sum('paid'),
            'totalDue' => $schedules->where('description', 'Repayment')->sum('due')
        ]);
    }







    // private $smsService;

    // public function boot(SMSService $smsService)
    // {
    //     $this->smsService = $smsService;
    // }

    // public function mount($loanId)
    // {
    //     $this->loanId = $loanId;
    //     $this->collectedBy = auth()->user()->name;
    //     $this->collectionDate = now()->format('Y-m-d');
    // }

    // public function submit()
    // {
    //     Log::info('Validation started', ['repaymentAmount' => $this->repaymentAmount, 'repaymentMethod' => $this->repaymentMethod]);
    //     $this->validate();
    //     Log::info('Validation passed');
    //     $collectionId = null;

    //     $repaymentAmount = $this->repaymentAmount;
    //     try {
    //         DB::transaction(function () use (&$collectionId) {
    //             Log::info('Transaction started');

    //             // Fetch the loan and related accounts
    //             $loan = Loan::findOrFail($this->loanId);
    //             $accounts = $this->getRequiredAccounts($loan->center->branch_id);

    //             // Get all pending installments
    //             $pendingInstallments = LoanCollectionSchedule::where('loan_id', $this->loanId)
    //                 ->where('status', 'pending')
    //                 ->orderBy('date', 'asc')
    //                 ->get();

    //             if ($pendingInstallments->isEmpty()) {
    //                 throw new Exception('No pending installments found for this loan.');
    //             }

    //             // Calculate total pending amount to validate repayment amount
    //             $totalPendingAmount = $pendingInstallments->sum('due') - $pendingInstallments->sum('paid');
    //             if ($this->repaymentAmount > $totalPendingAmount) {
    //                 // Optional: Cap the repayment amount or throw an exception
    //                 $this->repaymentAmount = $totalPendingAmount;
    //                 // Or
    //                 throw new Exception('Repayment amount exceeds total pending amount.');
    //             }

    //             // Allocate repayment amount to principal and interest
    //             $allocationResult = $this->allocateRepayment($pendingInstallments, $this->repaymentAmount);

    //             // Create collection record
    //             $collection = $this->createCollectionRecord($allocationResult);
    //             $collectionId = $collection->id;

    //             // Create payment record
    //             $this->createPaymentRecord($allocationResult);

    //             // Create transactions
    //             $this->createTransactions($loan, $accounts, $collection, $allocationResult);

    //             // Send SMS notification
    //             // $this->sendPaymentNotification($loan);

    //             // Reset form fields
    //             $this->reset(['repaymentAmount', 'repaymentMethod', 'description']);

    //             // Update schedule records and loan progress
    //             $this->updateSchedulePendingDues();
    //             $this->updateLoanProgress();

    //             Log::info('Transaction completed');
    //         });

    //         if ($collectionId) {
    //             $collection = Collection::with('loan.customer')->find($collectionId);
    //             if ($collection && $collection->loan) {
    //                 // Pass the original repayment amount
    //                 $this->sendSMSWithCollectedAmount($collection->loan, $repaymentAmount);
    //             }
    //         }
    //         $this->dispatch('show-success-alert', message: 'Payment recorded successfully and SMS sent!');
    //     } catch (Exception $e) {
    //         Log::error('Transaction error: ' . $e->getMessage());
    //         $this->dispatch('show-error-alert',
    //         title: 'Payment Error',
    //         message: $e->getMessage(),
    //         icon: 'error'
    //     );
    //     return;
    //     }
    // }

    // private function getRequiredAccounts($branchId)
    // {
    //     $accountTypes = ['loan_receivable', 'cash', 'interest_income', 'collection_cash'];
    //     $accounts = [];

    //     foreach ($accountTypes as $type) {
    //         $accounts[$type] = Account::where('branch_id', $branchId)
    //             ->where('type', $type)
    //             ->firstOrFail();
    //     }

    //     return $accounts;
    // }

    // private function allocateRepayment($pendingInstallments, $amount)
    // {
    //     $remainingAmount = $amount;
    //     $principalAmount = 0;
    //     $interestAmount = 0;
    //     $allocations = [];

    //     foreach ($pendingInstallments as $installment) {
    //         if ($remainingAmount <= 0) break;

    //         $pendingAmount = $installment->due - $installment->paid;
    //         if ($pendingAmount <= 0) continue;

    //         $allocatedAmount = min($remainingAmount, $pendingAmount);

    //         // Calculate proportional allocation to principal and interest
    //         $principalRatio = $installment->principal / $installment->due;
    //         $principalAllocation = round($allocatedAmount * $principalRatio, 2);
    //         $interestAllocation = $allocatedAmount - $principalAllocation;

    //         $principalAmount += $principalAllocation;
    //         $interestAmount += $interestAllocation;

    //         $allocations[] = [
    //             'installment' => $installment,
    //             'allocated' => $allocatedAmount,
    //             'principal' => $principalAllocation,
    //             'interest' => $interestAllocation
    //         ];

    //         $remainingAmount -= $allocatedAmount;
    //     }

    //     return [
    //         'total' => $amount,
    //         'principal' => $principalAmount,
    //         'interest' => $interestAmount,
    //         'allocations' => $allocations
    //     ];
    // }

    // private function createCollectionRecord($allocationResult)
    // {
    //     return Collection::create([
    //         'loan_id' => $this->loanId,
    //         'collector_id' => auth()->id(),
    //         'collected_amount' => $allocationResult['total'],
    //         'collection_date' => $this->collectionDate,
    //         'collection_method' => $this->repaymentMethod,
    //         'notes' => $this->description,
    //         'principal_amount' => $allocationResult['principal'],
    //         'interest_amount' => $allocationResult['interest'],
    //     ]);
    // }

    // private function createPaymentRecord($allocationResult)
    // {
    //     // Using first allocation for simplicity - could be split into multiple records if needed
    //     $firstAllocation = $allocationResult['allocations'][0] ?? null;

    //     if ($firstAllocation) {
    //         return LoanCollectionSchedule::create([
    //             'loan_id' => $this->loanId,
    //             'date' => Carbon::parse($this->collectionDate),
    //             'description' => 'Payment Received +',
    //             'principal' => $allocationResult['principal'],
    //             'interest' => $allocationResult['interest'],
    //             'penalty' => 0,
    //             'due' => 0,
    //             'paid' => $allocationResult['total'],
    //             'pending_due' => null,
    //             'total_due' => 0,
    //             'principal_due' => null,
    //             'status' => 'paid',
    //         ]);
    //     }

    //     return null;
    // }

    // private function createTransactions($loan, $accounts, $collection, $allocationResult)
    // {
    //     $user = Auth::user();
    //     $hasPermission = $user && $user->hasPermissionTo('Collection Account Access');

    //     // Determine which accounts to use based on permission
    //     $sourceAccount = $hasPermission ? $accounts['cash'] : $accounts['collection_cash'];

    //     // Create principal transaction
    //     Transaction::create([
    //         'debit_account_id' => $sourceAccount->id,
    //         'credit_account_id' => $accounts['loan_receivable']->id,
    //         'amount' => $allocationResult['principal'],
    //         'transaction_type' => 'principal collected',
    //         'branch_id' => $loan->center->branch_id,
    //         'loan_id' => $loan->id,
    //         'collection_id' => $collection->id,
    //         'description' => 'Principal repayment for Loan ' . $loan->loan_number,
    //         'status' => 'completed',
    //         'transaction_date' => $this->collectionDate,
    //         'created_by' => auth()->id(),
    //     ]);

    //     // Create interest transaction
    //     Transaction::create([
    //         'debit_account_id' => $sourceAccount->id,
    //         'credit_account_id' => $accounts['interest_income']->id,
    //         'amount' => $allocationResult['interest'],
    //         'transaction_type' => 'interest collected',
    //         'branch_id' => $loan->center->branch_id,
    //         'loan_id' => $loan->id,
    //         'collection_id' => $collection->id,
    //         'description' => 'Interest repayment for Loan ' . $loan->loan_number,
    //         'status' => 'completed',
    //         'transaction_date' => $this->collectionDate,
    //         'created_by' => auth()->id(),
    //     ]);

    //     // Create staff collection status record if no permission
    //     if (!$hasPermission) {
    //         StaffCollectionStatus::create([
    //             'collection_id' => $collection->id,
    //             'status' => 'Pending',
    //         ]);
    //     }
    // }

    // private function sendSMSWithCollectedAmount($loan, $amount)
    // {
    //     $loanProgress = LoanProgress::where('loan_id', $this->loanId)->first();

    //     if ($loan->customer && $loan->customer->customer_phone && $loanProgress) {
    //         $this->smsService->sendPaymentConfirmation(
    //             $loan->customer->customer_phone,
    //             $loan->customer->full_name,
    //             $amount, // Use the passed amount instead of $this->repaymentAmount
    //             $loan->loan_number,
    //             $loanProgress->balance,
    //             $this->collectionDate
    //         );
    //     }
    // }

    // private function updateSchedulePendingDues()
    // {
    //     // Get all records in chronological order
    //     $allRecords = LoanCollectionSchedule::where('loan_id', $this->loanId)
    //         ->orderBy('date', 'asc')
    //         ->get();

    //     $runningBalance = 0;

    //     foreach ($allRecords as $record) {
    //         if ($record->description === 'Payment Received +') {
    //             // Subtract payment amount from running balance
    //             $runningBalance -= $record->paid;
    //         } else {
    //             // Add installment amount to running balance
    //             $runningBalance += $record->due;

    //             // Update pending_due for repayment records only
    //             $record->pending_due = $runningBalance;

    //             // Update status based on running balance and date
    //             if ($runningBalance <= 0) {
    //                 $record->status = 'paid';
    //             } elseif ($record->date < now() && $runningBalance > 0) {
    //                 $record->status = 'Arrears';
    //             } else {
    //                 $record->status = 'pending';
    //             }

    //             // Save the total_due
    //             $record->total_due = $record->due;
    //             $record->save();
    //         }
    //     }

    //     // Update loan progress if fully paid
    //     if ($runningBalance <= 0) {
    //         $loanProgress = LoanProgress::where('loan_id', $this->loanId)->first();
    //         if ($loanProgress) {
    //             $loanProgress->status = 'completed';
    //             $loanProgress->save();
    //         }
    //     }
    // }

    // private function updateLoanProgress()
    // {
    //     $schedules = LoanCollectionSchedule::where('loan_id', $this->loanId)->get();

    //     $totalPaid = $schedules->where('description', 'Payment Received +')->sum('paid');
    //     $totalDue = $schedules->where('description', 'Repayment')->sum('due');

    //     $loanProgress = LoanProgress::where('loan_id', $this->loanId)->first();

    //     if ($loanProgress) {
    //         $loanProgress->total_paid_amount = $totalPaid;
    //         $loanProgress->balance = $loanProgress->total_amount - $totalPaid;

    //         // Update status based on balance
    //         if ($loanProgress->balance <= 0) {
    //             $loanProgress->status = 'Complete';
    //         } else {
    //             $loanProgress->status = 'Active';
    //         }

    //         // Update last due date if payment is complete
    //         if ($loanProgress->status === 'Complete') {
    //             $loanProgress->last_due_date = Carbon::parse($this->collectionDate);
    //         }

    //         $loanProgress->save();

    //         Log::info('Loan progress updated', [
    //             'loan_id' => $this->loanId,
    //             'total_paid' => $totalPaid,
    //             'balance' => $loanProgress->balance,
    //             'status' => $loanProgress->status
    //         ]);
    //     }
    // }

    // public function render()
    // {
    //     $schedules = LoanCollectionSchedule::where('loan_id', $this->loanId)
    //         ->orderBy('date', 'asc')
    //         ->get();

    //     return view('livewire.loan-details.collect-due', [
    //         'schedules' => $schedules,
    //         'totalPaid' => $schedules->where('description', 'Payment Received +')->sum('paid'),
    //         'totalDue' => $schedules->where('description', 'Repayment')->sum('due')
    //     ]);
    // }
}
