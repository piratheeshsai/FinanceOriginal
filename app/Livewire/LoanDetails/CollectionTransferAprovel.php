<?php

namespace App\Livewire\LoanDetails;

use App\Models\Account;
use App\Models\StaffCollectionStatus;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use DB;
use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Log;
use Illuminate\Validation\ValidationException;

class CollectionTransferAprovel extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;



    public function approveTransfer($transferId)
    {
        try {
            // Strict validation of input
            if (!is_numeric($transferId) || $transferId <= 0) {
                throw new ValidationException('Invalid transfer ID provided.');
            }

            DB::beginTransaction();

            // Fetch the transfer with strict checks
            $transfer = Transfer::findOrFail($transferId);

            // Strict status validation
            if ($transfer->status !== 'pending') {
                throw new Exception("Transfer {$transferId} is not in pending status. Current status: {$transfer->status}");
            }

            // Fetch the related staff collection status records with strict validation
            $staffCollections = StaffCollectionStatus::where('transfers_id', $transferId)->get();

            if ($staffCollections->isEmpty()) {
                throw new Exception("No staff collections found for transfer {$transferId}");
            }

            // Strict validation of all staff collections status
            foreach ($staffCollections as $staffCollection) {
                if ($staffCollection->status === 'Transferred') {
                    throw new Exception("Staff collection {$staffCollection->id} is already transferred");
                }
                if (!$staffCollection->collection) {
                    throw new Exception("Collection record missing for staff collection {$staffCollection->id}");
                }
            }

            // Sum the principal and interest amounts with strict validation
            $totalPrincipal = $staffCollections->sum(function ($staffCollection) {
                $amount = $staffCollection->collection->collected_amount ?? 0;
                if ($amount < 0) {
                    throw new Exception("Invalid collected amount in collection {$staffCollection->collection->id}");
                }
                return $amount;
            });

            $totalInterest = $staffCollections->sum(function ($staffCollection) {
                $amount = $staffCollection->collection->interest_amount ?? 0;
                if ($amount < 0) {
                    throw new Exception("Invalid interest amount in collection {$staffCollection->collection->id}");
                }
                return $amount;
            });

            // Validate amounts are positive
            if ($totalPrincipal <= 0) {
                throw new Exception("Total principal amount must be greater than zero. Current: {$totalPrincipal}");
            }

            // Fetch accounts with strict validation
            $cashierAccount = Account::where('branch_id', $transfer->branch_id)
                ->where('type', 'cash')
                ->first();

            if (!$cashierAccount) {
                throw new Exception("Cashier account not found for branch {$transfer->branch_id}");
            }

            $collection_cash = Account::where('branch_id', $transfer->branch_id)
                ->where('type', 'collection_cash')
                ->first();

            if (!$collection_cash) {
                throw new Exception("Collection cash account not found for branch {$transfer->branch_id}");
            }

            $interestAccount = Account::where('branch_id', $transfer->branch_id)
                ->where('type', 'interest_income')
                ->first();

            if (!$interestAccount) {
                throw new Exception("Interest income account not found for branch {$transfer->branch_id}");
            }

            // Validate account balances if needed (optional strict check)
            if ($collection_cash->balance < $totalPrincipal) {
                throw new Exception("Insufficient balance in collection cash account. Required: {$totalPrincipal}, Available: {$collection_cash->balance}");
            }

            // Create transaction with strict validation
            $transaction = Transaction::create([
                'branch_id' => $transfer->branch_id,
                'debit_account_id' => $cashierAccount->id,
                'credit_account_id' => $collection_cash->id,
                'transaction_date' => now(),
                'transaction_type' => 'repayment_transfer',
                'loan_id' => null,
                'type' => 'loan_repayment_total',
                'amount' => $totalPrincipal,
                'description' => 'Total repayment (principal + interest) for transfer ' . $transfer->id,
                'status' => 'completed',
                'created_by' => auth()->id(),
            ]);

            if (!$transaction->id) {
                throw new Exception("Failed to create transaction record");
            }





            // Update transfer status with strict validation
            $transferUpdated = Transfer::where('id', $transferId)
                ->where('status', 'pending') // Double-check status hasn't changed
                ->update([
                    'status' => 'approved',
                    'approved_by' => Auth::id(),
                    'updated_at' => now()
                ]);

            if ($transferUpdated !== 1) {
                throw new Exception("Failed to update transfer status or transfer was modified by another process");
            }

            // Update staff collection status with strict validation
            $staffCollectionUpdated = StaffCollectionStatus::where('transfers_id', $transferId)
                ->whereNotIn('status', ['Transferred', 'rejected']) // Only update pending ones
                ->update([
                    'status' => 'Transferred',
                    'updated_at' => now()
                ]);

            if ($staffCollectionUpdated !== $staffCollections->count()) {
                throw new Exception("Failed to update all staff collection statuses. Expected: {$staffCollections->count()}, Updated: {$staffCollectionUpdated}");
            }

            // Final validation - verify all changes were applied
            $updatedTransfer = Transfer::find($transferId);
            if ($updatedTransfer->status !== 'approved') {
                throw new Exception("Transfer status validation failed after update");
            }

            $updatedStaffCollections = StaffCollectionStatus::where('transfers_id', $transferId)->get();
            foreach ($updatedStaffCollections as $sc) {
                if ($sc->status !== 'Transferred') {
                    throw new Exception("Staff collection status validation failed for collection {$sc->id}");
                }
            }

            DB::commit();

            session()->flash('message', 'Transfer approved successfully. Amounts updated in accounts.');

        } catch (ValidationException $e) {
            DB::rollback();
            session()->flash('error', 'Validation Error: ' . $e->getMessage());
            Log::error('Transfer Approval Validation Error: ' . $e->getMessage(), [
                'transfer_id' => $transferId,
                'user_id' => auth()->id()
            ]);
        } catch (Exception $e) {
            DB::rollback();
            session()->flash('error', 'Failed to approve transfer: ' . $e->getMessage());
            Log::error('Transfer Approval Error: ' . $e->getMessage(), [
                'transfer_id' => $transferId,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function rejectTransfer($transferId, $reason = null)
    {
        try {
            // Strict validation of input
            if (!is_numeric($transferId) || $transferId <= 0) {
                throw new ValidationException('Invalid transfer ID provided.');
            }

            DB::beginTransaction();

            // Fetch the transfer with strict validation
            $transfer = Transfer::findOrFail($transferId);

            // Strict status validation
            if ($transfer->status !== 'pending') {
                throw new Exception("Transfer {$transferId} is not in pending status. Current status: {$transfer->status}");
            }

            // Validate rejection reason if required by business rules
            if (empty($reason)) {
                $reason = 'No reason provided';
            }

            // Fetch related staff collections for validation
            $staffCollections = StaffCollectionStatus::where('transfers_id', $transferId)->get();

            if ($staffCollections->isEmpty()) {
                throw new Exception("No staff collections found for transfer {$transferId}");
            }

            // Update transfer status with strict validation
            $transferUpdated = Transfer::where('id', $transferId)
                ->where('status', 'pending') // Double-check status hasn't changed
                ->update([
                    'status' => 'rejected',
                    'approved_by' => Auth::id(),
                    'rejection_reason' => $reason,
                    'updated_at' => now()
                ]);

            if ($transferUpdated !== 1) {
                throw new Exception("Failed to update transfer status or transfer was modified by another process");
            }

            // Update staff collection status with strict validation
            $staffCollectionUpdated = StaffCollectionStatus::where('transfers_id', $transferId)
                ->whereNotIn('status', ['rejected', 'Transferred']) // Only update pending ones
                ->update([
                    'status' => 'rejected',
                    'updated_at' => now()
                ]);

            if ($staffCollectionUpdated !== $staffCollections->count()) {
                throw new Exception("Failed to update all staff collection statuses. Expected: {$staffCollections->count()}, Updated: {$staffCollectionUpdated}");
            }

            // Final validation - verify all changes were applied
            $updatedTransfer = Transfer::find($transferId);
            if ($updatedTransfer->status !== 'rejected') {
                throw new Exception("Transfer status validation failed after update");
            }

            $updatedStaffCollections = StaffCollectionStatus::where('transfers_id', $transferId)->get();
            foreach ($updatedStaffCollections as $sc) {
                if ($sc->status !== 'rejected') {
                    throw new Exception("Staff collection status validation failed for collection {$sc->id}");
                }
            }

            DB::commit();
            $this->closeRejectModal();

            session()->flash('message', 'Transfer rejected successfully.');

        } catch (ValidationException $e) {
            DB::rollback();
            session()->flash('error', 'Validation Error: ' . $e->getMessage());
            Log::error('Transfer Rejection Validation Error: ' . $e->getMessage(), [
                'transfer_id' => $transferId,
                'user_id' => auth()->id()
            ]);
        } catch (Exception $e) {
            DB::rollback();
            session()->flash('error', 'Failed to reject transfer: ' . $e->getMessage());
            Log::error('Transfer Rejection Error: ' . $e->getMessage(), [
                'transfer_id' => $transferId,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public $showRejectModal = false;
    public $selectedTransferId;
    public $rejectionReason;

    public function openRejectModal($transferId)
    {
        // Strict validation
        if (!is_numeric($transferId) || $transferId <= 0) {
            session()->flash('error', 'Invalid transfer ID provided.');
            return;
        }

        // Verify transfer exists and is in pending status
        $transfer = Transfer::find($transferId);
        if (!$transfer) {
            session()->flash('error', 'Transfer not found.');
            return;
        }

        if ($transfer->status !== 'pending') {
            session()->flash('error', 'Transfer is not in pending status.');
            return;
        }

        $this->selectedTransferId = $transferId;
        $this->showRejectModal = true;
    }

    public function closeRejectModal()
    {
        $this->showRejectModal = false;
        $this->selectedTransferId = null;
        $this->rejectionReason = null;
    }

    public function render()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                throw new Exception('User not authenticated');
            }

            // Base query for pending transfers with strict validation
            $query = Transfer::where('status', 'pending')
                        ->where('description', 'collected amount Transfer to cashier');

            // Apply search filter with strict validation
            if (!empty($this->search)) {
                $searchTerm = trim($this->search);
                if (strlen($searchTerm) > 0) {
                    $query->where(function ($q) use ($searchTerm) {
                        $q->where('description', 'like', '%' . $searchTerm . '%')
                          ->orWhere('amount', 'like', '%' . $searchTerm . '%')
                          ->orWhereHas('createdBy', function ($subQuery) use ($searchTerm) {
                              $subQuery->where('name', 'like', '%' . $searchTerm . '%');
                          });
                    });
                }
            }

            // Apply permission-based filters with strict validation
            if ($user->hasPermissionTo('view all branches')) {
                // No additional filter needed; show all pending transfers
            } elseif ($user->hasPermissionTo('Loan C.T approval')) {
                if (!$user->branch_id) {
                    throw new Exception('User branch ID not found');
                }
                $query->where('branch_id', $user->branch_id);
            } else {
                // If the user doesn't have any relevant permissions, show no transfers
                $query->where('id', 0);
            }

            // Validate perPage value
            $perPage = max(1, min(100, $this->perPage)); // Ensure between 1 and 100

            // Fetch paginated transfers
            $pendingTransfers = $query->paginate($perPage);

            return view('livewire.loan-details.collection-transfer-aprovel', [
                'pendingTransfers' => $pendingTransfers,
            ]);

        } catch (Exception $e) {
            Log::error('Render Error in CollectionTransferAprovel: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);

            session()->flash('error', 'An error occurred while loading transfers.');

            return view('livewire.loan-details.collection-transfer-aprovel', [
                'pendingTransfers' => collect()->paginate(1),
            ]);
        }
    }
}
