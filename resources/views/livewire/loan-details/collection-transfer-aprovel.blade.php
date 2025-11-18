<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <!-- Flash Messages -->
                <div class="card-header bg-navy text-white rounded-top-4">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show mb-2 text-white" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-2 text-white" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 fw-bold text-white">
                            <i class="bi bi-arrow-left-right me-2 text-white"></i>Pending Collection Transfers
                        </h4>
                    </div>
                </div>

                <!-- Search and Pagination Controls -->
                <div class="card-body bg-light-teal border-bottom py-3 rounded-bottom-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                            <div class="input-group input-group-sm shadow-sm">
                                <span class="input-group-text bg-white text-teal"><i class="bi bi-search"></i></span>
                                <input
                                    type="text"
                                    class="form-control border-start-0 border-teal"
                                    placeholder="Search by description, amount, or creator..."
                                    wire:model.debounce.500ms="search"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transfers Table -->
                <div class="card-body px-3 pt-0 pb-2">
                    <div class="table-responsive">
                        <table class="table table-hover table-sm align-middle mb-0">
                            <thead class="table-navy text-white">
                                <tr>
                                    <th>#</th>
                                    <th>Branch</th>
                                    <th>From Staff</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingTransfers as $index => $transfer)
                                    <tr class="{{ $loop->even ? 'bg-light-teal' : 'bg-light-navy' }}">
                                        <td>
                                            <span class="text-muted">
                                                {{ ($pendingTransfers->currentPage() - 1) * $pendingTransfers->perPage() + $index + 1 }}
                                            </span>
                                        </td>
                                        <td>
                                            <span>{{ $transfer->branch->name }}</span>
                                        </td>
                                        <td>
                                            <span>{{ $transfer->createdBy->name }}</span>
                                        </td>
                                        <td>
                                            <span class="fw-bold text-teal">
                                                {{ number_format($transfer->amount, 2) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-muted">
                                                {{ $transfer->created_at->format('d/m/Y H:i') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button
                                                    wire:click="approveTransfer({{ $transfer->id }})"
                                                    class="btn btn-outline-teal"
                                                    wire:loading.attr="disabled"
                                                >
                                                    <i class="bi bi-check-circle me-1"></i>Approve
                                                </button>
                                                <button
                                                    wire:click="openRejectModal({{ $transfer->id }})"
                                                    class="btn btn-outline-navy"
                                                    wire:loading.attr="disabled"
                                                >
                                                    <i class="bi bi-x-circle me-1"></i>Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
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

                    <!-- Pagination -->
                    <div class="card-footer bg-light-navy py-2 d-flex justify-content-between align-items-center rounded-bottom-4">
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

    <!-- Reject Modal -->
    @if ($showRejectModal)
        <div class="modal fade show" tabindex="-1" role="dialog" style="display: block;">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content shadow-lg rounded-4">
                    <div class="modal-header bg-light-navy">
                        <h5 class="modal-title text-navy">
                            <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                            Reject Transfer
                        </h5>
                        <button wire:click="closeRejectModal" type="button" class="btn-close"></button>
                    </div>
                    <div class="modal-body bg-light">
                        <div class="form-group">
                            <label for="rejectionReason" class="form-label text-navy">
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
                    <div class="modal-footer bg-light-navy">
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


<style>
.bg-navy { background-color: #23395d !important; }
.text-navy { color: #23395d !important; }
.bg-teal { background-color: #008080 !important; }
.text-teal { color: #008080 !important; }
.btn-teal, .btn-outline-teal { background-color: #008080; color: #fff; border-color: #008080; }
.btn-outline-teal:hover { background-color: #006666; color: #fff; }
.btn-outline-navy { background-color: #23395d; color: #fff; border-color: #23395d; }
.btn-outline-navy:hover { background-color: #1a2940; color: #fff; }
.bg-light-teal { background-color: #e0f7fa !important; }
.bg-light-navy { background-color: #e3eafc !important; }
.table-navy { background-color: #23395d !important; color: #fff; }
.table-navy th {
    background-color: #23395d !important;
    color: #fff !important;
    font-size: 1.05rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    padding-top: 0.85em;
    padding-bottom: 0.85em;
    border-bottom: 2px solid #008080;
    text-align: center;
    vertical-align: middle;
}
.border-teal { border-color: #008080 !important; }
.card {
    border-radius: 1rem;
}
.card-header {
    border-radius: 1rem 1rem 0 0;
}
.card-footer {
    border-radius: 0 0 1rem 1rem;
}
.table th, .table td {
    font-size: 0.95rem;
    vertical-align: middle;
}
.table th {
    cursor: pointer;
    user-select: none;
    font-weight: 600;
}
.table th:hover {
    background-color: #e0f7fa;
}
.badge {
    font-size: 0.8rem;
    padding: 0.35em 0.6em;
}
.btn-group-sm .btn {
    line-height: 1.2;
    padding: 0.25rem 0.5rem;
}
.fs-7 {
    font-size: 0.85rem;
}
.form-select-sm, .form-control-sm {
    font-size: 0.9rem;
}
.input-group-sm .input-group-text {
    font-size: 0.9rem;
}
.rounded-4 { border-radius: 1rem !important; }
</style>
</div>
