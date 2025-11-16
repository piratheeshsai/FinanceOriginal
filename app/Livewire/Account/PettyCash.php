<?php

namespace App\Livewire\Account;

use App\Models\Account;
use App\Models\PettyCashRequest;
use App\Models\PettyCashType;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Voucher;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


class PettyCash extends Component
{


    use WithFileUploads, WithPagination;
    public $amount;
    public $typeId;
    public $attachments;
    public $accountId;
    public $requestEmployee;

    public $accounts = [];
    public $types = [];
    public $employees = [];

    // For filtering
    public $search = '';
    public $statusFilter = '';
    public $dateFilter = '';


    public $editMode = false;
    public $pettyCashRequestId;
    public $currentAttachment = null;

    public $showRejectModal = false;
    public $selectedRequestId = null;
    public $rejectReason = '';

    public function openRejectModal($requestId)
    {
        $this->selectedRequestId = $requestId;
        $this->showRejectModal = true;
    }

    public function rejectRequest()
{
    $this->validate(['rejectReason' => 'required|min:10|max:255']);

    $user = Auth::user();


    PettyCashRequest::findOrFail($this->selectedRequestId)->update([
        'status' => 'rejected',
        'rejection_reason' => $this->rejectReason,
        'approved_by' => $user->id
    ]);

    $this->closeModal();
    $this->dispatch('show-success-alert', message: 'Request rejected!');
}

public function closeModal()
{
    $this->reset(['showRejectModal', 'selectedRequestId', 'rejectReason']);
}

    protected $rules = [
        'amount'           => 'required|numeric|min:0.01',
        'typeId'           => 'required|exists:petty_cash_types,id',
        'accountId'        => 'required|exists:accounts,id',
        'requestEmployee'  => 'required|exists:users,id',
        'attachments'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048', // Single file
    ];

    public function mount($id = null)
    {
        if ($id) {
            $this->enterEditMode($id);
        }
        $this->loadFormData();
        $this->setDefaultAccount();
    }


    public function enterEditMode($id)
    {
        $this->editMode = true;
        $this->pettyCashRequestId = $id;

        $request = PettyCashRequest::findOrFail($id);

        $this->typeId = $request->type_id;
        $this->amount = $request->amount;
        $this->accountId = $request->account_id;
        $this->requestEmployee = $request->request_employee;
        $this->currentAttachment = $request->attachments;
    }
    public function cancelEdit()
    {
        $this->editMode = false;
        $this->pettyCashRequestId = null;
        $this->currentAttachment = null;
        $this->reset(['amount', 'typeId', 'attachments', 'accountId', 'requestEmployee']);
        $this->loadFormData();
    }
    protected function loadFormData()
    {
        $this->accounts = Account::where('branch_id', auth()->user()->branch_id)
            ->where('type', 'Petty_Cash')
            ->get();

        $this->types = PettyCashType::all();
        $this->employees = User::where('branch_id', auth()->user()->branch_id)->get();
    }

    protected function setDefaultAccount()
    {
        // Set default Petty Cash account if not selected
        if (!$this->accountId) {
            $this->accountId = Account::where('branch_id', auth()->user()->branch_id)
                ->where('type', 'Petty_Cash')
                ->value('id');
        }
    }



    public function submit()
    {
        $this->validate();
        $this->setDefaultAccount();

        DB::beginTransaction();

        try {
            if ($this->editMode) {
                $pettyCashRequest = PettyCashRequest::findOrFail($this->pettyCashRequestId);
                $oldAttachment = $pettyCashRequest->attachments;
            } else {
                $pettyCashRequest = new PettyCashRequest();
                $pettyCashRequest->branch_id = auth()->user()->branch_id;
                $pettyCashRequest->created_by = auth()->id();
                $pettyCashRequest->status = 'pending';
            }

            // Handle file upload
            $attachmentPath = $this->currentAttachment;
            if ($this->attachments) {
                // Delete old file if exists
                if ($this->editMode && $oldAttachment) {
                    Storage::disk('public')->delete($oldAttachment);
                }

                // Store new file
                $originalName = $this->attachments->getClientOriginalName();
                $sanitizedName = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
                $extension = $this->attachments->getClientOriginalExtension();

                $folderPath = "petty-cash-attachments/" .
                            ($this->editMode ? $pettyCashRequest->id : 'temp') . "/" .
                            now()->format('Y/m/d');

                $attachmentPath = $this->attachments->storeAs(
                    $folderPath,
                    "$sanitizedName.$extension",
                    'public'
                );
            }

            // Update request data
            $pettyCashRequest->fill([
                'account_id' => $this->accountId,
                'amount' => $this->amount,
                'type_id' => $this->typeId,
                'request_employee' => $this->requestEmployee,
                'attachments' => $attachmentPath ?? $pettyCashRequest->attachments,
            ])->save();

            DB::commit();

            $this->dispatch('show-success-alert', message:
                $this->editMode ? 'Request updated!' : 'Request created!');

            $this->resetForm();
            $this->loadFormData();
            $this->dispatch('refreshComponent');

            $this->dispatch('reload-page');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-error-alert', message: 'Error: ' . $e->getMessage());
        }
    }

    protected function cleanupTempFiles()
    {
        if (!$this->editMode && $this->attachments) {
            Storage::disk('public')->deleteDirectory('petty-cash-attachments/temp');
        }
    }

    protected function resetForm()
    {
        $this->reset([
            'amount',
            'typeId',
            'attachments',
            'accountId',
            'requestEmployee',
            'editMode',
            'pettyCashRequestId',
            'currentAttachment',

        ]);

        // Force reset file input
        $this->dispatch('reset-file-input');
        $this->resetErrorBag();
        $this->resetValidation();
        $this->cleanupTempFiles();
    }



    public function confirmApproval($requestId)
    {
        $this->dispatch('show-confirmation', [
            'requestId' => $requestId  // Change the key structure
        ]);
    }

    #[On('approveRequest')]

    public function approveRequest($requestId)
    {
        DB::beginTransaction();

        try {
            $user = Auth::user();
            abort_unless($user->hasPermissionTo('approve petty cash transfer'), 403);

            $pettyRequest = PettyCashRequest::findOrFail($requestId);

            // Get the account
            $Petty_Cash = Account::where('branch_id', $pettyRequest->branch_id)
                ->where('type', 'Petty_Cash')
                ->firstOrFail();

                $Petty_Cash_Expense = Account::where('branch_id', $pettyRequest->branch_id)
                ->where('type', 'other_expenses')
                ->firstOrFail();




            if (!$Petty_Cash) {
                DB::rollBack();
                $this->dispatch('show-error-alert', message: 'Petty Cash account not found for this branch.');
                return;
            }
            if (!$Petty_Cash_Expense) {
                DB::rollBack();
                $this->dispatch('show-error-alert', message: 'Petty Cash Expense account not found for this branch.');
                return;
            }


            if ($Petty_Cash->balance < $pettyRequest->amount) {
                DB::rollBack();
                $this->dispatch('show-error-alert', message: 'Insufficient balance in the Petty cash account.');
                return;
            }


            // Update petty request
            $pettyRequest->update([
                'status' => 'approved',
                'approved_by' => $user->id,
                'reject_reason' => null
            ]);


            Transaction::create([
                'branch_id' => $pettyRequest->branch_id,
                'debit_account_id' => $Petty_Cash_Expense->id, // Debit: Expense Account
                'credit_account_id' => $Petty_Cash->id, // Credit: Cashier Account
                'amount' => $pettyRequest->amount,
                'description' => 'Petty cash to ' . $pettyRequest->type->name,
                'transaction_date' => now(),
                'created_by' => $pettyRequest->created_by,
                'approved_by' => $user->id,
                'transaction_type' => 'petty_cash',
            ]);
            // Create transaction


            DB::commit();
            // After DB commit:
$voucherNumber = 'PETTY-' . now()->format('Ymd') . '-' . str_pad($pettyRequest->id, 5, '0', STR_PAD_LEFT);

Voucher::create([
    'voucher_number' => $voucherNumber,
    'type' => 'Petty cash',
    'reference_id' => $pettyRequest->id,
    'date' => now(),
    'amount' => $pettyRequest->amount,
    'payee_details' => User::find($pettyRequest->request_employee)->name,
    'description' => 'Request for ' . $pettyRequest->type->type,
    'account_id' => $Petty_Cash->id,
    'approved_by' => $user->id,
    'created_by' => $pettyRequest->created_by,
    'branch_id' => $pettyRequest->branch_id
]);

            $this->dispatch('show-success-alert', message: 'Request approved and funds deducted!');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('show-error-alert', message: 'Error: '.$e->getMessage());
        }
    }





    #[On('deleteRequest')]
    public function deleteRequest($requestId)
    {
        DB::beginTransaction();
        try {
            // Find the branch and delete it
            $request = PettyCashRequest::findOrFail($requestId);

            if ($request->status !== 'pending' && $request->status !== 'rejected') {
                $this->dispatch('show-error-alert', message: 'Only pending or rejected payments can be deleted.');
                return;
            }


            if ($request->attachments) {
                Storage::disk('public')->delete($request->attachments);
            }

            $request->delete();

            DB::commit();

            // Show success message
            $this->dispatch('show-success-alert', message: 'Request deleted!');
        } catch (\Exception $e) {
            DB::rollBack();
            // Show error message
            $this->dispatch('show-error-alert', message: 'Error deleting Request: ' . $e->getMessage());
        }
    }

    // public function deleteRequest($id)
    // {
    //     $request = PettyCashRequest::findOrFail($id);

    //     // Delete attachment

    //     $request->delete();
    //     $this->dispatch('show-success-alert', message: 'Request deleted!');
    // }

    public function render()
    {
        $requests = PettyCashRequest::query()
            ->with(['type', 'account', 'requestEmployee', 'creator', 'approver','voucher'])
            ->where('branch_id', auth()->user()->branch_id)
            ->when($this->search, function ($query) {
                $query->whereHas('requestEmployee', function ($q) {
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
            ->paginate(10);

        return view('livewire.account.petty-cash', [
            'requests' => $requests
        ]);
    }
}
