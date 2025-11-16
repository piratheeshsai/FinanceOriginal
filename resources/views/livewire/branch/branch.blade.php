<div class="container-fluid py-4">
    <div class="row">
        <!-- Branches Section -->
        <div class="col-12 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-white mb-0">Branch Management</h5>
                        @can('branch Create')
                            <button wire:click="openCreateBranchModal" class="btn btn-light btn-sm">
                                <i class="fas fa-plus me-1"></i>New Branch
                            </button>
                        @endcan
                    </div>
                </div>

                <!-- Branches List -->
                <div class="card-body p-0">
                    <div class="accordion" id="branchAccordion">
                        @forelse ($branches as $branch)
                            <div class="accordion-item border-0" id="branch-{{ $branch->id }}">
                                <h2 class="accordion-header" id="heading-{{ $branch->id }}">
                                    <button class="accordion-button p-3 @if($selectedBranch && $selectedBranch->id == $branch->id) bg-light @else collapsed @endif"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $branch->id }}"
                                            wire:click="fetchCenters({{ $branch->id }}, '{{ $branch->name }}')"
                                            aria-expanded="@if($selectedBranch && $selectedBranch->id == $branch->id) true @else false @endif">
                                        <div class="d-flex align-items-center justify-content-between w-100">
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $branch->name }}</h6>
                                                <small class="text-muted">{{ $branch->email }}</small>
                                            </div>
                                            <div class="d-none d-md-flex align-items-center text-sm text-muted">
                                                <i class="fas fa-phone-alt me-1"></i> {{ $branch->phone }}
                                            </div>
                                        </div>
                                    </button>
                                </h2>

                                <div id="collapse-{{ $branch->id }}" class="accordion-collapse collapse @if($selectedBranch && $selectedBranch->id == $branch->id) show @endif"
                                     aria-labelledby="heading-{{ $branch->id }}" data-bs-parent="#branchAccordion">
                                    <div class="accordion-body p-3 bg-light">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="d-flex flex-column h-100">
                                                    <div class="mb-3">
                                                        <h6 class="text-dark mb-2">Branch Details</h6>
                                                        <div class="row g-3">
                                                            <div class="col-md-6">
                                                                <div class="p-3 bg-white rounded shadow-sm">
                                                                    <small class="text-muted d-block">Email Address</small>
                                                                    <p class="mb-0">{{ $branch->email }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="p-3 bg-white rounded shadow-sm">
                                                                    <small class="text-muted d-block">Phone Number</small>
                                                                    <p class="mb-0">{{ $branch->phone }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="p-3 bg-white rounded shadow-sm">
                                                                    <small class="text-muted d-block">Created By</small>
                                                                    <p class="mb-0">{{ $branch->creator->name ?? 'Unknown' }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="p-3 bg-white rounded shadow-sm">
                                                                    <small class="text-muted d-block">Created Date</small>
                                                                    <p class="mb-0">{{ $branch->created_at }}</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="d-flex flex-column h-100">
                                                    <div class="mb-3">
                                                        <h6 class="text-dark mb-2">Actions</h6>
                                                        <div class="d-grid gap-2">
                                                            @can('branch Update')
                                                                <button wire:click="openEditModal({{ $branch->id }})"
                                                                        class="btn btn-outline-primary w-100 text-start">
                                                                    <i class="fas fa-pencil-alt me-2"></i>Edit Branch
                                                                </button>
                                                            @endcan

                                                            @can('Branch Fund Allocation')
                                                                <button wire:click="openAddFundsModal({{ $branch->id }})"
                                                                        class="btn btn-outline-success w-100 text-start">
                                                                    <i class="fas fa-coins me-2"></i>Add Funds
                                                                </button>
                                                            @endcan

                                                            @can('branch Delete')
                                                                <button onclick="confirmAction({
                                                                        title: 'Delete Branch?',
                                                                        text: 'Are you sure you want to delete this branch?',
                                                                        icon: 'warning',
                                                                        confirmButtonText: 'Yes, delete it!'
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                            Livewire.dispatch('deleteBranch', { branchId: {{ $branch->id }} });
                                                                        }
                                                                    })"
                                                                    class="btn btn-outline-danger w-100 text-start">
                                                                    <i class="far fa-trash-alt me-2"></i>Delete Branch
                                                                </button>
                                                            @endcan
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-building fa-3x text-muted"></i>
                                </div>
                                <h6 class="text-muted">No branches available</h6>
                                @can('branch Create')
                                    <button wire:click="openCreateBranchModal" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i>Add Your First Branch
                                    </button>
                                @endcan
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Centers Section -->
        @if($selectedBranch)
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-info p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="text-white mb-0">
                            Centers for {{ $selectedBranch->name }}
                        </h5>
                        <button wire:click="openCreateCenterModal({{ $selectedBranch->id }})"
                                class="btn btn-light btn-sm">
                            <i class="fas fa-plus me-1"></i>New Center
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    @if(count($centers) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Name</th>
                                        <th>Center Number</th>
                                        <th class="text-end pe-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($centers as $center)
                                        <tr>
                                            <td class="ps-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-box bg-info text-white rounded-circle p-2 me-2">
                                                        <i class="fas fa-home"></i>
                                                    </div>
                                                    {{ $center->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">{{ $center->center_code }}</span>
                                            </td>
                                            <td class="text-end pe-3">
                                                <div class="btn-group" role="group">
                                                    <button wire:click="openEditCenterModal({{ $center->id }})"
                                                            class="btn btn-sm btn-outline-dark">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </button>
                                                    <button onclick="confirmAction({
                                                            title: 'Delete Center?',
                                                            text: 'Are you sure you want to delete this center?',
                                                            icon: 'warning',
                                                            confirmButtonText: 'Yes, delete it!'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                Livewire.dispatch('delete-center', { centerId: {{ $center->id }} });
                                                            }
                                                        })"
                                                        class="btn btn-sm btn-outline-danger">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-home fa-3x text-muted"></i>
                            </div>
                            <h6 class="text-muted">No centers available for this branch</h6>
                            <button wire:click="openCreateCenterModal({{ $selectedBranch->id }})"
                                    class="btn btn-info btn-sm mt-2">
                                <i class="fas fa-plus me-1"></i>Add Your First Center
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>




    @if ($showAddFundsModal)
<!-- Bootstrap Modal -->
<div class="modal fade show" tabindex="-1" aria-labelledby="addFundsModalLabel"
     style="display: block; background-color: rgba(0, 0, 0, 0.5)"
     wire:ignore.self>
    <div class="modal-dialog">
        <div class="modal-content">
            <form wire:submit.prevent="addFundProcess">
                <div class="modal-header">
                    <h5 class="modal-title" id="addFundsModalLabel">
                        Transfer Funds to Branch
                    </h5>
                    <button type="button" class="btn-close"
                            wire:click="closeModal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">
                            Available in Main Bank: {{ number_format($mainBankBalance, 2) }}
                        </label>
                        <input type="number"
                               class="form-control"
                               wire:model.defer="amount"
                               placeholder="Enter transfer amount"
                               step="1">
                        @error('amount')
                            <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            wire:click="closeModal">Cancel</button>
                    <button type="submit"
                            class="btn btn-primary">
                        <span wire:loading.remove>Transfer Funds</span>
                        <span wire:loading>Processing...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif





    <div wire:ignore.self class="modal fade mt-6" id="createBranchModal" tabindex="-1"
        aria-labelledby="createBranchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createBranchModalLabel">
                        {{ $isEditing ? 'Edit Branch' : 'Create New Branch' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="store">
                        @csrf
                        <div class="mb-3">
                            <label for="branchName" class="form-label">Branch Name</label>
                            <input type="text" class="form-control" id="branchName" wire:model="branchName" required>
                            @error('branchName')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="branchEmail" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="branchEmail" wire:model="branchEmail"
                                required>
                            @error('branchEmail')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="branchPhone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="branchPhone" wire:model="branchPhone"
                                required>
                            @error('branchPhone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="Initial balance" class="form-label">Initial Balance</label>
                            <input type="number" class="form-control" id="initial_balance" wire:model="initial_balance"
                                required>
                            @error('initial_balance')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn bg-gradient-dark" wire:loading.attr="disabled">
                            {{ $isEditing ? 'Update' : 'Submit' }}
                            <span wire:loading>{{ $isEditing ? 'Updating...' : 'Submitting...' }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade mt-6" id="createCenterModal" tabindex="-1" aria-labelledby="createCenterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCenterModalLabel">
                        {{ $isCenterEditing ? 'Edit Center' : 'Create New Center' }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="storeCenter">
                        @csrf

                        <div class="mb-3">
                            <label for="centerName" class="form-label">Center Name</label>
                            <input type="text" class="form-control" id="centerName" wire:model="centerName"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="centerBranchId" class="form-label">Select Branch</label>
                            <select class="form-select" id="centerBranchId" wire:model="centerBranchId" required>
                                <option value="">Select a Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn bg-gradient-dark">
                            {{ $isCenterEditing ? 'Update' : 'Submit' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>









</div>

<script>
    window.addEventListener('show-branch-modal', () => {
        const modal = new bootstrap.Modal(document.getElementById('createBranchModal'));
        modal.show();
    });

    window.addEventListener('hide-modal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('createBranchModal'));
        modal.hide();
    });



    window.addEventListener('show-create-center-modal', () => {
        const modal = new bootstrap.Modal(document.getElementById('createCenterModal'));
        modal.show();
    });

    window.addEventListener('hide-create-center-modal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('createCenterModal'));
        modal.hide();
    });




    document.addEventListener('DOMContentLoaded', function() {
        // Ensure Livewire is available
        if (window.Livewire) {
            Livewire.on('show-success-alert', (data) => {
                console.log('Success alert triggered:', data);
                showSuccessAlert(data);
            });
            Livewire.on('show-error-alert', (data) => {
                showErrorAlert(data);
            });
        }
    });

document.addEventListener('livewire:load', function() {
    Livewire.on('remove-backdrop', () => {
        document.body.classList.remove('modal-open');
        const backdrops = document.getElementsByClassName('modal-backdrop');
        while(backdrops.length > 0) {
            backdrops[0].parentNode.removeChild(backdrops[0]);
        }
    });
});

</script>
