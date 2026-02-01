<div class="container-fluid">
    <!-- Header Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Pending due Report</h1>
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
                    {{-- <li><a class="dropdown-item" href="#" wire:click.prevent="exportPdf">
                        <i class="fas fa-file-pdf text-danger me-2"></i>PDF
                    </a></li> --}}
                </ul>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">
            <div class="row g-4">
                <div class="col-12 col-lg-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Date Range</label>
                    <div class="input-group">
                        <input type="date" wire:model.live="dateFrom" class="form-control border-end-0">
                        <span class="input-group-text bg-transparent">to</span>
                        <input type="date" wire:model.live="dateTo" class="form-control">
                    </div>
                </div>

                <div class="col-12 col-lg-4" wire:ignore>
                    <label class="form-label small fw-bold text-uppercase text-muted">Center</label>
                    <select wire:model.live="centerId" id="centerSelect" class="form-select">
                        <option value="">All Centers</option>
                        @foreach($centers as $center)
                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 col-lg-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Status</label>
                    <select wire:model.live="status" class="form-select">
                        <option value="">All Status</option>
                        <option value="Overdue">Overdue</option>
                        <option value="Pending">Pending</option>
                        <option value="Partial">Partial Payment</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards - Responsive for all devices -->
    <div class="row g-3 g-lg-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card bg-gradient-danger text-white shadow-lg border-0 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small mb-2 text-white">Total Overdue</h6>
                            <h2 class="mb-0 text-white fs-4 fs-md-3">{{ number_format($totalPending, 2) }}</h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x fa-md-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-lg-4">
            <div class="card bg-gradient-info text-white shadow-lg border-0 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small mb-2 text-white">Average Delay</h6>
                            <h2 class="mb-0 text-white fs-4 fs-md-3">{{ $averageDelay }} days</h2>
                        </div>
                        <i class="fas fa-calendar-times fa-2x fa-md-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-lg-4">
            <div class="card bg-gradient-secondary text-white shadow-lg border-0 h-100">
                <div class="card-body p-3 p-md-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small mb-2 text-white">Due Today</h6>
                            <h2 class="mb-0 text-white fs-4 fs-md-3">{{ number_format($todayOverdueAmount, 2) }}</h2>
                            <small class="text-white-50">{{ $todayOverdueCount }} loans</small>
                        </div>
                        <i class="fas fa-calendar-day fa-2x fa-md-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th style="width: 120px;">Due Date</th>
                            <th class="text-center d-none d-sm-table-cell" style="width: 130px;">Days Overdue</th>
                            <th style="width: 150px;">Loan Account</th>
                            <th class="d-none d-md-table-cell" style="width: 200px;">Customer</th>
                            <th class="text-end" style="width: 150px;">Overdue Amount</th>
                            <th class="text-end" style="width: 130px;">Due Amount</th>
                            <th class="d-none d-lg-table-cell" style="width: 180px;">Center</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                        <tr class="border-top">
                            <td style="width: 120px;">{{ $schedule->date->format('d M Y') }}</td>
                            <td class="text-center d-none d-sm-table-cell" style="width: 130px;">
                                <span class="badge bg-danger">{{ now()->diffInDays($schedule->date) }} days</span>
                            </td>
                            <td class="fw-semibold" style="width: 150px;">#{{ $schedule->loan->loan_number ?? ' '}}</td>
                            <td class="d-none d-md-table-cell customer-name" style="width: 200px; max-width: 200px;">
                                <div class="d-flex align-items-center" title="{{ $schedule->loan->customer->full_name ?? '' }}">
                                    <i class="fas fa-user-circle me-2 text-muted flex-shrink-0"></i>
                                    <span class="text-truncate">{{ $schedule->loan->customer->full_name ?? '' }}</span>
                            </div>
                            </td>
                            <td class="text-end fw-bold text-danger" style="width: 150px;">{{ number_format($schedule->pending_due, 2) }}</td>
                            <td class="text-end" style="width: 130px;">{{ number_format($schedule->due, 2) }}</td>
                            <td class="d-none d-lg-table-cell" style="width: 180px;">
                                <i class="fas fa-building me-2 text-muted"></i>
                                {{ $schedule->loan->center->name }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 bg-light">
                                <div class="py-4">
                                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                                    <p class="mb-0 text-muted">No overdue payments found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($schedules->hasPages())
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center px-3 px-md-4 py-3 border-top gap-3">
                <div class="text-muted small text-center text-sm-start">
                    Showing {{ $schedules->firstItem() }} - {{ $schedules->lastItem() }} of {{ $schedules->total() }}
                </div>
                {{ $schedules->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>


<style>
    .bg-gradient-danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .bg-gradient-info { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .bg-gradient-secondary { background: linear-gradient(135deg, #6366f1, #4f46e5); }

    .table-hover tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.03) !important;
    }

    /* Customer name column fixed width with text truncation */
    .customer-name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .customer-name span {
        display: inline-block;
        max-width: 160px;
    }

    /* Enhanced tooltip styling */
    .customer-name [title] {
        cursor: help;
    }

    /* Mobile responsiveness */
    @media (max-width: 576px) {
        .h3 { font-size: 1.5rem !important; }
        .card-body h2 { font-size: 1.25rem !important; }
        .table { font-size: 0.875rem; }
    }

    @media (max-width: 768px) {
        .table thead th { font-size: 0.85rem; padding: 0.5rem; }
        .table tbody td { font-size: 0.85rem; padding: 0.5rem; }
    }
</style>
</div>
<script>
    document.addEventListener('livewire:init', () => {
        let choices;

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
                });

                element.addEventListener('change', (e) => {
                    @this.set('centerId', e.target.value);
                });
            }
        };

        initChoices();

        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                if (component.id === @this.__instance.id) {
                    initChoices();
                }
            });
        });
    });
</script>
