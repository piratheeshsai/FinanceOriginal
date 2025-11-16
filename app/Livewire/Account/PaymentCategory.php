<?php

namespace App\Livewire\Account;

use App\Models\PaymentCategory as ModelsPaymentCategory;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PaymentCategory extends Component
{


    use WithPagination;
    public $editingId = null;
    public $search = '';
    public $isEditing = false;
    public $name;





    protected $rules = [
        'name' => 'required|string|max:255'
    ];

    public function save()
    {
        $this->validate();

    DB::beginTransaction();

    try {
        if ($this->isEditing) {

            ModelsPaymentCategory::find($this->editingId)->update([
                'name' => $this->name
            ]);

            $this->dispatch('show-success-alert', message: 'Type Updated successfully.');
        } else {
            ModelsPaymentCategory::create([
                'name' => $this->name
            ]);
            $this->dispatch('show-success-alert', message: 'Type Created successfully.');
        }

        DB::commit();

        $this->reset(['name', 'editingId', 'isEditing']);
    } catch (\Exception $e) {
        DB::rollBack();
        $this->dispatch('show-error-alert', message: 'Error Type: ' . $e->getMessage());
        throw $e;
    }
    }

    public function edit($id)
    {
        $name = ModelsPaymentCategory::find($id);
        $this->name = $name->name;
        $this->editingId = $id;
        $this->isEditing = true;
    }


    public function cancelEdit()
    {
        $this->reset(['name', 'editingId', 'isEditing']);
    }



    public function delete($id)
    {
        ModelsPaymentCategory::find($id)->delete();

        $this->dispatch('show-success-alert', message: 'Type Deleted successfully.');
    }

    public function render()
    {

        $Categories = ModelsPaymentCategory::when($this->search, function($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate(10);

        return view('livewire.account.payment-category',[ 'Categories' => $Categories]);
    }
}
