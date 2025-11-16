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
                    <li><a class="dropdown-item" href="#" wire:click.prevent="exportPdf">
                        <i class="fas fa-file-pdf text-danger me-2"></i>PDF
                    </a></li>
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

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card bg-gradient-danger text-white shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small mb-2 text-white">Total Pending</h6>
                            <h2 class="mb-0 text-white">{{ number_format($totalPending, 2) }}</h2>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card bg-gradient-warning text-white shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small mb-2 text-white">Overdue Accounts</h6>
                            <h2 class="mb-0 text-white">{{ $overdueCount }}</h2>
                        </div>
                        <i class="fas fa-clock fa-3x opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-4">
            <div class="card bg-gradient-info text-white shadow-lg border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase small mb-2 text-white">Average Delay</h6>
                            <h2 class="mb-0 text-white">{{ $averageDelay }} days</h2>
                        </div>
                        <i class="fas fa-calendar-times fa-3x opacity-25"></i>
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
                            <th >
                                Due Date

                            </th>
                            <th>Loan Account</th>
                            <th>Customer</th>
                            <th class="text-end">Due Amount</th>
                            <th class="text-end">Total Paid </th>
                            <th class="text-end">Pending</th>
                            <th>Center</th>

                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                        <tr class="border-top">
                            <td>{{ $schedule->date->format('d M Y') }}</td>
                            <td class="fw-semibold">#{{ $schedule->loan->loan_number }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle me-2 text-muted"></i>
                                    {{ $schedule->loan->customer->full_name }}
                                </div>
                            </td>
                            <td class="text-end">{{ number_format($schedule->due, 2) }}</td>
                            <td class="text-end">{{ number_format($schedule->due - $schedule->pending_due, 2) }}</td>
                            <td class="text-end fw-bold text-danger">{{ number_format($schedule->pending_due, 2) }}</td>

                            <td>
                                <i class="fas fa-building me-2 text-muted"></i>
                                {{ $schedule->loan->center->name }}
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 bg-light">
                                <div class="py-4">
                                    <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                                    <p class="mb-0 text-muted">No pending dues found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($schedules->hasPages())
            <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                <div class="text-muted small">
                    Showing {{ $schedules->firstItem() }} - {{ $schedules->lastItem() }} of {{ $schedules->total() }}
                </div>
                {{ $schedules->links('pagination::bootstrap-5') }}
            </div>
            @endif
        </div>
    </div>


<style>
    .bg-gradient-danger { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .bg-gradient-warning { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .bg-gradient-info { background: linear-gradient(135deg, #3b82f6, #2563eb); }

    .table-hover tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.03) !important;
    }

    .badge-danger { background-color: #fee2e2 !important; color: #dc2626 !important; }
    .badge-warning { background-color: #fef3c7 !important; color: #d97706 !important; }
    .badge-secondary { background-color: #f1f5f9 !important; color: #64748b !important; }
</style>

</div>
<script>
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
