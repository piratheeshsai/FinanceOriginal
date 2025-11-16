<div class="row">

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-1">
                    {{ $editMode ? 'Edit Cash Request' : 'Create New Cash Request' }}
                </h6>
            </div>
            <div class="card-body">
                <form wire:submit.prevent="submit" enctype="multipart/form-data">
                    <div class="row">
                        <!-- Request Type -->
                        <div class="col-md-7 mb-2">
                            <label class="form-label">Request Type</label>
                            <div class="d-flex gap-2">
                                <select wire:model="typeId" class="form-select">
                                    <option value="">Select Category</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('typeId')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-2 d-flex align-items-center justify-content-center">
                            <a href="{{ route('accounts.ManageTypes') }}"
                                class="text-info cursor-pointer font-weight-bold clickable-text">
                                Add / Edit
                            </a>
                        </div>
                    </div>

                    <!-- Amount -->
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" wire:model="amount" step="0.01" class="form-control">
                            @error('amount')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Request Employee -->
                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label">Request For Employee</label>
                            <select wire:model="requestEmployee" class="form-select">
                                <option value="">Select Employee</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                                @endforeach
                            </select>
                            @error('requestEmployee')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <!-- Attachments -->

                    <div class="row">
                        <div class="col-md-7 mb-3">
                            <label class="form-label">Attachments</label>

                            @if ($currentAttachment)
                                <div class="mb-2">
                                    <small class="text-muted">
                                        Current file:
                                        <a href="{{ Storage::url($currentAttachment) }}" target="_blank">
                                            {{ basename($currentAttachment) }}
                                        </a>
                                    </small>
                                </div>
                            @endif

                            <!-- Add wire:ignore to the file input -->
                            <input type="file" wire:model="attachments" class="form-control"
                                accept=".jpg,.jpeg,.png,.pdf" wire:ignore>

                            @error('attachments')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>



                    <div class="d-flex justify-content-end gap-2">
                        @if ($editMode)
                            <button type="button" class="btn btn-secondary" wire:click="cancelEdit">
                                Cancel
                            </button>
                        @endif
                        <button type="submit" class="btn btn-dark" wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                {{ $editMode ? 'Update Request' : 'Submit Request' }}
                            </span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm" role="status"></span>
                                Processing...
                            </span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <!-- Requests Table Card -->
    <div class="col-12">
        <div class="card mt-4">
            <div class="card-body">

                <div class="card card-background card-background-after-none align-items-start">
                    <div class="full-background"
                        style="background-image: linear-gradient(310deg, #141727 0%, #3a416f 100%)">
                    </div>
                    <div class="card-body text-start p-4 w-100">
                        <div class="row align-items-center">
                            <!-- Left Side: Branch Selection -->
                            <div class="col-md-3">
                                <h5 class="text-white">Recent Requests</h5>
                            </div>

                            <!-- Right Side: Date Selection -->
                            <div class="col-md-9 text-end">
                                <div class="row justify-content-end">
                                    <div class="col-lg-3 mb-2 mb-lg-0">
                                        <input type="text" wire:model.live="search" placeholder="Search employee..."
                                            class="form-control">
                                    </div>
                                    <div class="col-lg-3 mb-2 mb-lg-0">
                                        <select wire:model.live="statusFilter" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="pending">Pending</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-3">
                                        <input type="date" wire:model.live="dateFilter" class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-responsive mt-3">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Date</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Employee</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Purpose</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Amount</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Approve & Status</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Attachment</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">
                                            {{ $request->created_at->format('Y-m-d') }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">{{ $request->requestEmployee->name }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0"> {{ $request->type->type }}
                                        </p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0"> Rs.
                                            {{ number_format($request->amount, 2) }}
                                        </p>
                                    </td>


                                    <td class="align-middle">
                                        @if ($request->status === 'pending')
                                            @can('approve petty cash transfer')
                                                <div class="d-flex gap-2 align-items-center">
                                                    <button
                                                        onclick="confirmAction({
                                                            title: 'Approve Request?',
                                                            text: 'Are you sure you want to approve this request and deduct funds?',
                                                            icon: 'warning',
                                                            confirmButtonText: 'Yes, approve it!'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                Livewire.dispatch('approveRequest', { requestId: {{ $request->id }} });
                                                            }
                                                        })"
                                                        class="btn btn-sm btn-dark d-flex align-items-center gap-1 px-2"
                                                        wire:loading.attr="disabled"
                                                        wire:target="approveRequest({{ $request->id }})">
                                                        <span wire:loading.remove
                                                            wire:target="approveRequest({{ $request->id }})">
                                                            <i class="fas fa-check-circle"></i>
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="approveRequest({{ $request->id }})">
                                                            <span class="spinner-border spinner-border-sm"
                                                                role="status"></span>
                                                        </span>
                                                        Approve
                                                    </button>

                                                    <button wire:click="openRejectModal({{ $request->id }})"
                                                        class="btn btn-sm btn-danger px-3">
                                                        <i class="fas fa-times-circle"></i>
                                                        Reject
                                                    </button>
                                                </div>
                                            @else
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="badge bg-warning rounded-pill d-flex align-items-center">
                                                        <i class="fas fa-clock me-2"></i>
                                                        Pending Review
                                                    </span>
                                                </div>
                                            @endcan
                                        @else
                                            <div class="d-flex align-items-center gap-2">
                                                <span
                                                    class="badge rounded-pill d-flex align-items-center
                                                        {{ $request->status === 'approved' ? 'bg-success' : 'bg-danger' }}">
                                                    @if ($request->status === 'approved')
                                                        <i class="fas fa-check-circle me-2"></i>
                                                    @else
                                                        <i class="fas fa-times-circle me-2"></i>
                                                    @endif
                                                    {{ ucfirst($request->status) }}
                                                </span>

                                                @if ($request->status === 'rejected' && $request->rejection_reason)

                                                    <button class="btn btn-sm text-danger px-2"
                                                        data-bs-toggle="popover" data-bs-placement="top"
                                                        data-bs-trigger="hover" data-bs-title="Rejection Reason"
                                                        data-bs-content="{{ e($request->rejection_reason) }}">
                                                        <i class="fas fa-info-circle"></i>
                                                    </button>
                                                @endif

                                            </div>
                                        @endif
                                    </td>

                                    <td>
                                        @if ($request->attachments)
                                            <div class="d-flex gap-2">
                                                <a href="{{ Storage::url($request->attachments) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary  px-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ Storage::url($request->attachments) }}" download
                                                    class="btn btn-sm btn-outline-success px-2">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            @if($request->status === 'approved' && $request->voucher)
                                            <a href="{{ route('vouchers.show', $request->voucher) }}" target="_blank" class="btn btn-sm btn-info">
                                                View Voucher
                                            </a>
                                        @endif
                                        <a
                                        @if($request->status !== 'approved')
                                            wire:click="enterEditMode({{ $request->id }})"
                                        @endif
                                        class="btn btn-white px-2 {{ $request->status === 'approved' ? 'disabled pointer-events-none opacity-50' : '' }}"
                                    >
                                        <i class="fa-solid fa-pen-to-square fa-lg"></i> Edit
                                    </a>

                                            <a onclick="confirmAction({
                                                title: 'Delete Request?',
                                                text: 'Are you sure you want to delete this Request?',
                                                icon: 'warning',
                                                confirmButtonText: 'Yes, delete it!'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    Livewire.dispatch('deleteRequest', { requestId: {{ $request->id }} });
                                                }
                                            })"
                                                class="btn btn-white px-2">delete <i
                                                    class="fa-solid fa-trash fa-lg"></i>

                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $requests->links('livewire::bootstrap') }}

                </div>
            </div>
        </div>
    </div>
    <!-- Put this at the end of your Livewire component template -->
    @if ($showRejectModal)
        <div class="modal fade show" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Request</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea wire:model="rejectReason" class="form-control" placeholder="Enter rejection reason..." rows="4"></textarea>
                        @error('rejectReason')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="rejectRequest">
                            Submit Rejection
                            <span wire:loading wire:target="rejectRequest"
                                class="spinner-border spinner-border-sm"></span>
                        </button>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
    <style>
        body.modal-open {
            overflow: hidden;
        }

        .modal {
            background-color: rgba(0, 0, 0, 0.5);
        }
    </style>

</div>
<script>


    document.addEventListener("DOMContentLoaded", function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.forEach(function(popoverTriggerEl) {
            new bootstrap.Popover(popoverTriggerEl, {
                container: 'body' // Ensures it works inside tables or dynamic elements
            });
        });
    });

</script>
