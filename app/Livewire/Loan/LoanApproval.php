<?php

namespace App\Livewire\Loan;

use App\Models\Account;
use App\Models\Loan;
use App\Models\LoanApproval as ModelsLoanApproval;
use App\Models\LoanCollectionSchedule;
use App\Models\LoanProgress;
use App\Models\Transaction;
use App\Models\Voucher;
use DB;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;


class LoanApproval extends Component
{



    use WithPagination;

    public $perPage = 10;
    public $loanIdToReject;
    public $rejectionReason;
    public $statusFilter = '';


    protected $listeners = ['activateLoan'];
    // Method to handle the reject loan logic
    public function rejectLoan($loanId)
    {
        $this->loanIdToReject = $loanId;  // Ensure this is set properly
        $this->dispatch('open-rejection-modal');  // Dispatch the event to open the modal
    }

    // Method to handle the submission of rejection reason
    public function submitRejectionReason()
    {
        // Find the loan approval and update the status and rejection reason
        $loanApproval = ModelsLoanApproval::where('loan_id', $this->loanIdToReject)->first();

        if ($loanApproval) {
            $loanApproval->update([
                'status' => 'Rejected',
                'rejection_reason' => $this->rejectionReason,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            $this->dispatch('close-rejection-modal');  // Close the modal after submission
            $this->dispatch('rejected-message'); //
        }

        $this->rejectionReason = '';  // Reset rejection reason
    }
  
    public function approveLoan($loanId)
    {
        DB::beginTransaction();
        try {
            $loan = Loan::with('loanScheme')->findOrFail($loanId);

            // Approve the loan
            $loan->approval()->updateOrCreate([], [
                'status' => 'Approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            // Create voucher without customer name
            $voucherNumber = 'LOAN-' . now()->format('Ymd') . '-' . str_pad($loan->id, 5, '0', STR_PAD_LEFT);

            Voucher::create([
                'voucher_number' => $voucherNumber,
                'loan_id' => $loan->id,
                'amount' => $loan->loan_amount,
                'reference_id' => $loan->id,
                'customer_id' => $loan->customer_id,
                'date' => now(),
                'type' => 'Loan Disbursement',
                'description' => 'Loan disbursement voucher for ' . $loan->customer->full_name,
                'approved_by' => auth()->id(),
                'created_by' => $loan->loan_creator_name,
                'branch_id' => $loan->center->branch_id,
            ]);

            DB::commit();
            $this->dispatch('approved_message');
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function activateLoan($loanId)
    {
        try {
            DB::transaction(function () use ($loanId) {
                // Fetch the loan with related data
                $loan = Loan::with(['center.branch', 'loanScheme', 'customer'])
                    ->findOrFail($loanId);

                // 1. Get or create the loan receivable account
                $loanAccount = Account::firstOrCreate(
                    ['branch_id' => $loan->center->branch_id, 'type' => 'loan_receivable'],
                    [
                        'account_number' => 'LOAN-RECEIVABLE-' . $loan->center->branch->name,
                        'name' => 'Loan Receivable - ' . $loan->center->branch->name,
                        'balance' => 0,
                    ]
                );


                if (is_null($loan->loan_amount) || $loan->loan_amount <= 0) {
                    throw new \Exception('Invalid loan amount');
                }
                // 2. Get required accounts with error handling
                $disbursementAccount = Account::where('branch_id', $loan->center->branch_id)
                    ->where('type', 'bank')
                    ->firstOrFail();

                $documentChargeAccount = Account::where('branch_id', $loan->center->branch_id)
                    ->where('type', 'document_charge_income')
                    ->firstOrFail();

                $cashAccount = Account::where('branch_id', $loan->center->branch_id)
                    ->where('type', 'cash')
                    ->firstOrFail();

                // 3. Validate balances with buffer for floating point precision
                $balanceCheckBuffer = 0.01;
                if (($disbursementAccount->balance + $balanceCheckBuffer) < $loan->loan_amount) {
                    throw new \Exception('Insufficient balance in the branch account for disbursement.');
                }



                // 4. Create main loan disbursement transaction
                Transaction::create([
                    'debit_account_id' => $loanAccount->id,
                    'credit_account_id' => $disbursementAccount->id,
                    'amount' => $loan->loan_amount,
                    'branch_id' => $loan->center->branch_id,
                    'loan_id' => $loan->id,
                    'description' => 'Loan disbursement to ' . $loan->customer->full_name,
                    'status' => 'complete',
                    'transaction_date' => now(),
                    'created_by' => auth()->id(),
                    'transaction_type' => 'loan_disbursement',
                ]);

                // 5. Create document charge transaction
                Transaction::create([
                    'branch_id' => $loan->center->branch_id,
                    'debit_account_id' => $cashAccount->id,
                    'credit_account_id' => $documentChargeAccount->id,
                    'amount' => $loan->document_charge,
                    'description' => 'Document charge for loan ' . $loan->loan_number,
                    'transaction_type' => 'document_charge',
                    'created_by' => auth()->id(),
                    'transaction_date' => now(),
                    'status' => 'complete',
                ]);




                // 7. Update loan status
                $loan->approval()->updateOrCreate(
                    ['loan_id' => $loan->id],
                    [
                        'status' => 'active',
                        'active_at' => now(),
                        'activated_by' => auth()->id(),
                    ]
                );


                $this->generateCollections($loan);
            });


            $this->dispatch('activated_message');
            $this->dispatch('show-success-alert', message: 'Loan activated successfully.');
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Loan activation failed: ' . $e->getMessage());
            $this->dispatch('show-error-alert', message: $e->getMessage());
            return false;
        }
    }



  
public function generateCollections($loan) {
    try {
        DB::beginTransaction();

        $principal = $loan->loan_amount;
        $term = $loan->loanScheme->loan_term;
        $interestRate = ($loan->loanScheme->interest_rate * $term) / 100;
        $duration = $loan->loanScheme->collecting_duration;

        $installmentAmount = ($principal / $term) + ($principal * $interestRate / $term);
        $currentDate = \Carbon\Carbon::parse($loan->start_date);
        $lastDueDate = null;
        $cumulativePendingDue = 0;
        $cumulativeTotalDue = 0;

        for ($i = 1; $i <= $term; $i++) {
            // For the first iteration, use the start date as is
            if ($i > 1) {
                // Calculate the next due date based on duration for subsequent iterations
                switch ($duration) {
                    case 'daily':
                        $currentDate = $currentDate->addDay();
                        break;
                    case 'weekly':
                        $currentDate = $currentDate->addWeek();
                        break;
                    case 'monthly':
                        $currentDate = $currentDate->addMonth();
                        break;
                    default:
                        throw new Exception("Invalid collecting duration: $duration");
                }
            }

            // Skip Sundays by checking and adjusting
            if ($currentDate->isSunday()) {
                $currentDate = $currentDate->addDay(); // Move to Monday
            }

            $lastDueDate = $currentDate;

            // Cumulative calculations
            $cumulativePendingDue += $installmentAmount;
            $cumulativeTotalDue += $installmentAmount;

            // Create collection schedule
            LoanCollectionSchedule::create([
                'loan_id' => $loan->id,
                'date' => $currentDate->copy(), // Use copy() to avoid reference issues
                'description' => 'Installment Payment',
                'principal' => $principal / $term,
                'interest' => ($principal * $interestRate / $term),
                'penalty' => 0,
                'due' => $installmentAmount,
                'paid' => 0,
                'pending_due' => $cumulativePendingDue,
                'total_due' => $cumulativeTotalDue,
                'principal_due' => $principal / $term,
                'status' => 'pending',
            ]);
        }

        // Track loan progress
        LoanProgress::create([
            'loan_id' => $loan->id,
            'total_amount' => $principal + ($principal * $interestRate),
            'balance' => $principal + ($principal * $interestRate),
            'total_paid_amount' => 0,
        ]);

        DB::commit();
        return true;
    } catch (\Exception $e) {
        DB::rollBack();
        throw $e;
    }
}





    public function render()
    {

        $loans = Loan::with('customer', 'approval')
        ->where('created_at', '>=', now()->subDays(10))
        ->orderByRaw("CASE
            WHEN EXISTS (SELECT 1 FROM loan_approvals WHERE loan_approvals.loan_id = loan.id AND loan_approvals.status = 'pending') THEN 1
            WHEN EXISTS (SELECT 1 FROM loan_approvals WHERE loan_approvals.loan_id = loan.id AND loan_approvals.status = 'approved') THEN 2
            ELSE 3 END")
        ->latest('created_at')
        ->paginate($this->perPage);

    return view('livewire.loan.loan-approval', compact('loans'));
    }
}
