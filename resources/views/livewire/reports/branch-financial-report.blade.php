<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h3 class="card-title">Financial Report</h3>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="generateReport">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" id="startDate" class="form-control" wire:model="startDate">
                        @error('startDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" id="endDate" class="form-control" wire:model="endDate">
                        @error('endDate') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Select Branches</label>
                    <div class="row">
                        @foreach($branches as $branch)
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           value="{{ $branch->id }}"
                                           wire:model="selectedBranches"
                                           id="branch-{{ $branch->id }}">
                                    <label class="form-check-label" for="branch-{{ $branch->id }}">
                                        {{ $branch->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('selectedBranches') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-sync-alt"></i> Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($showReport)
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title">Financial Report: {{ date('M d, Y', strtotime($startDate)) }} - {{ date('M d, Y', strtotime($endDate)) }}</h3>
                <div>
                    <button wire:click="exportPDF" class="btn btn-danger">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                    <button wire:click="exportExcel" class="btn btn-success ml-2">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Company-wide Summary -->
                <div class="mb-5">
                    <h4 class="border-bottom pb-2">Company-wide Summary</h4>
                    <div class="row mt-4">
                        <div class="col-md-4 mt-2">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Balance Sheet</h5>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Total Assets:</span>
                                            <span class="font-weight-bold">{{ number_format($reportData['totals']['total_assets'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Total Liabilities:</span>
                                            <span class="font-weight-bold">{{ number_format($reportData['totals']['total_liabilities'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Total Equity:</span>
                                            <span class="font-weight-bold">{{ number_format($reportData['totals']['total_equity'], 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">Income Statement</h5>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Total Revenue:</span>
                                            <span class="font-weight-bold">{{ number_format($reportData['totals']['total_revenue'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Total Expenses:</span>
                                            <span class="font-weight-bold">{{ number_format($reportData['totals']['total_expenses'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Net Income:</span>
                                            <span class="font-weight-bold">{{ number_format($reportData['totals']['net_income'], 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-2">
                            <div class="card {{ $reportData['totals']['net_income'] >= 0 ? 'bg-success' : 'bg-danger' }} text-white">
                                <div class="card-body">
                                    <h5 class="card-title text-white">Financial Health</h5>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between">
                                            <span>Net Income:</span>
                                            <span class="font-weight-bold">{{ number_format($reportData['totals']['net_income'], 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Total Assets:</span>
                                            <span class="font-weight-bold">{{ number_format($reportData['totals']['total_assets'], 2) }}</span>
                                        </div>
                                        @php
                                            $roi = $reportData['totals']['total_assets'] > 0 ?
                                                ($reportData['totals']['net_income'] / $reportData['totals']['total_assets'] * 100) : 0;
                                        @endphp
                                        <div class="d-flex justify-content-between mt-2">
                                            <span>Return on Assets:</span>
                                            <span class="font-weight-bold">{{ number_format($roi, 2) }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Branch Details -->
                @foreach($reportData as $branchId => $branchData)
                    @if($branchId !== 'totals')
                        <div class="branch-section mb-5">
                            <h4 class="border-bottom pb-2">{{ $branchData['branch']->name }}</h4>

                            <!-- Branch Summary -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class="card bg-light mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Balance Sheet Summary</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                                <span>Total Assets:</span>
                                                <span class="font-weight-bold">{{ number_format($branchData['summary']['total_assets'], 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                                <span>Total Liabilities:</span>
                                                <span class="font-weight-bold">{{ number_format($branchData['summary']['total_liabilities'], 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Total Equity:</span>
                                                <span class="font-weight-bold">{{ number_format($branchData['summary']['total_equity'], 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card bg-light mb-4">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">Income Statement Summary</h5>
                                        </div>
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                                <span>Total Revenue:</span>
                                                <span class="font-weight-bold">{{ number_format($branchData['summary']['total_revenue'], 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                                                <span>Total Expenses:</span>
                                                <span class="font-weight-bold">{{ number_format($branchData['summary']['total_expenses'], 2) }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span>Net Income:</span>
                                                <span class="font-weight-bold">{{ number_format($branchData['summary']['net_income'], 2) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Account Details Tabs -->
                            <ul class="nav nav-tabs mt-4" id="branch-{{ $branchId }}-tabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="assets-tab-{{ $branchId }}"
                                        data-bs-toggle="tab" data-bs-target="#assets-content-{{ $branchId }}"
                                        type="button" role="tab" aria-controls="assets" aria-selected="true">
                                        Assets
                                    </button>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="equity-tab-{{ $branchId }}"
                                        data-bs-toggle="tab" data-bs-target="#equity-content-{{ $branchId }}"
                                        type="button" role="tab" aria-controls="equity" aria-selected="false">
                                        Equity
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="revenue-tab-{{ $branchId }}"
                                        data-bs-toggle="tab" data-bs-target="#revenue-content-{{ $branchId }}"
                                        type="button" role="tab" aria-controls="revenue" aria-selected="false">
                                        Revenue
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="expenses-tab-{{ $branchId }}"
                                        data-bs-toggle="tab" data-bs-target="#expenses-content-{{ $branchId }}"
                                        type="button" role="tab" aria-controls="expenses" aria-selected="false">
                                        Expenses
                                    </button>
                                </li>

                            </ul>

                            <div class="tab-content" id="branch-{{ $branchId }}-content">
                                <!-- Assets Tab -->
                                <div class="tab-pane fade show active" id="assets-content-{{ $branchId }}"
                                    role="tabpanel" aria-labelledby="assets-tab-{{ $branchId }}">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Account</th>
                                                    <th>Account Number</th>
                                                    <th>Opening Balance</th>
                                                    <th>Debits</th>
                                                    <th>Credits</th>
                                                    <th>Net Change</th>
                                                    <th>Ending Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($branchData['assets'] as $account)
                                                    <tr>
                                                        <td>{{ $account['account_name'] }}</td>
                                                        <td>{{ $account['account_number'] }}</td>
                                                        <td>{{ number_format($account['opening_balance'], 2) }}</td>
                                                        <td>{{ number_format($account['debits'], 2) }}</td>
                                                        <td>{{ number_format($account['credits'], 2) }}</td>
                                                        <td>{{ number_format($account['net_change'], 2) }}</td>
                                                        <td>{{ number_format($account['ending_balance'], 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-light font-weight-bold">
                                                <tr>
                                                    <td colspan="2">Total Assets</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['assets'], 'opening_balance')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['assets'], 'debits')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['assets'], 'credits')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['assets'], 'net_change')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['assets'], 'ending_balance')), 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <!-- Other account category tabs follow the same pattern -->


                                <!-- Equity Tab -->
                                <div class="tab-pane fade" id="equity-content-{{ $branchId }}"
                                role="tabpanel" aria-labelledby="equity-tab-{{ $branchId }}">
                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Account</th>
                                                    <th>Account Number</th>
                                                    <th>Opening Balance</th>
                                                    <th>Debits</th>
                                                    <th>Credits</th>
                                                    <th>Net Change</th>
                                                    <th>Ending Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($branchData['equity'] as $account)
                                                    <tr>
                                                        <td>{{ $account['account_name'] }}</td>
                                                        <td>{{ $account['account_number'] }}</td>
                                                        <td>{{ number_format($account['opening_balance'], 2) }}</td>
                                                        <td>{{ number_format($account['debits'], 2) }}</td>
                                                        <td>{{ number_format($account['credits'], 2) }}</td>
                                                        <td>{{ number_format($account['net_change'], 2) }}</td>
                                                        <td>{{ number_format($account['ending_balance'], 2) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-light font-weight-bold">
                                                <tr>
                                                    <td colspan="2">Total Equity</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['equity'], 'opening_balance')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['equity'], 'debits')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['equity'], 'credits')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['equity'], 'net_change')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['equity'], 'ending_balance')), 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>

                                <!-- Revenue Tab -->
                                <div class="tab-pane fade" id="revenue-content-{{ $branchId }}"
                                role="tabpanel" aria-labelledby="revenue-tab-{{ $branchId }}">
                                    <!-- Similar table structure as assets -->


                                    <div class="table-responsive mt-3">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <th>Account</th>
                                                    <th>Account Number</th>
                                                    <th>Opening Balance</th>
                                                    <th>Debits</th>
                                                    <th>Credits</th>
                                                    <th>Net Change</th>
                                                    <th>Ending Balance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($branchData['revenue'] as $account)
                                                <tr>
                                                    <td>{{ $account['account_name'] }}</td>
                                                    <td>{{ $account['account_number'] }}</td>
                                                    <td>{{ number_format($account['opening_balance'], 2) }}</td>
                                                    <td>{{ number_format($account['debits'], 2) }}</td>
                                                    <td>{{ number_format($account['credits'], 2) }}</td>
                                                    <td>{{ number_format($account['net_change'], 2) }}</td>
                                                    <td>{{ number_format($account['ending_balance'], 2) }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot class="bg-light font-weight-bold">
                                                <tr>
                                                    <td colspan="2">Total Revenue</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['revenue'], 'opening_balance')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['revenue'], 'debits')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['revenue'], 'credits')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['revenue'], 'net_change')), 2) }}</td>
                                                    <td>{{ number_format(array_sum(array_column($branchData['revenue'], 'ending_balance')), 2) }}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>






                                </div>

                                <!-- Expenses Tab -->
                                <div class="tab-pane fade" id="expenses-content-{{ $branchId }}"
                                role="tabpanel" aria-labelledby="expenses-tab-{{ $branchId }}">




                                <div class="table-responsive mt-3">
                                    <table class="table table-bordered table-striped">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th>Account</th>
                                                <th>Account Number</th>
                                                <th>Opening Balance</th>
                                                <th>Debits</th>
                                                <th>Credits</th>
                                                <th>Net Change</th>
                                                <th>Ending Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($branchData['expenses'] as $account)
                                                <tr>
                                                    <td>{{ $account['account_name'] }}</td>
                                                    <td>{{ $account['account_number'] }}</td>
                                                    <td>{{ number_format($account['opening_balance'], 2) }}</td>
                                                    <td>{{ number_format($account['debits'], 2) }}</td>
                                                    <td>{{ number_format($account['credits'], 2) }}</td>
                                                    <td>{{ number_format($account['net_change'], 2) }}</td>
                                                    <td>{{ number_format($account['ending_balance'], 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot class="bg-light font-weight-bold">
                                            <tr>
                                                <td colspan="2">Total Expenses</td>
                                                <td>{{ number_format(array_sum(array_column($branchData['expenses'], 'opening_balance')), 2) }}</td>
                                                <td>{{ number_format(array_sum(array_column($branchData['expenses'], 'debits')), 2) }}</td>
                                                <td>{{ number_format(array_sum(array_column($branchData['expenses'], 'credits')), 2) }}</td>
                                                <td>{{ number_format(array_sum(array_column($branchData['expenses'], 'net_change')), 2) }}</td>
                                                <td>{{ number_format(array_sum(array_column($branchData['expenses'], 'ending_balance')), 2) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                </div>



                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif


    <style>
        .table {
            --bs-table-bg: #f8fafc;
            border-collapse: separate;
            border-spacing: 0 0.5rem;
        }

        .table thead th {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
            letter-spacing: 0.5px;
            border: none;
        }

        .table-hover tbody tr {
            transition: all 0.2s ease;
            background-color: white;
        }

        .table-hover tbody tr:hover {
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .table-bordered {
            border: 1px solid #e9ecef;
            border-radius: 0.75rem;
            overflow: hidden;
        }

        .table-group-divider {
            border-top: 2px solid #dee2e6;
        }

        .text-end {
            padding-right: 1.5rem;
        }

        .table-active {
            background-color: #f1f3f5 !important;
        }

        .table-responsive {
            border-radius: 0.75rem;
            background: white;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }

        </style>

</div>

    @push('scripts')
<script>
// Bootstrap 5 Tab Initialization
document.addEventListener('livewire:load', function() {
    // Initialize tabs on load
    initTabs();

    // Reinitialize when Livewire updates DOM
    Livewire.hook('element.updated', (el) => {
        initTabs();
    });
});

function initTabs() {
    // Initialize all tab buttons
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(tabEl => {
        new bootstrap.Tab(tabEl).show(); // Only initialize if not already initialized

        // Add click handler to store active tab
        tabEl.addEventListener('shown.bs.tab', function (event) {
            localStorage.setItem('lastTab', event.target.id);
        });
    });

    // Activate last used tab if exists
    const lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
        const tab = new bootstrap.Tab(document.querySelector(`#${lastTab}`));
        tab.show();
    }
}
</script>
@endpush
<script>


</script>
