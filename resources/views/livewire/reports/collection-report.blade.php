<div>
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Loan Collection Report</h1>
            <p class="text-muted mb-0">{{ now()->format('F d, Y') }}</p>
        </div>
        <div class="d-flex gap-2">
          
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-download me-2"></i>Export
                </button>
                <ul class="dropdown-menu shadow">
                    <li><a class="dropdown-item" href="#" wire:click.prevent="exportExcel">
                        <i class="fas fa-file-excel text-success me-2"></i>Excel
                    </a></li>
                    <li><a class="dropdown-item" href="#" wire:click.prevent="exportPdf">
                        <i class="fas fa-file-pdf text-danger me-2"></i>PDF
                    </a></li>
                </ul>
            </div>
        </div>
    </div>



    <div class="card shadow-sm border-0 mb-4 ">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-12 col-lg-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Date Range</label>
                    <div class="input-group">
                        <input type="date" wire:model.lazy="dateFrom" class="form-control border-end-0">
                        <span class="input-group-text bg-transparent">to</span>
                        <input type="date" wire:model.lazy="dateTo" class="form-control">
                    </div>
                </div>

                <div class="col-12 col-lg-3" wire:ignore>
                    <label class="form-label small fw-bold text-uppercase text-muted">Collector</label>
                    <select wire:model.live="collectorId"
                            id="collectorSelect"
                            class="form-select">
                        <option value="">All Collectors</option>
                        @foreach($collectors as $collector)
                            <option value="{{ $collector->id }}">{{ $collector->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-lg-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Status</label>
                    <select wire:model.live="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Pending">Pending</option>
                        <option value="Waiting to Accept">Waiting to Accept</option>
                        <option value="Transferred">Transferred</option>
                    </select>
                </div>

                <div class="col-12 col-lg-3">
                    <label class="form-label small fw-bold text-uppercase text-muted">Branch</label>
                    <select wire:model.live="branchId" class="form-select">
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card bg-gradient-primary text-white shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small mb-2 text-white">Total Principal</h6>
                            <h2 class="mb-0 text-white">{{ number_format($totalPrincipal, 2) }}</h2>
                        </div>
                        <i class="fas fa-landmark fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card bg-gradient-success text-white shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small mb-2 text-white">Total Interest</h6>
                            <h2 class="mb-0 text-white">{{ number_format($totalInterest, 2) }}</h2>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card bg-gradient-info text-white shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small mb-2 text-white">Total Collected</h6>
                            <h2 class="mb-0 text-white">{{ number_format($totalCollections, 2) }}</h2>
                        </div>
                        <i class="fas fa-wallet fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Filters Card -->

    <!-- Summary Cards -->


    <!-- Data Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th wire:click="sortBy('collection_date')" class="ps-4" style="cursor: pointer;">
                                Date
                                @if($sortBy === 'collection_date')
                                    <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} float-end me-3"></i>
                                @else
                                    <i class="fas fa-sort float-end me-3 text-muted"></i>
                                @endif
                            </th>
                            <th>Loan ID</th>
                            <th>Customer</th>
                            <th>Collector</th>
                            <th class="text-end">Principal</th>
                            <th class="text-end">Interest</th>
                            <th class="text-end">Total</th>
                            <th>Method</th>
                            <th>Branch</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($collections as $collection)
                        <tr class="border-top">
                            <td class="ps-4">{{ $collection->collection_date->format('d M Y') }}</td>
                            <td class="fw-semibold">#{{ $collection->loan->center->name }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle me-2 text-muted"></i>
                                    {{ optional($collection->loan->customer)->full_name ?? 'N/A' }}
                                </div>
                            </td>
                            <td>
                                <div class="badge bg-gradient-secondary bg-opacity-10 text-secondery">
                                    {{ optional($collection->collector)->name ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="text-end">{{ number_format($collection->principal_amount, 2) }}</td>
                            <td class="text-end">{{ number_format($collection->interest_amount, 2) }}</td>
                            <td class="text-end fw-bold text-success">{{ number_format($collection->collected_amount, 2) }}</td>
                            <td>
                                <span class="badge rounded-pill bg-light text-dark border">
                                    {{ $collection->collection_method }}
                                </span>
                            </td>
                            <td>
                                <i class="fas fa-building me-2 text-muted"></i>
                                {{ optional($collection->loan->center->branch)->name ?? 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5 bg-light">
                                <div class="py-4">
                                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                                    <p class="mb-0 text-muted">No collection records found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($collections->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                <div class="text-muted small">
                    Showing {{ $collections->firstItem() }} - {{ $collections->lastItem() }} of {{ $collections->total() }}
                </div>
                {{ $collections->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>


<style>
    :root {
        --primary-color: #7b8bd1;
        --success-color: #22c55e;
        --info-color: #b345c1;
    }
    .choices__list--dropdown {
        z-index: 9999 !important; /* Higher than any other elements */
        transform: translateY(5px); /* Add small spacing from input */
    }
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }


    .card-body {
        overflow: visible !important;
    }

    .card {
        border-radius: 12px;
        /* transition: transform 0.2s, box-shadow 0.2s; */
    }

    .card:hover {
        /* transform: translateY(-2px); */
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, var(--primary-color), #3b4bdf);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, var(--success-color), #1d9d50);
    }

    .bg-gradient-info {
        background: linear-gradient(135deg, var(--info-color), #2563eb);
    }

    .table-hover tbody tr {
        transition: background-color 0.15s;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(var(--primary-color), 0.03);
    }

    .choices__inner {
        border-radius: 8px !important;
        border: 1px solid #222f41 !important;
    }
</style>
<div>
<script>
    document.addEventListener('livewire:init', function () {
        let choicesInstance = null;

        // Initialize Choices.js
        const initChoices = () => {
            if (choicesInstance) {
                choicesInstance.destroy();
            }

            const element = document.getElementById('collectorSelect');
            if (element) {
                choicesInstance = new Choices(element, {
                    searchPlaceholderValue: 'Search collectors...',
                    removeItemButton: true,
                    position: 'auto',
                    shouldSort: false,
                renderChoiceLimit: 4, // Show max 4 options initially
                searchResultLimit: 4,
                    classNames: {
                        containerInner: 'choices__inner',
                        input: 'choices__input',
                    },
                    shouldSort: false,
                    fuseOptions: {
                        threshold: 0.3
                    }
                });

                // Update Livewire when selection changes
                element.addEventListener('change', (e) => {
                    @this.set('collectorId', e.target.value);
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

