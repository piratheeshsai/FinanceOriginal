<?php
// App/Livewire/Account/Payments.php
namespace App\Livewire\Account;

use App\Models\Account;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\PaymentCategory;
use App\Models\PaymentSupplier;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\WithPagination;


class Payments extends Component
{
    use WithFileUploads;

    public $selectedCategory = '';
    public $selectedSupplier = '';
    public $paymentDate;
    public $amount;
    public $attachments = [];
    public $isSalary = false;
    public $search = '';
    public $isEditing = false;
    public $editingPaymentId = null;
    public $showRejectModal = false;
    public $selectedRequestId = null;
    public $rejectReason = '';

    public $statusFilter = '';
    public $dateFilter = '';

    // protected $listeners = ['refresh' => '$refresh'];
    use WithPagination;
    public $selectedSuppliers = [];
    public $filteredSuppliers = [];

    protected $listeners = ['setSelectedSuppliers'];

    protected $rules = [
        'selectedCategory' => 'required|exists:payment_categories,id',
        'selectedSuppliers' => 'required|array|min:1',
        'selectedSuppliers.*' => 'exists:suppliers,id', // Make sure table name matches your database
        'paymentDate' => 'required|date',
        'amount' => 'required|numeric|min:0',
        'attachments.*' => 'nullable|file|max:5120',
    ];




    protected $messages = [
        'selectedSuppliers.required' => 'Please select at least one supplier.',
        'selectedSuppliers.array' => 'Invalid supplier selection format.',
        'selectedSuppliers.min' => 'Please select at least one supplier.',
        'selectedSuppliers.*.exists' => 'One or more selected suppliers are invalid.',
    ];


    // #[On('approvePayment')]
    // public function approvePayment($paymentId)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $user = Auth::user();
    //         abort_unless($user->hasPermissionTo('approve-payment'), 403);

    //         $payment = Payment::findOrFail($paymentId);

    //         // Get the associated account (adjust this based on your account relationship)
    //         $cashier = Account::where('branch_id', $payment->branch_id)
    //         ->where('type', 'cash') // Salary Expense Account
    //         ->firstOrFail();

    //         if (!$cashier) {

    //             $this->dispatch('show-error-alert', message: 'account not found for this branch');
    //             return false;
    //         }

    //         // Check balance
    //         if ($cashier->balance < $payment->total_amount) {

    //             $this->dispatch('show-error-alert', message: 'Insufficient balance in the branch account.');
    //             return false;
    //         }

    //         // Update payment status
    //         $payment->update([
    //             'status' => 'approved',
    //             'approved_by' => $user->id,
    //             'rejection_reason' => null
    //         ]);

    //         if ($payment->paymentCategory->name === 'Salary') {
    //             // Use Salary Expense Account for salary payments
    //             $account = Account::where('branch_id', $payment->branch_id)
    //                 ->where('type', 'salary_expense') // Salary Expense Account
    //                 ->firstOrFail();
    //         } else {
    //             // Use the default account for other payments
    //             $account = Account::where('branch_id', $payment->branch_id)
    //                 ->where('type', $payment->paymentCategory->account_type) // Dynamic account type
    //                 ->firstOrFail();
    //         }



    //         // Create transaction record


    //         Transaction::create([
    //             'branch_id' => 1,
    //             'debit_account_id' => $account->id, // Salary Expense Account
    //             'credit_account_id' => $cashier->id, // Bank Account
    //             'amount' => 5000.00,
    //             'description' => 'Payment to Salary',
    //             'transaction_date' => now(),
    //             'created_by' => 1,
    //             'approved_by' => 2,
    //             'transaction_type' => 'payment_approval'
    //         ]);

    //         DB::commit();

    //         // After DB commit:
    //         $voucherNumber = 'PAY-' . now()->format('Ymd') . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT);

    //         Voucher::create([
    //             'voucher_number' => $voucherNumber,
    //             'type' => 'Payment',
    //             'reference_id' => $payment->id,
    //             'date' => now(),
    //             'amount' => $payment->total_amount,
    //             'payee_details' => $payment->suppliers->pluck('name')->implode(', '),
    //             'description' => 'Payment for ' . $payment->paymentCategory->name,
    //             'account_id' => $account->id,
    //             'approved_by' => $user->id,
    //             'created_by' => $payment->created_by,
    //             'branch_id' => $payment->branch_id
    //         ]);

    //         $this->dispatch('show-success-alert', message: 'Payment approved and funds deducted!');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         $this->dispatch('show-error-alert', message: 'Error: ' . $e->getMessage());
    //     }
    // }


    #[On('approvePayment')]
public function approvePayment($paymentId)
{
    DB::beginTransaction();

    try {
        // 1. Authorization Check
        $user = Auth::user();
        abort_unless($user->hasPermissionTo('approve-payment'), 403, 'You do not have permission to approve payments.');

        // 2. Fetch Payment
        $payment = Payment::with(['paymentCategory', 'suppliers'])->findOrFail($paymentId);

        // 3. Fetch Cashier Account
        $cashier = Account::where('branch_id', $payment->branch_id)
            ->where('type', 'bank') // bank Account
            ->first();

        if (!$cashier) {
            DB::rollBack();
            $this->dispatch('show-error-alert', message: 'Cashier account not found for this branch.');
            return;
        }

        // 4. Validate Cashier Balance
        if ($cashier->balance < $payment->total_amount) {
            DB::rollBack();
            $this->dispatch('show-error-alert', message: 'Insufficient balance in the cashier account.');
            return;
        }

        // 5. Fetch Payment Account Based on Category
        if ($payment->paymentCategory->name === 'salary') {
            // Use Salary Expense Account for salary payments
            $account = Account::where('branch_id', $payment->branch_id)
                ->where('type', 'salary_expense') // Salary Expense Account
                ->first();
        } elseif ($payment->paymentCategory->name === 'Rent') {
            // Use Rent Expense Account for rent payments
            $account = Account::where('branch_id', $payment->branch_id)
                ->where('type', 'rent_expense') // Rent Expense Account
                ->first();
        } elseif ($payment->paymentCategory->name === 'Supplier Payments') {
            // Use Office Supplies Expense Account for office supplies payments
            $account = Account::where('branch_id', $payment->branch_id)
                ->where('type', 'office_supplies_expense') // Office Supplies Expense Account
                ->first();
        } elseif ($payment->paymentCategory->name === 'Utilities') {
            // Use Utilities Expense Account for utilities payments
            $account = Account::where('branch_id', $payment->branch_id)
                ->where('type', 'utilities_expense') // Utilities Expense Account
                ->first();
        } else {
            // Use the default account for other payments
            $account = Account::where('branch_id', $payment->branch_id)
                ->where('type', 'other_expenses') // Dynamic account type
                ->first();
        }

        if (!$account) {
            DB::rollBack();
            $this->dispatch('show-error-alert', message: 'Account not found for payment category: ' . $payment->paymentCategory->name);
            return;
        }

        // 6. Update Payment Status
        $payment->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'rejection_reason' => null,
        ]);




        // 8. Create Transaction Record
        Transaction::create([
            'branch_id' => $payment->branch_id,
            'debit_account_id' => $account->id, // Debit: Expense Account
            'credit_account_id' => $cashier->id, // Credit: Cashier Account
            'amount' => $payment->total_amount,
            'description' => 'Payment to ' . $payment->paymentCategory->name,
            'transaction_date' => now(),
            'created_by' => $payment->created_by,
            'approved_by' => $user->id,
            'transaction_type' => 'salary payment',
        ]);

        // 9. Generate Voucher Number
        $voucherNumber = 'PAY-' . now()->format('Ymd') . '-' . str_pad($payment->id, 5, '0', STR_PAD_LEFT);

        // 10. Create Voucher
        Voucher::create([
            'voucher_number' => $voucherNumber,
            'type' => 'Payment',
            'reference_id' => $payment->id,
            'date' => now(),
            'amount' => $payment->total_amount,
            'payee_details' => $payment->suppliers->pluck('name')->implode(', '),
            'description' => 'Payment for ' . $payment->paymentCategory->name,
            'account_id' => $account->id,
            'approved_by' => $user->id,
            'created_by' => $payment->created_by,
            'branch_id' => $payment->branch_id,
        ]);

        // 11. Commit Transaction
        DB::commit();

        // 12. Notify User
        $this->dispatch('show-success-alert', message: 'Payment approved and You Can Pay!');
    } catch (\Exception $e) {
        DB::rollBack();
        $this->dispatch('show-error-alert', message: 'Error: ' . $e->getMessage());
    }
}



    public function closeModal()
    {
        $this->reset(['showRejectModal', 'selectedRequestId', 'rejectReason']);
    }
    public function openRejectModal($requestId)
    {
        $this->selectedRequestId = $requestId;
        $this->showRejectModal = true;
    }



    public function rejectRequest()
    {
        $this->validate(['rejectReason' => 'required|min:10|max:255']);

        $user = Auth::user();


        Payment::findOrFail($this->selectedRequestId)->update([
            'status' => 'rejected',
            'rejection_reason' => $this->rejectReason,
            'approved_by' => $user->id
        ]);

        $this->closeModal();
        $this->dispatch('show-success-alert', message: 'Request rejected!');
    }



    public function mount()
    {
        $this->paymentDate = now()->format('Y-m-d');
    }

    public function updatedSelectedCategory($value)
    {
        $this->selectedSuppliers = [];
        $this->amount = null;

        if ($value) {
            $category = PaymentCategory::find($value);
            $this->isSalary = $category->name === 'salary';
            $this->loadSuppliers();
        } else {
            $this->isSalary = false;
            $this->filteredSuppliers = [];
        }
    }

    public function setSelectedSuppliers($suppliers)
    {
        $this->selectedSuppliers = $suppliers;

        if ($this->isSalary) {
            $totalSalary = 0;
            foreach ($this->selectedSuppliers as $supplierId) {
                $supplier = PaymentSupplier::find($supplierId);
                if ($supplier) {
                    $totalSalary += $supplier->salary;
                }
            }
            $this->amount = $totalSalary;
            $this->dispatch('amount-updated', amount: $this->amount);
        }
    }

    public function enterEditMode($paymentId)
    {
        $this->isEditing = true;
        $this->editingPaymentId = $paymentId;

        // Load the payment data
        $payment = Payment::with(['paymentCategory', 'suppliers'])->findOrFail($paymentId);

        // Set the form fields
        $this->selectedCategory = $payment->payment_category_id;
        $this->paymentDate = $payment->payment_date;
        $this->amount = $payment->total_amount;

        // First load the suppliers for the category
        $this->loadSuppliers();

        // Then set the selected suppliers
        $this->selectedSuppliers = $payment->suppliers->pluck('id')->toArray();

        // Check if this is a salary payment
        $category = PaymentCategory::find($this->selectedCategory);
        $this->isSalary = $category->name === 'salary';

        // Dispatch events to update the UI
        $this->dispatch('edit-mode-activated', selectedSuppliers: $this->selectedSuppliers);
    }



    public function updatedSelectedSupplier($value)
    {
        if ($this->isSalary && $value) {
            $supplier = PaymentSupplier::with('paymentCategory')->find($value);

            if ($supplier) {
                $this->amount = $supplier->salary;
                $this->dispatch('amount-updated', amount: $this->amount);
            }
        } else {
            $this->amount = null;
        }
    }









    public function loadSuppliers()
    {
        $this->filteredSuppliers = PaymentSupplier::where('payment_category_id', $this->selectedCategory)
            ->get()
            ->map(function ($supplier) {
                return [
                    'id' => $supplier->id,
                    'name' => $supplier->name,
                    'salary' => $supplier->Salary,
                    'payment_category' => [
                        'name' => $supplier->paymentCategory->name
                    ]
                ];
            })->toArray();

        $this->dispatch('suppliersUpdated', suppliers: $this->filteredSuppliers);
    }










    public function update()
    {
        try {
            \Log::info('Starting payment update process', [
                'payment_id' => $this->editingPaymentId,
                'category' => $this->selectedCategory,
                'suppliers' => $this->selectedSuppliers,
                'amount' => $this->amount,
                'date' => $this->paymentDate
            ]);

            $this->validate();
            \Log::info('Validation passed for update');

            \DB::beginTransaction();

            // Find and update the payment
            $payment = Payment::findOrFail($this->editingPaymentId);
            $payment->update([
                'payment_category_id' => $this->selectedCategory,
                'total_amount' => $this->amount,
                'payment_date' => $this->paymentDate,
                // Don't update status or created_by as these should remain unchanged
            ]);
            \Log::info('Payment updated:', ['payment_id' => $payment->id]);

            // Detach all existing suppliers and attach new ones
            $payment->suppliers()->detach();

            foreach ($this->selectedSuppliers as $supplierId) {
                $supplier = PaymentSupplier::find($supplierId);
                $supplierAmount = $this->isSalary ?
                    $supplier->salary :
                    $this->amount / count($this->selectedSuppliers);

                $payment->suppliers()->attach($supplierId, [
                    'amount' => $supplierAmount
                ]);
                \Log::info('Supplier reattached', [
                    'supplier_id' => $supplierId,
                    'amount' => $supplierAmount
                ]);
            }

            \DB::commit();
            \Log::info('Update transaction committed successfully');

            // Reset the form and exit edit mode
            $this->isEditing = false;
            $this->editingPaymentId = null;
            $this->resetForm();

            session()->flash('success', 'Payment updated successfully!');
            $this->dispatch('paymentUpdated');
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Payment update failed:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error updating payment: ' . $e->getMessage());
        }
    }

    public function refresh()
    {
        // Empty method just to force refresh
    }

    public function save()
    {
        if ($this->isEditing) {
            $this->update();
        } else {
            try {
                \Log::info('Starting payment save process');
                \Log::info('Pre-validation data:', [
                    'category' => $this->selectedCategory,
                    'suppliers' => $this->selectedSuppliers,
                    'amount' => $this->amount,
                    'date' => $this->paymentDate
                ]);

                $this->validate();
                \Log::info('Validation passed');

                \DB::beginTransaction();

                // Create payment
                $payment = Payment::create([
                    'payment_category_id' => $this->selectedCategory,
                    'total_amount' => $this->amount,
                    'status' => 'pending',
                    'payment_date' => $this->paymentDate,
                    'created_by' => auth()->id(),
                    'branch_id' => auth()->user()->branch_id
                ]);
                \Log::info('Payment created:', ['payment_id' => $payment->id]);

                // Attach suppliers
                foreach ($this->selectedSuppliers as $supplierId) {
                    $supplier = PaymentSupplier::find($supplierId);
                    $supplierAmount = $this->isSalary ?
                        $supplier->salary :
                        $this->amount / count($this->selectedSuppliers);

                    $payment->suppliers()->attach($supplierId, [
                        'amount' => $supplierAmount
                    ]);
                    \Log::info('Supplier attached', [
                        'supplier_id' => $supplierId,
                        'amount' => $supplierAmount
                    ]);
                }

                \DB::commit();
                \Log::info('Transaction committed successfully');

                $this->resetForm();
                $this->dispatch('paymentSaved');
                $this->dispatch('show-success-alert', message: 'Payment Created successfully!');
            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Payment save failed:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                session()->flash('error', 'Error: ' . $e->getMessage());
                $this->dispatch('show-error-alert', message: 'Payment Not Created :'. $e->getMessage());
            }
        }
    }

    public function resetForm()
    {
        $this->selectedCategory = '';
        $this->selectedSuppliers = [];
        $this->amount = null;
        $this->paymentDate = now()->format('Y-m-d');
        $this->attachments = [];
        $this->isSalary = false;
        $this->filteredSuppliers = [];

        $this->dispatch('suppliersUpdated', suppliers: []);
    }



    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }


    public function deletePayment($paymentId)
{
    DB::beginTransaction();

    try {
        // Find the payment
        $payment = Payment::findOrFail($paymentId);

        // Check permissions
        $user = Auth::user();
        if (!$user->hasPermissionTo('view all branches')) {
            $this->dispatch('show-error-alert', message: 'You do not have permission to delete payments.');
            return;
        }
        if ($payment->status !== 'pending' && $payment->status !== 'rejected') {
            $this->dispatch('show-error-alert', message: 'Only pending or rejected payments can be deleted.');
            return;
        }


        // First detach all suppliers
        $payment->suppliers()->detach();

        // Then delete the payment
        $payment->delete();

        DB::commit();

        // Show success message
        $this->dispatch('show-success-alert', message: 'Payment deleted successfully!');

        // Refresh the component
        $this->dispatch('paymentDeleted');
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Payment deletion failed:', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        $this->dispatch('show-error-alert', message: 'Error deleting payment: ' . $e->getMessage());
    }
}

    public function render()
    {


        $payments = Payment::query()
            ->with(['paymentCategory', 'createdBy', 'approvedBy', 'suppliers',])
            // ->where('branch_id', auth()->user()->branch_id)
            ->when($this->search, function ($query) {
                $query->whereHas('paymentCategory', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFilter, function ($query) {
                $query->whereDate('created_at', $this->dateFilter);
            })
            ->latest()
            ->paginate(50);

        // Return the view with the data
        return view('livewire.account.payments', [
            'categories' => PaymentCategory::all(),
            'payments' => $payments
        ]);
    }
}
