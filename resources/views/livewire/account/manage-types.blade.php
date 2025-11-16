<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->
            <div class="card bg-gradient-dark">
                <div class="card-body p-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h6 class="text-white mb-0">Manage Petty Cash Types</h6>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('accounts.PettyCash') }}" class="btn btn-light btn-sm">
                                Back to Requests
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $isEditing ? 'Edit Type' : 'Add New Type' }}</h5>
                    <form wire:submit.prevent="save">
                        <div class="row g-3 align-items-center">
                            <div class="col-md-8">
                                <input type="text"
                                       wire:model="type"
                                       placeholder="Enter type name"
                                       class="form-control @error('type') is-invalid @enderror">
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        {{ $isEditing ? 'Update' : 'Save' }}
                                    </button>
                                    @if ($isEditing)
                                        <button type="button" wire:click="cancelEdit" class="btn btn-secondary">
                                            Cancel
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card mt-4">
                <div class="card-header p-4 bg-gradient-dark">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="text-white mb-0">Type List</h5>
                        </div>
                        <div class="col-auto">
                            <div class="input-group input-group-outline">
                                <input type="text"
                                       wire:model.live="search"
                                       placeholder="Search types..."
                                       class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">#</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder">Type Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($types as $type)
                                    <tr>
                                        <td class="ps-3">
                                            <span class="text-xs font-weight-bold">{{ $loop->iteration }}</span>
                                        </td>
                                        <td>
                                            <span class="text-xs font-weight-bold">{{ $type->type }}</span>
                                        </td>
                                        <td class="text-end pe-3">
                                            <button wire:click="edit({{ $type->id }})" class="btn btn-link text-dark px-2">
                                                <i class="fa-solid fa-pen-to-square fa-sm"></i>
                                            </button>
                                            <button wire:click="delete({{ $type->id }})" class="btn btn-link text-danger px-2">
                                                <i class="fa-solid fa-trash fa-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">No types found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer px-4">
                        {{ $types->links('livewire::bootstrap') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
