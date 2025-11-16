<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-white">Customer Report</h4>
                    <span class="badge bg-light text-primary">{{ now()->format('F d, Y') }}</span>
                </div>
                <div class="card-body">
                    <!-- Filters Card -->
                    <div class="row mb-4">
                        <div class="col-md-12 mx-auto">
                            <div class="card h-100 border-0 shadow-sm">

                                <div class="card-body">
                                    <div class="row g-4">
                                        <div class="col-12 col-lg-4">
                                            <input type="text"
                                                wire:model.live="search"
                                                placeholder="Search by name..."
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

                                      <div class="col-12 col-lg-4">
                                            <div class="d-flex flex-column flex-md-row gap-2 justify-content-md-end">
                                                <button wire:click="exportPDF" class="btn btn-primary w-100 w-md-auto">
                                                    <i class="fas fa-download me-2"></i>Export PDF
                                                </button>
                                                <button wire:click="exportExcel" class="btn btn-info w-100 w-md-auto">
                                                    <i class="fas fa-file-excel me-2"></i>Export Excel
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Data Table Card -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="bi bi-people"></i>
                                        Customer List
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4">Name</th>
                                                    <th>Customer No</th>
                                                    <th>NIC</th>
                                                    <th>Phone</th>
                                                    <th>Loan Status</th>
                                                    <th>Registered Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($customers as $customer)
                                                <tr class="border-top">
                                                    <td class="ps-4">{{ $customer->full_name }}</td>
                                                    <td>{{ $customer->customer_no }}</td>
                                                    <td>{{ $customer->nic }}</td>
                                                    <td>{{ $customer->customer_phone }}</td>
                                                    <td>
                                                        @if($customer->loans->isNotEmpty())
                                                            @php $status = optional($customer->loans->first()->approval)->status @endphp
                                                            <span class="badge bg-light text-dark border">
                                                                {{ $status ?? 'Pending' }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-light text-secondary border">
                                                                No Loans
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $customer->created_at->format('Y-m-d') }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Pagination -->
                                    @if($customers->hasPages())
                                    <div class="d-flex justify-content-between align-items-center px-4 py-3 border-top">
                                        <div class="text-muted small">
                                            Showing {{ $customers->firstItem() }} - {{ $customers->lastItem() }} of {{ $customers->total() }}
                                        </div>
                                        {{ $customers->links('pagination::bootstrap-5') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('livewire:init', function () {
        let centerChoices = null;

        // Initialize Choices.js for center select
        const initCenterChoices = () => {
            if (centerChoices) centerChoices.destroy();

            const element = document.getElementById('centerSelect');
            if (element) {
                centerChoices = new Choices(element, {
                    searchPlaceholderValue: 'Search centers...',
                    removeItemButton: true,
                    shouldSort: false,
                    renderChoiceLimit: 4, // Show max 4 options initially
                    searchResultLimit: 4,
                    classNames: {
                        containerInner: 'choices__inner',
                        input: 'choices__input',
                        listDropdown: 'choices__list--dropdown'
                    },
                    fuseOptions: { threshold: 0.3 }
                });

                element.addEventListener('change', (e) => {
                    @this.set('center', e.target.value);
                });
            }
        };

        // Initial initialization
        initCenterChoices();

        // Reinitialize when Livewire updates
        Livewire.hook('commit', ({ component, succeed }) => {
            succeed(() => {
                if (component.id === @this.__instance.id) {
                    initCenterChoices();
                }
            });
        });
    });
</script>
