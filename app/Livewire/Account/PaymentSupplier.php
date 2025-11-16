<?php

namespace App\Livewire\Account;

use App\Models\PaymentCategory;
use App\Models\PaymentSupplier as ModelsPaymentSupplier;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentSupplier extends Component
{
    use WithPagination;
    public $editingId = null;
    public $search = '';
    public $name;
    public $nic;
    public $salary;
    public $bank_account_name;
    public $bank_account_number;
    public $isEditing = false;
    public $isSalaryCategory = false;
    public $supplierId;
    public $payment_category_id;

    protected $rules = [
        'payment_category_id' => 'required',
        'name' => 'required|string|max:255',
        'nic' => 'nullable|string|max:255',
        'salary' => 'nullable|numeric',
        'bank_account_name' => 'nullable|string|max:255',
        'bank_account_number' => 'nullable|string|max:255',
    ];


    public function save()
    {
        $this->validate();

    DB::beginTransaction();

    try {
        if ($this->isEditing) {

            ModelsPaymentSupplier::find($this->editingId)->update([
                'name' => $this->name,
                'payment_category_id' => $this->payment_category_id,
                'nic' => $this->nic,
                'salary' => $this->salary,
                'bank_account_name' => $this->bank_account_name,
                'bank_account_number' => $this->bank_account_number,
            ]);

            $this->dispatch('show-success-alert', message: 'Supplier Updated successfully.');
        } else {

            ModelsPaymentSupplier::create([
                'name' => $this->name,
                'payment_category_id' => $this->payment_category_id,
                'nic' => $this->nic,
                 'salary' => $this->salary,
                'bank_account_name' => $this->bank_account_name,
                'bank_account_number' => $this->bank_account_number,
            ]);

            $this->dispatch('show-success-alert', message: 'Supplier Created successfully.');
        }

        DB::commit();

        $this->reset(['name', 'editingId', 'isEditing']);
    } catch (\Exception $e) {
        DB::rollBack();
        $this->dispatch('show-error-alert', message: 'Error Supplier: ' . $e->getMessage());
        throw $e;
    }
    }

    public function edit($id)
    {
        $Supplier = ModelsPaymentSupplier::find($id);
        $this->name = $Supplier->name;
        $this->payment_category_id = $Supplier->payment_category_id;
        $this->nic = $Supplier->nic;
        $this->salary = $Supplier->salary;
        $this->bank_account_name = $Supplier->bank_account_name;
        $this->bank_account_number = $Supplier->bank_account_number;
        $this->editingId = $id;
        $this->isEditing = true;
    }

    public function cancelEdit()
    {
        $this->reset([
        'name',
        'payment_category_id',
        'nic',
        'salary',
        'bank_account_name',
        'bank_account_number',
        'editingId',
        'isEditing']);
    }

    public function delete($id)
    {
        ModelsPaymentSupplier::find($id)->delete();

        $this->dispatch('show-success-alert', message: 'Supplier Deleted successfully.');
    }
    public function render()
    {


        $PaymentSuppliers = ModelsPaymentSupplier::when($this->search, function($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate(10);

        return view('livewire.account.payment-supplier',  [
            'paymentCategories' => PaymentCategory::all(),'PaymentSuppliers' => $PaymentSuppliers ]);
    }
}
