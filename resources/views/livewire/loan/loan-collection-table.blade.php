<div class="container-fluid">
    <div class="card border-0 shadow-lg">
        <div class="card-header bg-light-primary border-bottom-0 py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-calendar-day fs-4 text-primary me-3"></i>
                    <div>
                        <h4 class="mb-0 fw-semibold text-dark">Daily Collections</h4>
                        <span class="text-muted fs-sm">{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    {{-- <button class="btn btn-sm btn-outline-primary d-flex align-items-center" wire:click="exportExcel">
                        <i class="fas fa-file-excel me-2 fs-5"></i>
                        <span class="d-none d-sm-inline">Excel Export</span>
                    </button> --}}
                    <button class="btn btn-sm btn-primary d-flex align-items-center" wire:click="exportPdf">
                        <i class="fas fa-file-pdf me-2 fs-5"></i>
                        <span class="d-none d-sm-inline">PDF Export</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body pt-4">
            <!-- Filters Section - Improved for mobile -->
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <div class="row g-3">
                        <!-- Search Input -->
                        <div class="col-12 col-md-6 col-lg-3">
                            <div class="form-group mb-0">
                                <label class="form-label small text-muted mb-1">Search Customers</label>
                                <div class="input-group input-group-merged shadow-sm">
                                    <span class="input-group-text bg-white border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text"
                                           class="form-control rounded-end"
                                           placeholder="Name or phone..."
                                           wire:model.live.debounce.300ms="searchTerm">
                                </div>
                            </div>
                        </div>

                        <!-- Center Select -->
                        <div class="col-12 col-md-6 col-lg-3" wire:ignore>
                            <div class="form-group mb-0">
                                <label class="form-label small text-muted mb-1">Select Center</label>
                                <select wire:model.live="centerId"
                                        class="form-select shadow-sm"
                                        id="centerSelect">
                                    <option value="">All Centers</option>
                                    @foreach($centers as $center)
                                        <option value="{{ $center->id }}">{{ $center->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Date Range - Responsive version -->
                        <div class="col-12 col-lg-6">
                            <div class="form-group mb-0">
                                <label class="form-label small text-muted mb-1">Date Range</label>
                                <div class="row g-2">
                                    <div class="col-12 col-sm-6">
                                        <div class="input-group input-group-merged shadow-sm">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-calendar-start text-muted"></i>
                                            </span>
                                            <input type="date"
                                                   class="form-control"
                                                   wire:model.live="startDate">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6">
                                        <div class="input-group input-group-merged shadow-sm">
                                            <span class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-calendar-end text-muted"></i>
                                            </span>
                                            <input type="date"
                                                   class="form-control"
                                                   wire:model.live="endDate">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Loan Type & Items per Page -->
                        <div class="col-12 col-lg-3">
                            <div class="row g-2">
                                <div class="col-6 col-sm-6">
                                    <div class="form-group mb-0">
                                        <label class="form-label small text-muted mb-1">Loan Type</label>
                                        <select class="form-select shadow-sm"
                                                wire:model.live="loanType">
                                            <option value="">All Types</option>
                                            <option value="individual">Personal</option>
                                            <option value="group">Group</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-6 col-sm-4">
                                    <div class="form-group mb-0">
                                        <label class="form-label small text-muted mb-1">Items/Page</label>
                                        <select class="form-select shadow-sm"
                                                wire:model="perPage">
                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards - Improved for mobile -->
            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4">
                    <div class="card border-0 hover-animate bg-gradient-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <span class="h6 text-uppercase text-white mb-0">Total Collections</span>
                                    <h2 class="mt-2 mb-0">{{ $collectionCount }}</h2>
                                </div>
                                <div class="icon-shape bg-white-10 rounded-circle p-3">
                                    <i class="fas fa-list fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card border-0 hover-animate bg-gradient-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <span class="h6 text-uppercase text-white mb-0">Total Due</span>
                                    <h2 class="mt-2 mb-0">{{ number_format($totalDue, 2) }}</h2>
                                </div>
                                <div class="icon-shape bg-white-10 rounded-circle p-3">
                                    <i class="fas fa-coins fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="card border-0 hover-animate bg-gradient-danger text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <span class="h6 text-uppercase text-white mb-0">Pending Amount</span>
                                    <h2 class="mt-2 mb-0">{{ number_format($totalPending, 2) }}</h2>
                                </div>
                                <div class="icon-shape bg-white-10 rounded-circle p-3">
                                    <i class="fas fa-exclamation-triangle fa-2x text-white"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table - Modified for responsive display -->
<div class="table-responsive rounded-3 border shadow-sm">
    <table class="table table-hover mb-0">
        <thead class="bg-light">
            <tr>
                <th class="ps-3 py-3 text-uppercase small text-muted fw-semibold">Date</th>
                <th class="py-3 text-uppercase small text-muted fw-semibold">Customer</th>
                <th class="py-3 text-uppercase small text-muted fw-semibold d-none d-md-table-cell">Center</th>
                <th class="py-3 text-uppercase small text-muted fw-semibold">Due</th>
                <th class="py-3 text-uppercase small text-muted fw-semibold d-none d-md-table-cell">Paid</th>
                <th class="py-3 text-uppercase small text-muted fw-semibold ">Pending</th>
                <th class="py-3 text-uppercase small text-muted fw-semibold d-none d-md-table-cell">Status</th>
                <th class="pe-3 py-3 text-uppercase small text-muted fw-semibold text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $schedule)
                <tr class="align-middle">
                    <td class="ps-3">{{ $schedule->date->format('d M, Y') }}</td>
                    <td>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold text-truncate">{{ $schedule->loan->customer->full_name ?? 'N/A' }}</span>
                            <small class="text-muted">{{ $schedule->loan->loan_number ?? 'N/A' }}</small>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">{{ $schedule->loan->center->name ?? 'N/A' }}</td>
                    <td class="fw-bold font-monospace">{{ number_format($schedule->due, 2) }}</td>
                    <td class="text-success fw-bold font-monospace d-none d-md-table-cell">{{ number_format($schedule->paid, 2) }}</td>
                    <td class="text-danger fw-bold font-monospace">{{ number_format($schedule->pending_due, 2) }}</td>
                    <td class="d-none d-md-table-cell">
                        @if ($schedule->pending_due > 0)
                            <span class="badge bg-danger-soft text-danger">Pending</span>
                        @else
                            <span class="badge bg-success-soft text-success">Completed</span>
                        @endif
                    </td>
                 <td class="pe-3 text-end ">
                                    <button class="btn btn-sm btn-light-primary p-1 p-md-2"
                                 wire:click="viewCollection({{ $schedule->id }})"
                              wire:loading.class="disabled"
                          aria-label="View payment details">

                             <!-- Regular icon (hidden during loading) -->
                                 <i class="fas fa-hand-holding-usd text-success fs-4 fs-md-5"
                                wire:loading.class="d-none"
                              wire:target="viewCollection({{ $schedule->id }})"></i>

                             <!-- Spinner (shown only during loading) -->
            <i class="fas fa-spinner fa-spin text-primary fs-4 fs-md-5 d-none"
       wire:loading.class.remove="d-none"
       wire:target="viewCollection({{ $schedule->id }})"></i>

    <!-- Button text -->
    <span class="d-none d-md-inline ms-1">Payment</span>
</button>
</td>



                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="py-4">
                            <i class="fas fa-calendar-times fa-2x text-muted mb-3"></i>
                            <p class="text-muted mb-0">No collections found for selected criteria</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

            <!-- Pagination - Improved for mobile -->
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-4 px-3 gap-3">

                <nav class="d-flex justify-content-center">
                    {{ $schedules->links('livewire::bootstrap') }}
                </nav>
            </div>
        </div>
    </div>
    @livewire('collection-modal-component')


</div>
<script>

function confirmSubmit() {
    Swal.fire({
        title: 'Submit Payment?',
        text: "Are you sure you want to submit this payment?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Manually dispatch Livewire action
            Livewire.dispatch('submitPayment');
        }
    });
}






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
