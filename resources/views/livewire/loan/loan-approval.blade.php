<!-- resources/views/livewire/loan/loan-approval.blade.php -->
<div class="container-fluid py-3">
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-navy text-white py-3 rounded-top-4 d-flex justify-content-between align-items-center">
            <h4 class="mb-0 fw-bold text-white">
                <i class="bi bi-person-check me-2"></i>Loan Approval Management
            </h4>
            <span class="badge bg-teal text-white rounded-pill px-3 py-2">
                {{ $loans->where('approval.status', 'Approved')->count() }} Approved Loans
            </span>
        </div>

        <!-- Filter Section -->
        <div class="card-body bg-light-teal border-bottom py-3 rounded-bottom-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label text-navy fw-semibold mb-1">Records</label>
                    <select wire:model.live="perPage" class="form-select form-select-sm border-teal">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label text-navy fw-semibold mb-1">Status Filter</label>
                    <select wire:model.live="statusFilter" class="form-select form-select-sm border-teal">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Approved">Approved</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div wire:loading class="text-center py-2">
            <div class="spinner-border text-teal spinner-border-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <span class="text-xs ms-2">Processing request...</span>
        </div>

        <!-- Table Section -->
        <div class="table-responsive" style="border-radius: 1rem; background: #fff; padding: 1rem;">
            <table class="table table-hover table-sm align-middle mb-0">
                <thead class="table-navy text-white">
                    <tr>
                        <th style="width: 110px;">Loan No</th>
                        <th style="max-width: 180px;">Customer</th>
                        <th style="width: 110px;">Amount</th>
                        <th style="max-width: 160px;">Loan Scheme</th>
                        <th style="width: 110px;">Date</th>
                        <th class="text-center" style="width: 100px;">Status</th>
                        <th class="text-center" style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($loans as $loan)
                        <tr>
                            <td class="fw-bold text-teal">{{ $loan->loan_number }}</td>
                            <td style="max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <span class="fw-semibold text-truncate" title="{{ $loan->customer->full_name }}">
                                    {{ $loan->customer->full_name }}
                                </span>
                                <br>
                                <small class="text-muted text-truncate">{{ $loan->customer->nic }}</small>
                            </td>
                            <td>{{ number_format($loan->loan_amount, 2) }}</td>
                            <td style="max-width: 160px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                <span class="text-truncate" title="{{ $loan->loanScheme->loan_name }}">
                                    {{ $loan->loanScheme->loan_name }}
                                </span>
                            </td>
                            <td>{{ $loan->start_date->format('Y-m-d') }}</td>
                            <td class="text-center">
                                @if ($loan->approval)
                                    @php
                                        $badgeClass = match ($loan->approval->status) {
                                            'Active' => 'bg-success text-white',
                                            'Pending' => 'bg-warning text-dark',
                                            'Approved' => 'bg-teal text-white',
                                            'Rejected' => 'bg-danger text-white',
                                            'Completed' => 'bg-info text-dark',
                                            default => 'bg-secondary text-white',
                                        };
                                    @endphp
                                    <span class="badge rounded-pill {{ $badgeClass }}">
                                        {{ $loan->approval->status }}
                                    </span>
                                @else
                                    <span class="badge rounded-pill bg-secondary text-white">No Approval</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="btn btn-teal btn-sm rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        @if($loan->approval && $loan->approval->status == 'Approved' && $loan->status != 'Active')
                                            <li>
                                                <a class="dropdown-item text-warning" href="#" onclick="Swal.fire({
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
                                                })">
                                                    Activate Loan
                                                </a>
                                            </li>
                                        @endif
                                        @if ($loan->approval && $loan->approval->status !== 'Pending' && $loan->voucher)
                                            <li>
                                                <a class="dropdown-item text-info" href="{{ route('vouchers.show', $loan->voucher) }}" target="_blank">
                                                    View Voucher
                                                </a>
                                            </li>
                                        @endif
                                        @if (!$loan->approval || $loan->approval->status == 'Pending')
                                            <li>
                                                <a class="dropdown-item text-success" href="#" wire:click.prevent="approveLoan({{ $loan->id }})">
                                                    Approve Loan
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item text-danger" href="#" wire:click.prevent="rejectLoan({{ $loan->id }})">
                                                    Reject Loan
                                                </a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                <i class="fas fa-clipboard-check fa-2x mb-2"></i>
                                <div>No pending loans found</div>
                                <small>All loans have been processed</small>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="card-footer px-4 py-2 bg-light-navy rounded-bottom-4">
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Showing {{ $loans->firstItem() ?? 0 }} to {{ $loans->lastItem() ?? 0 }} of {{ $loans->total() }} entries
                </small>
                <div>
                    {{ $loans->links('livewire::bootstrap') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Rejection Modal -->
    <div wire:ignore.self class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="rejectionModalLabel">
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

    <style>
        .bg-navy { background-color: #23395d !important; }
        .text-navy { color: #23395d !important; }
        .bg-teal { background-color: #008080 !important; }
        .text-teal { color: #008080 !important; }
        .bg-light-teal { background-color: #e0f7fa !important; }
        .bg-light-navy { background-color: #e3eafc !important; }
        .table-navy th {
            background-color: #23395d !important;
            color: #fff !important;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            padding-top: 0.7em;
            padding-bottom: 0.7em;
            border-bottom: 2px solid #008080;
            text-align: center;
            vertical-align: middle;
        }
        .table th, .table td {
            font-size: 0.85rem !important;
            vertical-align: middle;
            padding-top: 0.4rem !important;
            padding-bottom: 0.4rem !important;
        }
        .table-responsive {
            border-radius: 1rem !important;
            background: #fff;
            padding: 1rem;
        }
        .card, .card-header, .card-footer {
            border-radius: 1rem !important;
        }
        .text-truncate {
            display: inline-block;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .badge {
            font-size: 0.8rem;
            padding: 0.35em 0.6em;
        }
        .btn-group-sm .btn,
        .btn-outline-teal,
        .btn-outline-navy,
        .btn-outline-danger,
        .btn-outline-info {
            padding: 0.25rem 0.6rem !important;
            font-size: 1rem !important;
            border-radius: 2rem !important;
            min-width: 32px;
            min-height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .btn-outline-teal {
            background-color: #008080;
            color: #fff;
            border: none;
        }
        .btn-outline-teal:hover {
            background-color: #006666;
            color: #fff;
        }
        .btn-outline-navy {
            background-color: #23395d;
            color: #fff;
            border: none;
        }
        .btn-outline-navy:hover {
            background-color: #1a2940;
            color: #fff;
        }
        .btn-outline-danger {
            background-color: #e3342f;
            color: #fff;
            border: none;
        }
        .btn-outline-danger:hover {
            background-color: #c82333;
            color: #fff;
        }
        .btn-outline-info {
            background-color: #38bdf8;
            color: #fff;
            border: none;
        }
        .btn-outline-info:hover {
            background-color: #0ea5e9;
            color: #fff;
        }
        .btn-success, .btn-danger, .btn-warning, .btn-info {
            color: #fff !important;
            font-size: 1rem !important;
            border: none !important;
            box-shadow: 0 2px 6px rgba(44,62,80,0.08);
        }
        .btn-success { background-color: #198754 !important; }
        .btn-danger { background-color: #dc3545 !important; }
        .btn-warning { background-color: #ffc107 !important; color: #23395d !important; }
        .btn-info { background-color: #0dcaf0 !important; }
        .btn-success:hover { background-color: #157347 !important; }
        .btn-danger:hover { background-color: #bb2d3b !important; }
        .btn-warning:hover { background-color: #e0a800 !important; color: #23395d !important; }
        .btn-info:hover { background-color: #31d2f2 !important; }
        .btn-teal {
            background-color: #008080 !important;
            color: #fff !important;
            border: none;
            border-radius: 2rem !important;
            font-weight: 600;
        }
    </style>




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
</div>
