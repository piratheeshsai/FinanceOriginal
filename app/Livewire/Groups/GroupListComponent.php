<?php

namespace App\Livewire\Groups;

use App\Models\Center;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithPagination;

class GroupListComponent extends Component
{
    use WithPagination;

    public $perPage = 10;
    public $delete_id;
    public $selectedGroupId;
    public $search = '';
    public $centerFilter = null;
    // Listener methods
    protected $listeners = [
        'resetGroupData',
        'deleteCo' => 'deleteGroup',
        'groupCreated' => '$refresh'
    ];

    public function openEditModal($groupId)
    {
        $this->selectedGroupId = $groupId;
        $this->dispatch('editGroup', $groupId);
    }

    public function GroupDeleteConformation($id)
    {
        $this->delete_id = $id;
        $this->dispatch('show-group-delete');
    }

    public function deleteGroup()
    {
        $group = Group::findOrFail($this->delete_id);
        $group->delete();

        $this->dispatch('GroupDeleted');
    }


    public $selectedGroupDetailsId;

public function openGroupDetailsModal($groupId)
{
    $this->selectedGroupDetailsId = $groupId;
    $this->dispatch('showGroupDetails', $groupId);
}






public function render()
{
    $user = Auth::user();

    // Base query for groups
    $query = Group::with('center')
        ->withCount('members')
        // Search by group code
        ->when($this->search, function ($query) {
            return $query->where('group_code', 'like', '%' . $this->search . '%');
        })
        // Filter by center
        ->when($this->centerFilter, function ($query) {
            return $query->where('center_id', $this->centerFilter);
        });

    // Permission-based filtering
    if (!$user->hasPermissionTo('View All Branch Groups')) {
        // Restrict to user's branch
        $query->where('branch_id', $user->branch_id);
    }

    // If user has a specific center, further filter
    if ($user->center_id) {
        $query->where('center_id', $user->center_id);
    }

    // Fetch groups with pagination
    $groups = $query->paginate($this->perPage);

    // Fetch centers based on permission
    if ($user->hasPermissionTo('view all branches')) {
        $centers = Center::all(); // Show all centers
    } else {
        $centers = Center::where('branch_id', $user->branch_id)->get(); // Only user's branch centers
    }

    return view('livewire.groups.group-list-component', [
        'groups' => $groups,
        'centers' => $centers,
    ]);
}

}
