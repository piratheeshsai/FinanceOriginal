<?php

namespace App\Livewire\Groups;

use App\Models\Center;
use App\Models\Customer;
use App\Models\Group;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class GroupAddComponent extends Component
{
    // Properties
    public $centers = [];
    public $selectedCenter = '';
    public $groupCode = '';
    public $group_leader = '';
    public $editing = false;
    public $allCustomers = [];
    public $selectedMembers = [];
    protected $listeners = ['updateSelectedCenter'];

    // Validation Rules
    protected $rules = [
        'selectedCenter' => 'required',
        'selectedMembers' => 'required|array|min:3|max:5',
        'group_leader' => 'required|in_array:selectedMembers.*',
    ];

    protected $messages = [
        'selectedCenter.required' => 'Please select a center.',
        'selectedMembers.required' => 'Please select group members.',
        'selectedMembers.min' => 'Select at least 3 members.',
        'selectedMembers.max' => 'You can select up to 5 members.',
        'group_leader.required' => 'Please select a group leader.',
        'group_leader.in_array' => 'The group leader must be one of the selected members.',
    ];

    public function mount()
    {
        // Preload centers and customers
        $this->centers = Center::all();
        $this->allCustomers = Customer::whereDoesntHave('groups')
        ->whereDoesntHave('loans', function ($query) {
            $query->whereHas('loanProgress', function ($subQuery) {
                $subQuery->where('status', 'active');
            });
        })
        ->get();
    }



public function saveGroup()
{
    try {
        // Validate form data
        $this->validate();

        // Validate selected center
        if (is_array($this->selectedCenter) && isset($this->selectedCenter['value'])) {
            $centerId = $this->selectedCenter['value'];
        } else {
            $this->dispatch('show-error-alert', message: 'No valid center selected.');
            return;
        }

        // Find center and validate
        $center = Center::find($centerId);
        if (!$center) {
            $this->dispatch('show-error-alert', message: 'Center not found.');
            return;
        }

        $branchId = $center->branch_id;

        // Generate unique group code
        $newGroupCode = Group::generateGroupCode($branchId, $centerId);

        // Create the group
        $group = Group::create([
            'creator_id' => auth()->id(),
            'branch_id' => $branchId,
            'center_id' => $centerId,
            'group_code' => $newGroupCode,
            'group_leader' => $this->group_leader,
        ]);

        // Attach members to the group
        if (!empty($this->selectedMembers)) {
            $group->members()->attach($this->selectedMembers);
        }

        // Reset form
        $this->resetGroupForm();

        // Dispatch success message and reload page
        $this->dispatch('show-success-alert', message: 'Group created successfully.');
        $this->dispatch('closeModal');
        $this->dispatch('reloadPage'); // Add this line

    } catch (\Illuminate\Database\QueryException $e) {
        \Log::error('Database error during group creation: ' . $e->getMessage());
        $this->dispatch('show-error-alert', message: 'Database error occurred. Please try again.');

    } catch (\InvalidArgumentException $e) {
        \Log::error('Validation error during group creation: ' . $e->getMessage());
        $this->dispatch('show-error-alert', message: 'Invalid data provided. Please check your inputs.');

    } catch (\Exception $e) {
        \Log::error('Group creation failed: ' . $e->getMessage(), [
            'user_id' => auth()->id(),
            'center_id' => $centerId ?? null,
            'error' => $e->getTraceAsString()
        ]);

        $this->dispatch('show-error-alert', message: 'Failed to create group. Please try again.');
    }
}

// Add this new method to refresh available customers
public function refreshAvailableCustomers()
{
    $this->allCustomers = Customer::whereDoesntHave('groups')
        ->whereDoesntHave('loans', function ($query) {
            $query->whereHas('loanProgress', function ($subQuery) {
                $subQuery->where('status', 'active');
            });
        })
        ->get();
}
    public function resetGroupForm()
    {
        $this->selectedCenter = '';
        $this->groupCode = '';
        $this->group_leader = '';
        $this->selectedMembers = [];
    }

    public function render()
    {
        return view('livewire.groups.group-add-component');
    }
}
