<div class="container-fluid py-4">
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-white">Groups Management</h6>
            @can('Create Groups')
            <button class="btn btn-light btn-sm fw-bold shadow" data-bs-toggle="modal" data-bs-target="#createGroupModal">
                <i class="fas fa-plus me-1"></i> Create Group
            </button>
            @endcan
        </div>

        <div class="card-body">
            <!-- Search & Filter -->
            <div class="row mb-3 g-2">
                <div class="col-md-6">
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light"><i class="fas fa-search"></i></span>
                        <input wire:model.live="search" type="text" class="form-control"
                            placeholder="Search group code...">
                    </div>
                </div>
                <div class="col-md-6" wire:ignore>
                    <select wire:model.live="centerFilter" id="centerSelects" class="form-select shadow-sm">
                        <option value="">All Centers</option>
                        @foreach ($centers as $center)
                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Group Code</th>
                            <th>Center</th>
                            <th>Members</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($groups as $group)
                            <tr class="shadow-sm">
                                <td class="fw-bold">{{ $group->group_code }}</td>
                                <td>{{ $group->center->name ?? 'Unassigned' }}</td>
                                <td>
                                    <span class="badge bg-info text-dark px-3 py-1">
                                        {{ $group->members_count }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $group->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $group->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <button wire:click="openGroupDetailsModal({{ $group->id }})"
                                            class="btn btn-sm btn-outline-info shadow-sm">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @can('Edit Groups')
                                        <button wire:click="openEditModal({{ $group->id }})"
                                            class="btn btn-sm btn-outline-warning shadow-sm">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @endcan

                                        @can('Delete Groups')
                                        <button wire:click="GroupDeleteConformation({{ $group->id }})"
                                            class="btn btn-sm btn-outline-danger shadow-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No Groups Found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            {{ $groups->links('livewire::bootstrap') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Create Group Modal -->
    <div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createGroupModalLabel">Create New Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('groups.group-add-component')
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Group Modal -->
    <div class="modal fade" id="editGroupModal" tabindex="-1" aria-labelledby="editGroupModalLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGroupModalLabel">Edit Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($selectedGroupId)
                        @livewire('groups.group-edit-component', ['groupId' => $selectedGroupId], key($selectedGroupId))
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Group Details Modal -->

    <div class="modal fade" id="groupDetailsModal" tabindex="-1" aria-labelledby="groupDetailsModalLabel"
        aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="groupDetailsModalLabel">
                        <i class="fas fa-users"></i> Group Details
                    </h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body p-3" style="max-height: 500px; overflow-y: auto;">
                    @if ($selectedGroupDetailsId)
                        @php
                            $group = App\Models\Group::with('branch', 'members.loans')->findOrFail(
                                $selectedGroupDetailsId,
                            );
                        @endphp

                        <div class="row">

                            <!-- Group Information Card -->
                            <div class="col-md-6">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-info-circle"></i> Group Information
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless mb-0">
                                            <tr>
                                                <th class="w-50 text-secondary">Group Code:</th>
                                                <td class="fw-bold">{{ $group->group_code }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary">Branch:</th>
                                                <td>
                                                    <span class="badge bg-info text-white">
                                                        {{ $group->branch->name ?? 'No Branch' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="text-secondary">Status:</th>
                                                <td>
                                                    @if($group->center)
                                                        <span class="badge bg-info">
                                                            <i class="fas fa-building me-1"></i>
                                                            {{ $group->center->code }} - {{ $group->center->name }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-times-circle me-1"></i>
                                                            No Center Assigned
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Member Loan Summary Card -->
                            <div class="col-md-6">
                                <div class="card shadow-sm border-0">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0"><i class="fas fa-hand-holding-usd"></i> Member Loan
                                            Summary</h6>
                                    </div>
                                    <div class="card-body p-0" style="max-height: 300px; overflow-y: auto;">
                                        <table class="table table-hover table-striped mb-0">
                                            <thead class="bg-secondary text-white">
                                                <tr>
                                                    <th>Member</th>
                                                    <th>Loan Amount</th>
                                                    <th>Outstanding</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($group->members as $member)
                                                <tr onclick="window.location='{{ optional($member->loans->first())->id ? route('loan.loanDetails', $member->loans->first()->id) : 'javascript:void(0)' }}'"
                                                    style="cursor: {{ $member->loans->first()?->id ? 'pointer' : 'default' }}"
                                                    class="{{ $member->loans->first()?->id ? 'hover-highlight' : '' }}">
                                                        <td> {{ Str::limit($member->full_name, 20) }}</td>
                                                        <td
                                                            class="text-center {{ !optional($member->loans->first())?->loan_amount ? 'text-muted fst-italic' : '' }}">
                                                            @if ($member->loans->first()?->loan_amount && $member->loans->first()?->customer_id)
                                                                {{ number_format($member->loans->first()->loan_amount, 2) }}
                                                            @else
                                                                Inactive
                                                            @endif
                                                        </td>

                                                        <td
                                                            class="{{ $member->loans->first()?->loanProgress?->balance ?? null ? 'fw-bold text-danger' : 'text-muted fst-italic' }}">
                                                            @php
                                                                // Get the first loan with progress
                                                                $loanWithProgress = $member->loans->first(function (
                                                                    $loan,
                                                                ) {
                                                                    return $loan->loanProgress &&
                                                                        $loan->loanProgress->balance !== null;
                                                                });
                                                            @endphp

                                                            @if ($loanWithProgress)
                                                                {{ number_format($loanWithProgress->loanProgress->balance, 2) }}
                                                            @else
                                                                Inactive
                                                            @endif
                                                        </td>

                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center text-muted">No
                                                            Members Found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- End Row -->
                    @endif
                </div> <!-- End Modal Body -->

            </div>
        </div>
    </div>
    <style>

.hover-highlight:hover {
    background-color: #f8f9fa;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
        </style>

</div>



<script>
    document.addEventListener('livewire:init', () => {
        let choices;

        // Initialize Choices.js
        const initChoices = () => {
            if (choices) {
                choices.destroy();
            }

            const element = document.getElementById('centerSelects');
            if (element) {
                choices = new Choices(element, {
                    searchEnabled: true,
                    searchPlaceholderValue: 'Search centers...',
                    removeItemButton: true,
                    shouldSort: false,
                    classNames: {
                        containerInner: 'choices__inner',
                        input: 'choices__input',
                    },
                });

                // Update Livewire when selection changes
                element.addEventListener('change', (e) => {
                    @this.set('centerFilter', e.target.value);
                });
            }
        };

        // Initial initialization
        initChoices();

        // Reinitialize when Livewire updates
        Livewire.hook('commit', ({
            component,
            commit,
            respond,
            succeed,
            fail
        }) => {
            succeed(({
                snapshot,
                effect
            }) => {
                if (component.id === @this.__instance.id) {
                    initChoices();
                }
            });
        });
    });






    window.addEventListener('showGroupDetails', event => {
        var modal = new bootstrap.Modal(document.getElementById('groupDetailsModal'));
        modal.show();
    });





    window.addEventListener('editGroup', event => {
        var modal = new bootstrap.Modal(document.getElementById('editGroupModal'));
        modal.show();
    });

    window.addEventListener('show-group-delete', event => {
        Swal.fire({
            icon: "question",
            title: "{{ __('Are you sure?') }}",
            showCancelButton: true,
            confirmButtonText: "{{ __('Delete') }}",
            cancelButtonText: "{{ __('Cancel') }}",
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteCo')
            }
        });
    });

    window.addEventListener('GroupDeleted', event => {
        Swal.fire(
            'Deleted!',
            "{{ __('Group has been deleted successfully.') }}",
            'success'
        )
    });
</script>
