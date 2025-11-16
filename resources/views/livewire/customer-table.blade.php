<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <!-- Header with Add Customer Button -->
                <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Customers Management</h5>
                    <a href="{{ route('customer.create') }}"
                        class="btn bg-gradient-primary btn-sm d-flex align-items-center">
                        <i class="fas fa-user-plus me-2"></i>
                        <span>Add Customer</span>
                    </a>
                </div>


                <div class="card-body pt-2 pb-0">
                    <div class="row g-3">
                        <!-- First row with filters -->
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap align-items-end gap-3">
                                <!-- Center Filter -->
                                <div class="flex-grow-1" style="min-width: 150px; max-width: 200px;">
                                    <label
                                        class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">Center</label>
                                    <select wire:model.live="selectedCenter" class="form-select form-select-sm">
                                        <option value="">All Centers</option>
                                        @foreach ($centers as $center)
                                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Customer Type Filter -->
                                <div class="flex-grow-1" style="min-width: 150px; max-width: 200px;">
                                    <label
                                        class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">Customer
                                        Type</label>
                                    <select wire:model.live="selectedType" class="form-select form-select-sm">
                                        <option value="">All Types</option>
                                        @foreach ($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Per Page Dropdown -->
                                <div style="width: 100px;">
                                    <label
                                        class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">Records</label>
                                    <select wire:model.live="perPage" class="form-select form-select-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>

                                <!-- Search Input -->
                                <div class="ms-auto" style="min-width: 200px; max-width: 300px;">
                                    <label
                                        class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">Search</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-search text-primary text-sm"></i>
                                        </span>
                                        <input wire:model.live.debounce.300ms="search" type="text"
                                            class="form-control form-control-sm ps-0 border-start-0"
                                            placeholder="Search name, NIC, number...">
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
                <div class="table-responsive p-0 mt-5">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-3">
                                    Customer
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">
                                    Type</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">
                                    NIC / ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 ps-2">
                                    Customer No.</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                    Status</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                    Added</th>
                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-9">
                                    Contact</th>
                                <th
                                    class="text-end text-uppercase text-secondary text-xxs font-weight-bolder opacity-9 pe-3">
                                    Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($customers as $customer)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="avatar-wrapper me-3">
                                                @if (optional($customer)->photo && file_exists(public_path('storage/' . $customer->photo)))
                                                    <img src="{{ asset('storage/' . $customer->photo) }}"
                                                        alt="{{ $customer->full_name ?? 'Customer' }}"
                                                        class="rounded-circle object-fit-cover" width="40"
                                                        height="40">
                                                @else
                                                    <div class="rounded-circle d-flex align-items-center justify-content-center bg-info text-white"
                                                        style="width: 40px; height: 40px; font-size: 14px;">
                                                        {{ strtoupper(substr($customer->full_name ?? 'C', 0, 1)) }}{{ strtoupper(substr(strrchr($customer->full_name ?? '', ' ') ?: '', 1, 1)) }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $customer->full_name }}</h6>
                                                <p class="text-xs text-secondary mb-0">
                                                    {{ $customer->center->name ?? 'No Center' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @forelse ($customer->types as $type)
                                                <span
                                                    class="badge badge-sm bg-gradient-secondary">{{ $type->name }}</span>
                                            @empty
                                                <span class="text-xs text-muted">Not assigned</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $customer->nic }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $customer->customer_no }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @php
                                            $loan = $customer->loans->first();
                                            $approval = $loan ? $loan->approval : null;
                                            $status = $approval ? $approval->status : null;

                                            $statusText = match ($status) {
                                                'Pending' => 'Pending',
                                                'Approved' => 'Approved',
                                                'Active' => 'Active',
                                                'Completed' => 'Complete',
                                                'Rejected' => 'Rejected',
                                                default => 'Inactive',
                                            };

                                            $badgeClass = match ($status) {
                                                'Pending' => 'bg-gradient-warning',
                                                'Active' => 'bg-gradient-success',
                                                'Completed' => 'bg-gradient-info',
                                                'Rejected' => 'bg-gradient-danger',
                                                default => 'bg-gradient-secondary',
                                            };
                                        @endphp

                                        <span class="badge badge-sm {{ $badgeClass }}">{{ $statusText }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ \Carbon\Carbon::parse($customer->created_at)->format('Y-m-d') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0">{{ $customer->customer_phone }}</p>
                                    </td>
                                    <td class="align-middle text-end pe-3">
                                        <div class="d-flex justify-content-end gap-1">
                                            <a href="{{ route('customer.show', $customer->id) }}"
                                                class="btn btn-link text-info p-1 m-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @can('Edit Customers')
                                            <a href="{{ route('customer.edit', $customer->id) }}"
                                                class="btn btn-link text-primary p-1 m-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Edit Customer">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            @endcan

                                            @php
                                                // Check if customer has active loan progress
                                                $hasActiveLoanProgress = $customer->loans()
                                                    ->whereHas('loanProgress', function($query) {
                                                        $query->where('status', 'Active');
                                                    })
                                                    ->exists();

                                                // Get counts for display
                                                $activeLoanCount = $customer->loans()
                                                    ->whereHas('loanProgress', function($query) {
                                                        $query->where('status', 'Active');
                                                    })
                                                    ->count();

                                                $totalLoanCount = $customer->loans()->count();
                                            @endphp

                                            @if(!$hasActiveLoanProgress)
                                                {{-- Allow deletion - no active loan progress --}}
                                                <button type="button"
                                                    class="btn btn-link text-danger p-1 m-0 delete-button"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Delete Customer" data-id="{{ $customer->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>

                                                <form id="deleteForm-{{ $customer->id }}"
                                                    action="{{ route('customer.destroy', $customer->id) }}"
                                                    method="POST" class="d-none">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            @else
                                                {{-- Block deletion - has active loan progress --}}
                                                <button type="button"
                                                    class="btn btn-link text-secondary p-1 m-0 cannot-delete-button"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Cannot delete - has active loan progress"
                                                    data-id="{{ $customer->id }}"
                                                    data-active-loans="{{ $activeLoanCount }}"
                                                    data-total-loans="{{ $totalLoanCount }}">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="d-flex flex-column align-items-center">
                                            <i class="fas fa-user-slash fa-2x text-secondary mb-2"></i>
                                            <h6 class="text-secondary">No customers found</h6>
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
                            Showing <span class="font-weight-bold">{{ $customers->firstItem() ?? 0 }}</span>
                            to <span class="font-weight-bold">{{ $customers->lastItem() ?? 0 }}</span>
                            of <span class="font-weight-bold">{{ $customers->total() }}</span> entries
                        </p>
                        <div>
                            {{ $customers->links('livewire::bootstrap') }}
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

    // Handle delete button clicks (for allowed deletions)
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const customerId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This customer record will be permanently deleted. This action cannot be undone.",
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
                    document.getElementById('deleteForm-' + customerId).submit();

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
    });

    // Handle cannot delete button clicks (for blocked deletions)
    document.querySelectorAll('.cannot-delete-button').forEach(button => {
        button.addEventListener('click', function() {
            const activeLoans = this.getAttribute('data-active-loans');
            const totalLoans = this.getAttribute('data-total-loans');

            Swal.fire({
                title: 'Cannot Delete Customer',
                html: `
                    <div class="text-start">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>This customer has active loan progress and cannot be deleted.</strong>
                        </div>

                        <h6 class="mt-3">Loan Information:</h6>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-play-circle text-danger me-2"></i><strong>${activeLoans}</strong> loans with active progress</li>
                            <li><i class="fas fa-money-bill text-info me-2"></i><strong>${totalLoans}</strong> total loans</li>
                        </ul>

                        <div class="mt-3">
                            <h6>To delete this customer:</h6>
                            <ol class="text-muted small">
                                <li>Complete all active loan progress (set status to 'Complete')</li>
                                <li>Ensure all loan balances are fully paid</li>
                                <li>Then deletion will be allowed</li>
                            </ol>
                        </div>
                    </div>
                `,
                icon: 'warning',
                confirmButtonText: 'Understood',
                confirmButtonColor: '#3085d6',
                width: '500px'
            });
        });
    });
});
</script>
