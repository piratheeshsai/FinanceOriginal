<div class="container-fluid p-2 p-md-4">
    <div class="card shadow-lg border-0">
        <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h2 class="mb-2 mb-md-0 fw-bold text-white fs-4 fs-md-3">
                    <i class="bi bi-graph-up-arrow me-2"></i>Profit & Loss Overview
                </h2>
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center w-100">
                    <select wire:model.live="selectedBranch" class="form-select form-select-sm me-md-2 mb-2 mb-md-0 w-auto">
                        <option value="all">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                    <div class="d-flex flex-column flex-sm-row align-items-stretch w-100">
                        <div class="input-group input-group-sm mb-2 mb-sm-0 me-sm-2">
                            <span class="input-group-text bg-light"><i class="bi bi-calendar"></i></span>
                            <input type="date" wire:model.live="startDate" class="form-control form-control-sm">
                        </div>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light">to</span>
                            <input type="date" wire:model.live="endDate" class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body p-2 p-md-4">
            <!-- Summary Cards - More Responsive -->
            <div class="row g-2 g-md-4">
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm overflow-hidden h-100">
                        <div class="card-body bg-gradient-success text-white p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1 me-2">
                                    <h6 class="text-uppercase mb-1 opacity-75 text-white fs-7 fs-sm-6">Total Revenue</h6>
                                    <h4 class="fw-bold mb-0 fs-5 fs-sm-4 fs-md-3">
                                        {{ number_format($profitLossData['total_revenue'], 0) }}
                                    </h4>
                                </div>
                                <div class="text-end">
                                    <i class="bi bi-graph-up fs-3 fs-sm-2 fs-md-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm overflow-hidden h-100">
                        <div class="card-body bg-gradient-danger text-white p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1 me-2">
                                    <h6 class="text-uppercase mb-1 opacity-75 text-white fs-7 fs-sm-6">Total Expenses</h6>
                                    <h4 class="fw-bold mb-0 fs-5 fs-sm-4 fs-md-3">
                                        {{ number_format($profitLossData['total_expenses'], 0) }}
                                    </h4>
                                </div>
                                <div class="text-end">
                                    <i class="bi bi-graph-down fs-3 fs-sm-2 fs-md-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-12 col-lg-4">
                    <div class="card border-0 shadow-sm overflow-hidden h-100">
                        <div class="card-body bg-gradient-info text-white p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1 me-2">
                                    <h6 class="text-uppercase mb-1 opacity-75 text-white fs-7 fs-sm-6">Net Profit</h6>
                                    <h4 class="fw-bold mb-0 fs-5 fs-sm-4 fs-md-3">
                                        {{ number_format($profitLossData['net_profit'], 0) }}
                                    </h4>
                                </div>
                                <div class="text-end">
                                    <i class="bi bi-cash-coin fs-3 fs-sm-2 fs-md-1 opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Breakdown -->
            <div class="row mt-3 mt-md-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light py-2 py-md-3">
                            <h5 class="mb-0 fs-6 fs-md-5">Detailed Breakdown</h5>
                        </div>
                        <div class="card-body p-2 p-md-3">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <h6 class="text-primary fs-7 fs-md-6">Revenue Breakdown</h6>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <span class="fs-8 fs-sm-7">Interest Income</span>
                                            <span class="badge bg-success rounded-pill fs-8">
                                                {{ number_format($revenueBreakdown['interest_income'] ?? 0, 0) }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <span class="fs-8 fs-sm-7">Document Charges</span>
                                            <span class="badge bg-success rounded-pill fs-8">
                                                {{ number_format($revenueBreakdown['document_charge_income'] ?? 0, 0) }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <span class="fs-8 fs-sm-7">Late Fees</span>
                                            <span class="badge bg-success rounded-pill fs-8">
                                                {{ number_format($revenueBreakdown['late_fee_income'] ?? 0, 0) }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-12 col-md-6">
                                    <h6 class="text-danger fs-7 fs-md-6">Expense Breakdown</h6>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <span class="fs-8 fs-sm-7">Salaries</span>
                                            <span class="badge bg-danger rounded-pill fs-8">
                                                {{ number_format($expenseBreakdown['salary_expense'] ?? 0, 0) }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <span class="fs-8 fs-sm-7">Rent</span>
                                            <span class="badge bg-danger rounded-pill fs-8">
                                                {{ number_format($expenseBreakdown['rent_expense'] ?? 0, 0) }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <span class="fs-8 fs-sm-7">Utilities</span>
                                            <span class="badge bg-danger rounded-pill fs-8">
                                                {{ number_format($expenseBreakdown['utilities_expense'] ?? 0, 0) }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <span class="fs-8 fs-sm-7">Office Supplies</span>
                                            <span class="badge bg-danger rounded-pill fs-8">
                                                {{ number_format($expenseBreakdown['office_supplies_expense'] ?? 0, 0) }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <span class="fs-8 fs-sm-7">Petty Cash Expenses</span>
                                            <span class="badge bg-danger rounded-pill fs-8">
                                                {{ number_format($expenseBreakdown['petty_cash_expenses'] ?? 0, 0) }}
                                            </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <span class="fs-8 fs-sm-7">Other Expenses</span>
                                            <span class="badge bg-danger rounded-pill fs-8">
                                                {{ number_format($expenseBreakdown['other_expenses'] ?? 0, 0) }}
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(to right, #4e73df 0%, #224abe 100%);
    }
    .bg-gradient-success {
        background: linear-gradient(to right, #1cc88a 0%, #13855f 100%);
    }
    .bg-gradient-danger {
        background: linear-gradient(to right, #e74a3b 0%, #be2a1d 100%);
    }
    .bg-gradient-info {
        background: linear-gradient(to right, #36b9cc 0%, #258391 100%);
    }

    /* Custom font sizes for better responsiveness */
    .fs-7 { font-size: 0.875rem !important; }
    .fs-8 { font-size: 0.75rem !important; }

    /* Mobile specific adjustments */
    @media (max-width: 576px) {
        .container-fluid {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        .card-body {
            padding: 1rem !important;
        }

        .fs-sm-7 { font-size: 0.875rem !important; }
        .fs-sm-6 { font-size: 1rem !important; }
        .fs-sm-4 { font-size: 1.25rem !important; }
        .fs-sm-2 { font-size: 1.5rem !important; }
    }

    /* Medium devices */
    @media (min-width: 768px) {
        .fs-md-6 { font-size: 1rem !important; }
        .fs-md-5 { font-size: 1.125rem !important; }
        .fs-md-3 { font-size: 1.75rem !important; }
        .fs-md-1 { font-size: 2.5rem !important; }
    }

    /* Ensure cards have equal height */
    .h-100 {
        height: 100% !important;
    }
</style>
@endpush
