<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                {{-- Flash Messages --}}
                <div class="card-header bg-transparent border-bottom">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- Page Title --}}
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pending Transfer Approvals</h5>
                    </div>
                </div>

                {{-- Search and Pagination Controls --}}
                <div class="card-header bg-light border-bottom py-3">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            {{-- <x-perPage :perPage="$perPage" class="form-select form-select-sm" /> --}}
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-search"></i></span>
                                <input
                                    type="text"
                                    class="form-control form-control-sm"
                                    placeholder="Search by description, amount, or creator..."
                                    wire:model.live="search"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Transfers Table --}}
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="bg-light text-muted">
                                <tr>
                                    <th class="text-uppercase text-xxs">#</th>
                                    <th class="text-uppercase text-xxs">Branch</th>
                                    <th class="text-uppercase text-xxs">From Staff</th>
                                    <th class="text-uppercase text-xxs">Amount</th>
                                    <th class="text-uppercase text-xxs">Remark</th>
                                    <th class="text-uppercase text-xxs">Date</th>
                                    <th class="text-uppercase text-xxs text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingTransfers as $index => $transfer)
                                    <tr>
                                        <td>
                                            <span class="text-sm text-muted">
                                                {{ ($pendingTransfers->currentPage() - 1) * $pendingTransfers->perPage() + $index + 1 }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-sm">{{ $transfer->branch->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-sm">{{ $transfer->createdBy->name }}</span>
                                        </td>
                                        <td>
                                            <span class="text-sm fw-bold text-success">
                                                {{ number_format($transfer->amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-sm text-muted">{{ $transfer->description }}</span>
                                        </td>
                                        <td>
                                            <span class="text-sm text-muted">
                                                {{ $transfer->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">

                                                <button
                                                    wire:click="approveTransfer({{ $transfer->id }})"
                                                    class="btn btn-success"
                                                    wire:loading.attr="disabled"
                                                >
                                                    <i class="bi bi-check-circle me-1"></i>Approve
                                                </button>
                                                <button
                                                    wire:click="openRejectModal({{ $transfer->id }})"
                                                    class="btn btn-danger"
                                                    wire:loading.attr="disabled"
                                                >
                                                    <i class="bi bi-x-circle me-1"></i>Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="alert alert-info mb-0">
                                                <i class="bi bi-info-circle me-2"></i>
                                                No pending transfers found.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Showing {{ $pendingTransfers->firstItem() }} to {{ $pendingTransfers->lastItem() }}
                            of {{ $pendingTransfers->total() }} entries
                        </small>
                        {{ $pendingTransfers->links('livewire::bootstrap') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reject Modal --}}
    @if ($showRejectModal)
        <div class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg">
                    <div class="modal-header bg-light">
                        <h5 class="modal-title">
                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                            Reject Transfer
                        </h5>
                        <button wire:click="closeRejectModal" type="button" class="btn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejectionReason" class="form-label">
                                Reason for Rejection (Optional)
                            </label>
                            <textarea
                                id="rejectionReason"
                                class="form-control"
                                wire:model="rejectionReason"
                                rows="4"
                                placeholder="Provide a reason for rejecting this transfer..."
                            ></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button
                            wire:click="closeRejectModal"
                            type="button"
                            class="btn btn-secondary"
                        >
                            Cancel
                        </button>
                        <button
                            wire:click="rejectTransfer({{ $selectedTransferId }}, rejectionReason)"
                            type="button"
                            class="btn btn-danger"
                        >
                            Confirm Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
