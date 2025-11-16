<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <!-- Header with Add Loan Button -->
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Loans Management</h5>
                    @can('Create Loan')
                        <a href="{{ route('loan.create') }}" class="btn bg-gradient-primary btn-sm d-flex align-items-center">
                            <i class="fas fa-plus me-2"></i>
                            <span>New Loan</span>
                        </a>
                    @endcan
                </div>

                <!-- Filter Section - Improved with better widths -->
                <div class="card-body pt-2 pb-0">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap align-items-end gap-3">
                                <!-- Per Page Dropdown -->
                                <div style="min-width: 90px; max-width: 120px;">
                                    <label
                                        class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">Records</label>
                                    <select wire:model.live="perPage" class="form-select form-select-sm"
                                        style="height: 38px;">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="300">300</option>
                                    </select>
                                </div>

                                <!-- Center Filter -->
                                <div style="min-width: 200px; max-width: 280px; flex: 1;" wire:ignore>
                                    <label class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">Center</label>
                                    <select wire:model.live="centerId" id="centerSelect" class="form-select form-select-sm"
                                        style="height: 38px;">
                                        <option value="">All Centers</option>
                                        @foreach($centers as $center)
                                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Search Input -->
                                <div style="min-width: 250px; max-width: 300px; flex: 1.5;">
                                    <label
                                        class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">Search</label>
                                    <div class="input-group" style="height: 38px;">
                                        <span class="input-group-text bg-white border-end-0"
                                            style="height: 38px; display: flex; align-items: center;">
                                            <i class="fas fa-search text-primary text-sm"></i>
                                        </span>
                                        <input wire:model.live.debounce.300ms="search" type="text"
                                            class="form-control ps-2 border-start-0"
                                            style="height: 38px; font-size: 14px;"
                                            placeholder="Search name, NIC, loan number...">
                                    </div>
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
                    <span class="text-xs ms-2">Loading data...</span>
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
                                    Principal
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">
                                    Interest Rate
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">
                                    Payment Details
                                </th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                    Status
                                </th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                    Branch
                                </th>
                                <th
                                    class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 pe-3">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @php
                                $start = ($loans->currentPage() - 1) * $loans->perPage() + 1;
                            @endphp
                            @forelse ($loans as $index => $loan)
                                <tr>

                                    {{-- href="{{ route('loan.loanDetails', $loans->id) }}" --}}

                                    <td>
                                        <div class="d-flex px-2 py-1">


                                            <div
                                                class="avatar avatar-sm me-3 rounded-circle bg-gradient-info d-flex justify-content-center align-items-center">
                                                <i class="fas fa-file-invoice-dollar text-white"></i>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $loan->loan_number }}</h6>
                                                <p class="text-xs text-secondary mb-0">#{{ $start + $index }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if ($loan->customer)
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-0 text-sm">{{ $loan->customer->full_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $loan->customer->nic }}</p>
                                            </div>
                                        @else
                                            <p class="text-xs text-secondary mb-0">No customer found</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">
                                            {{ number_format($loan->loan_amount, 2) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bold mb-0">
                                            {{ $loan->loanScheme->interest_rate ?? '0' }}%</p>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="text-xs me-2">Total:</span>
                                                <span
                                                    class="text-sm font-weight-bold">{{ number_format($loan->loanProgress->total_amount ?? 0, 2) }}</span>
                                            </div>
                                            <div class="d-flex align-items-center mb-1">
                                                <span class="text-xs me-2">Paid:</span>
                                                <span
                                                    class="text-sm font-weight-bold text-success">{{ number_format($loan->loanProgress->total_paid_amount ?? 0, 2) }}</span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <span class="text-xs me-2">Balance:</span>
                                                <span
                                                    class="text-sm font-weight-bold text-danger">{{ number_format($loan->loanProgress->balance ?? 0, 2) }}</span>
                                            </div>
                                        </div>
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
                                                    'Pending' => 'Pending'  ,
                                                    'Active' => 'Active'    ,
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
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span
                                                class="text-sm font-weight-bold text-success">{{ $loan->center->branch->name }}</span>
                                            <span class="text-secondary text-xs font-weight-bold">
                                                {{ \Carbon\Carbon::parse($loan->created_at)->format('Y-m-d') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="align-middle text-end pe-3">
                                        <div class="d-flex justify-content-end gap-1">

                                            @if (in_array($loan->approval->status, ['Pending']))
                                                <span class="btn btn-link text-info p-1 m-0 disabled-link"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Details not available for {{ $loan->approval->status }} status">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            @else
                                                <a href="{{ route('loan.loanDetails', $loan->id) }}"
                                                    class="btn btn-link text-info p-1 m-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @endif


                                            @can('Edit Loans')
                                                <a href="{{ $loan->approval && $loan->approval->status === 'Active' ? '#' : route('loan.edit', $loan->id) }}"
                                                    class="btn btn-link text-primary p-1 m-0 {{ $loan->approval && $loan->approval->status === 'Active' ? 'disabled' : '' }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $loan->approval && $loan->approval->status === 'Active' ? 'Cannot edit active loan' : 'Edit Loan' }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endcan
                                            @can('Delete Loan')
                                                <button wire:click="LoanDeleteConformation({{ $loan->id }})"
                                                    class="btn btn-link text-danger p-1 m-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" title="Delete Loan">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-file-invoice-dollar fa-2x text-secondary mb-2"></i>
                                            <h6 class="text-secondary">No loans found</h6>
                                            <p class="text-xs text-muted">Try adjusting your search or filters</p>
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
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });

    window.addEventListener('show-loan-delete', event => {
        Swal.fire({
            title: 'Are you sure?',
            text: "This loan record will be permanently deleted. This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('deleteloan');

                // Show processing state
                Swal.fire({
                    title: 'Deleting...',
                    text: 'Please wait while we process your request.',
                    icon: 'info',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    });

    window.addEventListener('LoanDeleted', event => {
        Swal.fire(
            'Deleted!',
            "{{ __('Loan has been deleted successfully.') }}",
            'success'
        );
    });

    window.addEventListener('LoanDeletionFailed', event => {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: event.detail.message,
        });
    });

    // Choices.js for Center Select
    document.addEventListener('livewire:init', () => {
        let choices;

        // Initialize Choices.js
        const initChoices = () => {
            if (choices) {
                choices.destroy();
            }

            const element = document.getElementById('centerSelect');
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
                    @this.set('centerId', e.target.value);
                });
            }
        };

        // Initial initialization
        initChoices();

        // Reinitialize when Livewire updates
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                if (component.id === @this.__instance.id) {
                    initChoices();
                }
            });
        });
    });
</script>
