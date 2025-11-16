<?php

namespace App\Livewire\Account;

use App\Models\PettyCashType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ManageTypes extends Component
{


    use WithPagination;

    public $type;
    public $editingId = null;
    public $search = '';
    public $isEditing = false;

    protected $rules = [
        'type' => 'required|string|max:255'
    ];

    public function save()
    {
        $this->validate();

    DB::beginTransaction();

    try {
        if ($this->isEditing) {

            PettyCashType::find($this->editingId)->update([
                'type' => $this->type
            ]);

            $this->dispatch('show-success-alert', message: 'Type Updated successfully.');
        } else {
            PettyCashType::create([
                'type' => $this->type
            ]);
            $this->dispatch('show-success-alert', message: 'Type Created successfully.');
        }

        DB::commit();

        $this->reset(['type', 'editingId', 'isEditing']);
    } catch (\Exception $e) {
        DB::rollBack();
        $this->dispatch('show-error-alert', message: 'Error Type: ' . $e->getMessage());
        throw $e;
    }
    }

    public function edit($id)
    {
        $type = PettyCashType::find($id);
        $this->type = $type->type;
        $this->editingId = $id;
        $this->isEditing = true;
    }

    public function cancelEdit()
    {
        $this->reset(['type', 'editingId', 'isEditing']);
    }

    public function delete($id)
    {
        PettyCashType::find($id)->delete();

        $this->dispatch('show-success-alert', message: 'Type Deleted successfully.');
    }




    public function render()
    {

        $types = PettyCashType::when($this->search, function($query) {
            $query->where('type', 'like', '%' . $this->search . '%');
        })
        ->latest()
        ->paginate(30);

        return view('livewire.account.manage-types', [
            'types' => $types
        ]);
    }
}
