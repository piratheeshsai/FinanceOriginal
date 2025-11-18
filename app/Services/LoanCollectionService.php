<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Collection;
use App\Models\Loan;
use App\Models\LoanApproval;
use App\Models\LoanCollectionSchedule;
use App\Models\LoanProgress;
use App\Models\StaffCollectionStatus;
use App\Models\Transaction;
use DB;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoanCollectionService
{

    private $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    public function processRepayment($loanId, $repaymentAmount, $repaymentMethod, $collectionDate, $description = null)
    {

        Log::info('Repayment processing started', [
            'loanId' => $loanId,
            'amount' => $repaymentAmount,
            'method' => $repaymentMethod
        ]);

        $collectionId = null;

        try {
            $result = DB::transaction(function () use ($loanId, $repaymentAmount, $repaymentMethod, $collectionDate, $description, &$collectionId) {
                Log::info('Transaction started');

                // Fetch the loan and related accounts
                $loan = Loan::findOrFail($loanId);
                $accounts = $this->getRequiredAccounts($loan->center->branch_id);

                // Get all unpaid installments (any status with pending due)
                $pendingInstallments = LoanCollectionSchedule::where('loan_id', $loanId)
                    ->where('pending_due', '>', 0)
                    ->where('description', '!=', 'Payment Received +') // Exclude payment records
                    ->orderBy('date', 'asc')
                    ->get();

                if ($pendingInstallments->isEmpty()) {
                    throw new Exception('No pending installments found for this loan.');
                }

                // Calculate total pending amount to validate repayment amount
                // Use rounded totals and compare in cents to avoid floating point issues.
                $totalPendingAmount = round($pendingInstallments->sum('due') - $pendingInstallments->sum('paid'), 2);
                $repaymentAmount = round($repaymentAmount, 2);

                // Convert to cents (integers) for strict comparison
                $pendingCents = (int) round($totalPendingAmount * 100);
                $repaymentCents = (int) round($repaymentAmount * 100);

                // Allow a small tolerance (in cents) for minor overpayments (adjustable)
                $allowedOverpayCents = 200; // allow up to 10 cents tolerance

                if ($repaymentCents > $pendingCents + $allowedOverpayCents) {
                    throw new Exception('Repayment amount exceeds total pending amount.');
                }

                // If repayment is slightly higher (within allowed tolerance) clamp it to the pending amount
                if ($repaymentCents > $pendingCents) {
                    $repaymentAmount = $totalPendingAmount;
                }

                // Allocate repayment amount to principal and interest
                $allocationResult = $this->allocateRepayment($pendingInstallments, $repaymentAmount);

                // Create collection record
                $collection = $this->createCollectionRecord($loanId, $allocationResult, $collectionDate, $repaymentMethod, $description);
                $collectionId = $collection->id;

                // Create payment record
                $this->createPaymentRecord($loanId, $allocationResult, $collectionDate);

                // Create transactions
                $this->createTransactions($loan, $accounts, $collection, $allocationResult, $collectionDate);

                // Update schedule records and loan progress
                $this->updateSchedulePendingDues($loanId);
                $this->updateLoanProgress($loanId, $collectionDate);

                Log::info('Transaction completed');

                return [
                    'status' => 'success',
                    'collection_id' => $collection->id,
                ];
            });

            // Add a small delay to ensure transaction is committed
            usleep(100000); // 0.1 second delay

            // Send SMS outside of transaction to prevent rollback if SMS fails
            if ($collectionId) {
                // Refresh the loan progress to get the latest data
                $collection = Collection::with(['loan.customer', 'loan.loanProgress'])->find($collectionId);
                if ($collection && $collection->loan) {
                    $this->sendSMSWithCollectedAmount($collection->loan, $repaymentAmount, $collectionDate);
                }
            }

            return [
                'status' => 'success',
                'message' => 'Payment recorded successfully and SMS sent!',
                'collection_id' => $collectionId
            ];
        } catch (Exception $e) {
            Log::error('Transaction error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getRequiredAccounts($branchId)
    {
        $accountTypes = ['loan_receivable', 'cash', 'interest_income', 'collection_cash'];
        $accounts = [];

        foreach ($accountTypes as $type) {
            $accounts[$type] = Account::where('branch_id', $branchId)
                ->where('type', $type)
                ->firstOrFail();
        }

        return $accounts;
    }

    private function allocateRepayment($pendingInstallments, $amount)
    {
        $remainingAmount = $amount;
        $principalAmount = 0;
        $interestAmount = 0;
        $allocations = [];

        foreach ($pendingInstallments as $installment) {
            if ($remainingAmount <= 0) break;

            $pendingAmount = $installment->due - $installment->paid;
            if ($pendingAmount <= 0) continue;

            $allocatedAmount = min($remainingAmount, $pendingAmount);

            // Calculate proportional allocation to principal and interest
            $principalRatio = $installment->principal / $installment->due;
            $principalAllocation = round($allocatedAmount * $principalRatio, 2);
            $interestAllocation = $allocatedAmount - $principalAllocation;

            $principalAmount += $principalAllocation;
            $interestAmount += $interestAllocation;

            $allocations[] = [
                'installment' => $installment,
                'allocated' => $allocatedAmount,
                'principal' => $principalAllocation,
                'interest' => $interestAllocation
            ];

            $remainingAmount -= $allocatedAmount;
        }

        return [
            'total' => $amount,
            'principal' => $principalAmount,
            'interest' => $interestAmount,
            'allocations' => $allocations
        ];
    }

    private function createCollectionRecord($loanId, $allocationResult, $collectionDate, $repaymentMethod, $description)
    {
        return Collection::create([
            'loan_id' => $loanId,
            'collector_id' => auth()->id(),
            'collected_amount' => $allocationResult['total'],
            'collection_date' => $collectionDate,
            'collection_method' => $repaymentMethod,
            'notes' => $description,
            'principal_amount' => $allocationResult['principal'],
            'interest_amount' => $allocationResult['interest'],
        ]);
    }

    private function createPaymentRecord($loanId, $allocationResult, $collectionDate)
    {
        // Using first allocation for simplicity - could be split into multiple records if needed
        $firstAllocation = $allocationResult['allocations'][0] ?? null;

        if ($firstAllocation) {
            return LoanCollectionSchedule::create([
                'loan_id' => $loanId,
                'date' => Carbon::parse($collectionDate),
                'description' => 'Payment Received +',
                'principal' => $allocationResult['principal'],
                'interest' => $allocationResult['interest'],
                'penalty' => 0,
                'due' => 0,
                'paid' => $allocationResult['total'],
                'pending_due' => 0, // Set to 0 instead of null
                'total_due' => 0,
                'principal_due' => null,
                'status' => 'paid',
            ]);
        }

        return null;
    }

    private function createTransactions($loan, $accounts, $collection, $allocationResult, $collectionDate)
    {
        $user = Auth::user();
        $hasPermission = $user && $user->hasPermissionTo('Collection Account Access');

        $sourceAccount = $hasPermission
            ? Account::where('type', 'cash')->where('branch_id', $user->branch_id)->first()
            : Account::where('type', 'collection_cash')->where('branch_id', $user->branch_id)->first();

        // Create principal transaction
        Transaction::create([
            'debit_account_id' => $sourceAccount->id,
            'credit_account_id' => $accounts['loan_receivable']->id,
            'amount' => $allocationResult['principal'],
            'transaction_type' => 'principal collected',
            'branch_id' => $loan->center->branch_id,
            'loan_id' => $loan->id,
            'collection_id' => $collection->id,
            'description' => 'Principal repayment for Loan ' . $loan->loan_number,
            'status' => 'completed',
            'transaction_date' => $collectionDate,
            'created_by' => auth()->id(),
        ]);

        // Create interest transaction
        Transaction::create([
            'debit_account_id' => $sourceAccount->id,
            'credit_account_id' => $accounts['interest_income']->id,
            'amount' => $allocationResult['interest'],
            'transaction_type' => 'interest collected',
            'branch_id' => $loan->center->branch_id,
            'loan_id' => $loan->id,
            'collection_id' => $collection->id,
            'description' => 'Interest repayment for Loan ' . $loan->loan_number,
            'status' => 'completed',
            'transaction_date' => $collectionDate,
            'created_by' => auth()->id(),
        ]);

        // Create staff collection status record if no permission
        if (!$hasPermission) {
            StaffCollectionStatus::create([
                'collection_id' => $collection->id,
                'status' => 'Pending',
            ]);
        }
    }

    public function sendSMSWithCollectedAmount($loan, $amount, $collectionDate)
    {
        try {
            // Get the latest loan progress data
            $loanProgress = LoanProgress::where('loan_id', $loan->id)->first();

            if ($loan->customer && $loan->customer->customer_phone && $loanProgress) {
                Log::info('Sending SMS', [
                    'loan_id' => $loan->id,
                    'amount' => $amount,
                    'balance' => $loanProgress->balance,
                    'phone' => $loan->customer->customer_phone
                ]);

                $result = $this->smsService->sendPaymentConfirmation(
                    $loan->customer->customer_phone,
                    $loan->customer->full_name,
                    $amount,
                    $loan->loan_number,
                    $loanProgress->balance,
                    $collectionDate
                );

                if ($result) {
                    Log::info('SMS sent successfully for loan: ' . $loan->id);
                } else {
                    Log::warning('SMS sending failed for loan: ' . $loan->id);
                }

                return $result;
            } else {
                Log::warning('SMS not sent - missing data', [
                    'has_customer' => !is_null($loan->customer),
                    'has_phone' => $loan->customer ? !is_null($loan->customer->customer_phone) : false,
                    'has_progress' => !is_null($loanProgress)
                ]);
            }
        } catch (Exception $e) {
            Log::error('SMS sending error: ' . $e->getMessage(), [
                'loan_id' => $loan->id
            ]);
        }

        return false;
    }

    /**
     * Get the correct completed status value for loan progress
     * Based on ENUM('Active', 'complete')
     */
    private function getCompletedStatusValue()
    {
        return 'complete'; // Your ENUM allows 'complete'
    }

    private function updateSchedulePendingDues($loanId)
    {
        // Strict validation of input
        if (!is_numeric($loanId) || $loanId <= 0) {
            throw new Exception('Invalid loan ID provided for schedule update');
        }

        // Get all records in chronological order
        $allRecords = LoanCollectionSchedule::where('loan_id', $loanId)
            ->orderBy('date', 'asc')
            ->get();

        if ($allRecords->isEmpty()) {
            throw new Exception("No loan collection schedule records found for loan {$loanId}");
        }

        // Calculate total payments
        $totalPaid = 0;
        foreach ($allRecords as $record) {
            if ($record->description === 'Payment Received +') {
                $totalPaid += $record->paid;
            }
        }

        // Process all records to update pending dues cumulatively
        $runningBalance = 0;
        $remainingPayment = $totalPaid;

        foreach ($allRecords as $record) {
            if ($record->description === 'Payment Received +') {
                // Payment records have no pending due
                $record->pending_due = 0;
                $record->save();
            } else {
                // Add installment amount to running balance
                $runningBalance += $record->due;

                // Calculate pending due as the total running balance minus payments made
                if ($remainingPayment >= $runningBalance) {
                    // All installments up to this point are fully paid
                    $record->pending_due = 0;
                    $record->status = 'paid';
                } else {
                    // Calculate the cumulative pending due
                    $record->pending_due = $runningBalance - $remainingPayment;

                    // Update status based on date and payment status
                    if ($record->date < now() && $record->pending_due > 0) {
                        $record->status = 'Arrears';
                    } else {
                        $record->status = 'pending';
                    }
                }

                // Save the total_due
                $record->total_due = $record->due;
                $record->save();
            }
        }

        // Calculate total due and total paid to determine if loan is fully paid
        $totalDue = 0;
        $totalPaidFinal = 0;
        foreach ($allRecords as $record) {
            if ($record->description !== 'Payment Received +') {
                $totalDue += $record->due;
            } else {
                $totalPaidFinal += $record->paid;
            }
        }

        Log::info('Schedule update completed', [
            'loan_id' => $loanId,
            'total_due' => $totalDue,
            'total_paid' => $totalPaidFinal,
            'fully_paid' => $totalPaidFinal >= $totalDue
        ]);

        // STRICT: Update loan progress AND loan approval if fully paid
        if ($totalPaidFinal >= $totalDue) {
            // Update LoanProgress
            $loanProgress = LoanProgress::where('loan_id', $loanId)->first();
            if (!$loanProgress) {
                throw new Exception("LoanProgress record not found for loan {$loanId}");
            }

            $loanProgress->status = $this->getCompletedStatusValue();
            if (!$loanProgress->save()) {
                throw new Exception("Failed to update LoanProgress status for loan {$loanId}");
            }

            Log::info('Loan marked as completed in schedule update', ['loan_id' => $loanId]);

            // CRITICAL: Update LoanApproval status to 'Completed' - THIS IS MANDATORY
            $loanApproval = LoanApproval::where('loan_id', $loanId)->first();
            if (!$loanApproval) {
                throw new Exception("CRITICAL: LoanApproval record not found for loan {$loanId}");
            }

            // Strict validation of current status before updating
            if (!in_array($loanApproval->status, ['Approved', 'Active'])) {
                throw new Exception("LoanApproval cannot be completed from current status: {$loanApproval->status}");
            }

            $loanApproval->status = 'Completed';
            $loanApproval->active_at = $loanApproval->active_at ?? Carbon::now();

            if (!$loanApproval->save()) {
                throw new Exception("CRITICAL: Failed to update LoanApproval status to Completed for loan {$loanId}");
            }

            // Verify the update was successful
            $verifyApproval = LoanApproval::where('loan_id', $loanId)
                ->where('status', 'Completed')
                ->first();

            if (!$verifyApproval) {
                throw new Exception("CRITICAL: LoanApproval status verification failed for loan {$loanId}");
            }

            Log::info('LoanApproval marked as Completed in schedule update', [
                'loan_id' => $loanId,
                'approval_id' => $loanApproval->id,
                'previous_status' => $loanApproval->getOriginal('status'),
                'new_status' => 'Completed'
            ]);
        }
    }

    private function updateLoanProgress($loanId, $collectionDate)
    {
        // Strict validation of input
        if (!is_numeric($loanId) || $loanId <= 0) {
            throw new Exception('Invalid loan ID provided for loan progress update');
        }

        $schedules = LoanCollectionSchedule::where('loan_id', $loanId)->get();

        if ($schedules->isEmpty()) {
            throw new Exception("No schedule records found for loan {$loanId}");
        }

        $totalPaid = $schedules->where('description', 'Payment Received +')->sum('paid');
        $totalDue = $schedules->where('description', '!=', 'Payment Received +')->sum('due');

        $loanProgress = LoanProgress::where('loan_id', $loanId)->first();

        if (!$loanProgress) {
            throw new Exception("LoanProgress record not found for loan {$loanId}");
        }

        $loanProgress->total_paid_amount = $totalPaid;
        $loanProgress->balance = max(0, round($loanProgress->total_amount - $totalPaid, 2));

        // Update status based on balance
        if ($loanProgress->balance <= 0) {
            $loanProgress->status = $this->getCompletedStatusValue();
            $loanProgress->last_due_date = Carbon::parse($collectionDate);

            // CRITICAL: Also update LoanApproval when loan is fully paid
            $loanApproval = LoanApproval::where('loan_id', $loanId)->first();
            if (!$loanApproval) {
                throw new Exception("CRITICAL: LoanApproval record not found for loan {$loanId}");
            }

            if (in_array($loanApproval->status, ['Approved', 'Active'])) {
                $loanApproval->status = 'Completed';
                $loanApproval->active_at = $loanApproval->active_at ?? Carbon::now();

                if (!$loanApproval->save()) {
                    throw new Exception("CRITICAL: Failed to update LoanApproval status in updateLoanProgress for loan {$loanId}");
                }

                Log::info('LoanApproval updated to Completed in updateLoanProgress', [
                    'loan_id' => $loanId,
                    'approval_id' => $loanApproval->id
                ]);
            }
        } else {
            $loanProgress->status = 'Active';
        }

        if (!$loanProgress->save()) {
            throw new Exception("Failed to save LoanProgress for loan {$loanId}");
        }

        Log::info('Loan progress updated', [
            'loan_id' => $loanId,
            'total_paid' => $totalPaid,
            'total_due' => $totalDue,
            'balance' => $loanProgress->balance,
            'status' => $loanProgress->status,
            'is_final_payment' => $loanProgress->balance <= 0
        ]);
    }
}
