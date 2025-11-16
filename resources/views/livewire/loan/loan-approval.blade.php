<!-- resources/views/livewire/loan/loan-approval.blade.php -->
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <!-- Header with Title -->
            <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Loan Approval Management</h5>
                <span class="badge bg-gradient-warning">
                    {{ $loans->where('approval.status', 'Approved')->count() }} Approved Loans
                </span>
            </div>

            <!-- Filter Section -->
            <div class="card-body pt-2 pb-0">
                <div class="row g-3">
                    <div class="col-md-12">
                        <div class="d-flex flex-wrap align-items-end gap-3">

                            <!-- Per Page Dropdown -->
                            <div class="d-flex flex-column" style="width: 120px;">
                                <label class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">
                                    Records
                                </label>
                                <select wire:model.live="perPage" class="form-select form-select-sm">
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>

                            <!-- Status Filter (Optional) -->
                            <div class="d-flex flex-column flex-grow-1" style="min-width: 200px; max-width: 250px;">
                                <label class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">
                                    Status Filter
                                </label>
                                <select wire:model="statusFilter" class="form-select form-select-sm">
                                    <option value="">All Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>

                        </div>

                    </div>
                </div>
            </div>


            <!-- Loading Indicator -->
            <div wire:loading class="text-center py-2">
                <div class="spinner-border text-primary spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <span class="text-xs ms-2">Processing request...</span>
            </div>

            <!-- Table Section -->
            <div class="table-responsive p-0 mt-3">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-3">
                                Loan Details
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">
                                Customer
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">
                                Amount
                            </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">
                                Loan Scheme
                            </th>


                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">
                                Date
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                Status
                            </th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($loans as $loan)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">

                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $loan->loan_number }}</h6>

                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <h6 class="mb-0 text-sm">{{ $loan->customer->full_name }}</h6>
                                        <p class="text-xs text-secondary mb-0">{{ $loan->customer->nic }}</p>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ number_format($loan->loan_amount, 2) }}</p>
                                </td>

                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $loan->loanScheme->loan_name }}</p>
                                </td>

                                <td>
                                    <p class="text-sm font-weight-bold mb-0">{{ $loan->start_date->format('Y-m-d') }}</p>
                                </td>
                                <td class="align-middle text-center text-sm">
                                    @if ($loan->approval)
                                    @php
                                        $badgeClass = match ($loan->approval->status) {
                                            'Active' => 'bg-gradient-success',
                                            'Pending' => 'bg-gradient-warning',
                                            'Approved' => 'bg-gradient-warning',
                                            'Rejected' => 'bg-gradient-danger',
                                            'Completed' => 'bg-gradient-info',
                                            default => 'bg-gradient-secondary',
                                        };

                                        $statusText = match ($loan->approval->status) {
                                            'Approved' => 'Approved',
                                            'Rejected' => 'Rejected',
                                            'Pending' => 'Pending',
                                            'Active' => 'Active',
                                            'Completed' => 'Completed',
                                            default => $loan->approval->status,
                                        };
                                    @endphp
                                    <span
                                        class="badge badge-sm {{ $badgeClass }}">{{ $statusText }}</span>
                                @else
                                    <span class="badge badge-sm bg-gradient-secondary">No Approval</span>
                                @endif
                                </td>
                                <td class="align-middle text-center">
                                    @if (!$loan->approval || $loan->approval->status == 'Pending')
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-dark d-flex align-items-center"
                                                wire:click="approveLoan({{ $loan->id }})"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Approve Loan">
                                                <i class="fas fa-check me-1"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger d-flex align-items-center"
                                                wire:click="rejectLoan({{ $loan->id }})"
                                                data-bs-toggle="tooltip" data-bs-placement="top" title="Reject Loan">
                                                <i class="fas fa-times me-1"></i> Reject
                                            </button>
                                        </div>
                                    @elseif($loan->approval->status == 'Approved' && $loan->status != 'Active')

                                    <button type="button"
                                    class="btn btn-sm btn-danger d-flex align-items-center mx-auto"
                                    onclick="Swal.fire({
                                        title: '⚠️ <strong>Critical Action!</strong>',
                                        html: `This action will <b>permanently activate</b> the loan.<br><br>
                                            <span style='color: red; font-weight: bold;'>
                                            You will not be able to edit or delete it afterwards!
                                            </span>`,
                                        icon: 'warning',
                                        iconColor: '#e3342f',
                                        showCancelButton: true,
                                        confirmButtonColor: '#e3342f',
                                        cancelButtonColor: '#6c757d',
                                        confirmButtonText: 'Yes, Activate Loan',
                                        cancelButtonText: 'Cancel',
                                        reverseButtons: true,
                                        focusCancel: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            Livewire.dispatch('activateLoan', { loanId: {{ $loan->id }} });
                                        }
                                    })"
                                    data-bs-toggle="tooltip"
                                    data-bs-placement="top"
                                    title="Activate Loan">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Activate
                                </button>

                                    @else
                                        <span class="badge bg-gradient-secondary">{{ ucfirst($loan->status) }}</span>
                                    @endif
                                    @if ($loan->approval->status !== 'Pending' && $loan->voucher)
                                    <a href="{{ route('vouchers.show', $loan->voucher) }}" target="_blank"
                                        class="btn btn-sm btn-info mt-2">
                                        Voucher
                                    </a>
                                @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-clipboard-check fa-2x text-secondary mb-2"></i>
                                        <h6 class="text-secondary">No pending loans found</h6>
                                        <p class="text-xs text-muted">All loans have been processed</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer px-3 py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-xs text-secondary mb-0">
                        Showing <span class="font-weight-bold">{{ $loans->firstItem() ?? 0 }}</span>
                        to <span class="font-weight-bold">{{ $loans->lastItem() ?? 0 }}</span>
                        of <span class="font-weight-bold">{{ $loans->total() }}</span> entries
                    </p>
                    <div>
                        {{ $loans->links('livewire::bootstrap') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
<div wire:ignore.self class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger">
                <h5 class="modal-title text-white" id="rejectionModalLabel">
                    <i class="fas fa-times-circle me-2"></i>Reject Loan Application
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="submitRejectionReason">
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Reason for Rejection</label>
                        <textarea id="rejectionReason" wire:model="rejectionReason" class="form-control" required rows="4"
                                 placeholder="Please provide a detailed reason for rejecting this loan application..."></textarea>
                        @error('rejectionReason')
                            <div class="text-danger mt-1 text-xs">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger d-flex align-items-center">
                            <i class="fas fa-times-circle me-2"></i>Confirm Rejection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });

    window.addEventListener('open-rejection-modal', () => {
        const modal = new bootstrap.Modal(document.getElementById('rejectionModal'));
        modal.show();
    });

    window.addEventListener('close-rejection-modal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('rejectionModal'));
        modal.hide();
    });

    window.addEventListener('approved_message', event => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Loan Approved Successfully",
            text: "The loan has been approved and is now ready for activation.",
            showConfirmButton: false,
            timer: 3000
        });
    });

    window.addEventListener('activated_message', event => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Loan Activated Successfully",
            text: "The loan has been activated and is now in effect.",
            showConfirmButton: false,
            timer: 3000
        });
    });

    window.addEventListener('rejected-message', event => {
        Swal.fire({
            position: "top-end",
            icon: "success",
            title: "Loan Rejected",
            text: "The loan application has been rejected with the provided reason.",
            showConfirmButton: false,
            timer: 3000
        });
    });
</script>
