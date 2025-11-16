<div>
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Loan Report</h1>
            <p class="text-muted mb-0">{{ now()->format('F d, Y') }}</p>
        </div>
        <div class="d-flex gap-2">
            <button wire:click="exportPDF('download')" class="btn btn-primary">
                <i class="fas fa-download me-2"></i>Export PDF
            </button>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-12 col-lg-4">
                    <input type="text"
                           wire:model.debounce.500ms="search"
                           placeholder="Search customer..."
                           class="form-control">
                </div>

                <div class="col-12 col-lg-4" wire:ignore>
                    <select wire:model.live="center"
                            id="centerSelect"
                            class="form-select">
                        <option value="">All Centres</option>
                        @foreach($centers as $center)
                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-lg-4" wire:ignore>
                    <select wire:model.live="statusFilter"
                            id="statusSelect"
                            class="form-select">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="Active">Active</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table with loading state -->
    <div class="card shadow-sm border-0">
        <div wire:loading.class="opacity-50" class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light position-sticky top-0" style="z-index: 1;">
                        <tr>
                            <th class="ps-4">
                                <a href="#" wire:click.prevent="sortBy('loan_number')" class="text-decoration-none text-dark d-flex align-items-center">
                                    Loan ID
                                    @if ($sortBy === 'loan_number')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Customer</th>
                            <th>Guarantors</th>
                            <th class="text-end">
                                <a href="#" wire:click.prevent="sortBy('loan_amount')" class="text-decoration-none text-dark d-flex align-items-center justify-content-end">
                                    Loan Amount
                                    @if ($sortBy === 'loan_amount')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th>Status</th>
                            <th>
                                <a href="#" wire:click.prevent="sortBy('created_at')" class="text-decoration-none text-dark d-flex align-items-center">
                                    Applied On
                                    @if ($sortBy === 'created_at')
                                        <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @endif
                                </a>
                            </th>
                            <th class="text-end">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                        <tr class="border-top">
                            <td class="ps-4">{{ $loan->loan_number }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle me-2 text-muted"></i>
                                    {{ $loan->customer->full_name }}
                                </div>
                            </td>
                            <td style="max-width: 150px;">
                                @if($loan->guarantors->isNotEmpty())
                                    <button type="button"
                                           class="btn btn-sm btn-link p-0"
                                           data-bs-toggle="tooltip"
                                           data-bs-html="true"
                                           data-bs-title="{{ $loan->guarantors->pluck('full_name')->join('<br>') }}">
                                        {{ $loan->guarantors->count() }} Guarantors
                                    </button>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td class="text-end">{{ number_format($loan->loan_amount, 2) }}</td>
                            <td>
                                <span class="badge rounded-pill text-white
                                @if($loan->approval->status === 'approved')
                                    bg-gradient-success
                                @elseif($loan->approval->status === 'pending')
                                    bg-gradient-warning
                                @elseif($loan->approval->status === 'rejected')
                                    bg-gradient-danger
                                @elseif($loan->approval->status === 'Active')
                                    bg-gradient-info
                                @else
                                    bg-gradient-secondary
                                @endif">
                                {{ ucfirst($loan->approval->status) }}
                            </span>
                            </td>
                            <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                            <td class="text-end fw-bold">
                                @if($loan->approval && isset($loan->loanProgress) && in_array(strtolower($loan->approval->status), ['approved', 'active']))
                                    {{ number_format($loan->loanProgress->balance, 2) }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-search fa-2x text-muted mb-3"></i>
                                    <p class="mb-0">No loans found matching your filters.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Loading Indicator -->
            <div wire:loading class="position-absolute top-50 start-50 translate-middle">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- Pagination -->
            @if($loans->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                <div class="text-muted small">
                    Showing {{ $loans->firstItem() }} - {{ $loans->lastItem() }} of {{ $loans->total() }}
                </div>

                {{ $loans->links('livewire::bootstrap') }}
            </div>
            @endif
        </div>
    </div>

    <script>
    document.addEventListener('livewire:init', function() {
        // Use tooltips instead of popovers for better performance
        const initTooltips = () => {
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl, {
                boundary: document.body
            }));
        };

        initTooltips();

        Livewire.hook('message.processed', (message, component) => {
            initTooltips();
        });

        // Optimize select initialization
        let centerChoices = null;
        let statusChoices = null;

        const initSelects = () => {
            // Center select
            if (!centerChoices) {
                const centerElement = document.getElementById('centerSelect');
                if (centerElement) {
                    centerChoices = new Choices(centerElement, {
                        searchPlaceholderValue: 'Search centers...',
                        removeItemButton: true,
                        shouldSort: false,
                        renderChoiceLimit: 4,
                        searchResultLimit: 4,
                        fuseOptions: {
                            threshold: 0.3,
                            distance: 1000
                        }
                    });

                    centerElement.addEventListener('change', (e) => {
                        @this.set('center', e.target.value || null);
                    });
                }
            }

            // Status select
            if (!statusChoices) {
                const statusElement = document.getElementById('statusSelect');
                if (statusElement) {
                    statusChoices = new Choices(statusElement, {
                        searchPlaceholderValue: 'Search status...',
                        removeItemButton: true,
                        shouldSort: false,
                        fuseOptions: { threshold: 0.3 }
                    });

                    statusElement.addEventListener('change', (e) => {
                        @this.set('statusFilter', e.target.value || null);
                    });
                }
            }
        };

        initSelects();
    });
    </script>
</div>
