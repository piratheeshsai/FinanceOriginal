<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <!-- Header with Export Button -->
                <div class="card-header pb-0 d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Loan Receipts</h5>

                    <!-- Common Export Button -->
                    <button wire:click="ReceiptBulk" class="btn btn-outline-primary btn-sm"
                        wire:loading.attr="disabled" wire:target="ReceiptBulk">
                        <span wire:loading.remove wire:target="ReceiptBulk">
                            <i class="fas fa-file-pdf me-2"></i>Export Filtered Receipts
                        </span>
                        <span wire:loading wire:target="ReceiptBulk">
                            <i class="fas fa-spinner fa-spin me-2"></i>Generating Receipts...
                        </span>
                    </button>
                </div>

                <!-- Flash Messages -->
                @if (session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Filter Section - Improved -->
                <div class="card-body pt-2 pb-0">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap align-items-end gap-3">

                                <div style="width: 180px;">
                                    <label
                                        class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">Date</label>
                                    <input type="date" wire:model.live="dateFilter"
                                        class="form-control form-control-sm">
                                </div>

                                <!-- Per Page Dropdown -->
                                <div style="width: 100px;">
                                    <label
                                        class="form-label text-xs text-uppercase font-weight-bolder text-primary mb-1">Records</label>
                                    <select wire:model.live="perPage" class="form-select form-select-sm">
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="300">300</option>
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
                                    Loan Amount
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
                                            <span class="badge badge-sm {{ $badgeClass }}">{{ $statusText }}</span>
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

                                            @if (in_array($loan->approval->status, ['Pending', 'Completed']))
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

                                            <!-- Individual PDF Export Button -->

                                            <button wire:click="Receipt({{ $loan->id }})"
                                                class="btn btn-link text-danger p-1 m-0" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="Export Receipt "
                                                wire:loading.attr="disabled"
                                                wire:target="Receipt({{ $loan->id }})">

                                                <span wire:loading.remove
                                                    wire:target="Receipt({{ $loan->id }})">
                                                    <i class="fas fa-file-pdf"></i>
                                                </span>
                                                <span wire:loading
                                                    wire:target="Receipt({{ $loan->id }})">
                                                    <i class="fas fa-spinner fa-spin"></i>
                                                </span>
                                            </button>

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

    <!-- Hidden form for bulk PDF export -->
    <form id="bulkPdfForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="date_filter" id="bulk_date_filter">
        <input type="hidden" name="search" id="bulk_search">
    </form>

    <script>
        // Handle single PDF download
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('download-pdf', (data) => {
                window.open(data[0].url, '_blank');
            });


            Livewire.on('show-alert', (data) => {
                const alertData = data[0];

                if (alertData.type === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: alertData.title,
                        text: alertData.message,
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                } else if (alertData.type === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: alertData.title,
                        text: alertData.message
                    });
                }
            });



            Livewire.on('download-bulk-pdf', (data) => {
                const form = document.getElementById('bulkPdfForm');
                const dateInput = document.getElementById('bulk_date_filter');
                const searchInput = document.getElementById('bulk_search');

                form.action = data[0].url;
                dateInput.value = data[0].dateFilter || '';
                searchInput.value = data[0].search || '';

                // Hide loader and prevent it from showing
                const loader = document.getElementById('loader');
                if (loader) {
                    loader.style.display = 'none';
                }

                // Set flags to prevent loader
                window.skipLoader = true;
                window.isDownloading = true;

                form.submit();

                // Reset flags after download
                setTimeout(() => {
                    window.skipLoader = false;
                    window.isDownloading = false;
                }, 2000);
            });
        });

        // Also listen for clicks on the bulk export button
        document.addEventListener('DOMContentLoaded', function() {
            const bulkExportButton = document.querySelector('[wire\\:click="ReceiptBulk"]');
            if (bulkExportButton) {
                bulkExportButton.addEventListener('click', function() {
                    window.skipLoader = true;
                    window.isDownloading = true;
                });
            }
        });
    </script>
</div>
