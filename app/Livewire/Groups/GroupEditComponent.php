<?php

namespace App\Livewire\Groups;

use App\Models\Center;
use App\Models\Customer;
use App\Models\Group;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class GroupEditComponent extends Component
{
    public $group_leader = '';
    public $groupCode;
    public $centers;
    public $selectedCenter;
    public $selectedMembers = [];
    public $allCustomers = [];

    public $group_code;
    public $members = [];
    public $status;


    public $group;
    public $groupId;
    public $groupName;

    public function setSelectedMembers($members)
    {
        $this->selectedMembers = $members;
    }


    public function mount($groupId)
    {
        $this->groupId = $groupId;

        // Log::info('Received groupId: ' . $groupId);

        // Find the specific group by groupId
        $this->group = Group::find($groupId);

        // Check if the group exists
        if (!$this->group) {
            // Handle the case when the group is not found (optional)
            abort(404, 'Group not found');
        }

        // Preload related data
        $this->centers = Center::all();  // Assuming you need all centers

        $this->allCustomers = Customer::all();
        $this->selectedMembers = $this->group->members->pluck('id')->toArray();
        $this->selectedCenter = $this->group->center_id;
        $this->group_leader = $this->group->group_leader;
        $this->groupCode = $this->group->group_code;

    }
    


    public function updateGroup()
    {
        // Validation rules
        $this->validate([
            'groupCode' => 'required|string|max:255',
            'selectedCenter' => 'required|exists:centers,id',
            'selectedMembers' => 'required|array|min:3|max:5',
            'selectedMembers.*' => 'exists:customers,id',
            'group_leader' => [
                'required',
                'exists:customers,id',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, $this->selectedMembers)) {
                        $fail('The group leader must be one of the selected group members.');
                    }
                },
            ],
        ]);

        // Find the group and update its details
        $group = Group::find($this->groupId);

        if (!$group) {
            session()->flash('error', 'Group not found.');
            return;
        }

        // Update group details
        $group->update([
            'group_code' => $this->groupCode,
            'center_id' => $this->selectedCenter,
            'group_leader' => $this->group_leader,
        ]);

        // Sync group members
        $group->members()->sync($this->selectedMembers);

        // Flash success message and close modal
        if ($group) {

            session()->flash('success', 'Group Updated successfully!');
        } else {
            session()->flash('error', 'Failed to update group. Please try again.');
        }
        $this->dispatch('successMessage', 'Group updated successfully!');
        $this->dispatch('closeModal');
        // Assuming you have this to close the modal
    }


    public function render()
    {

        return view('livewire.groups.group-edit-component');
    }
}
