<div>
    <div class="card shadow-sm mb-4 border-0">
        <div class="card-header bg-navy text-white py-3">
            <h2 class="mb-0 text-white">Balance Sheet</h2>
            <p class="mb-0 fs-6">As of {{ date('F d, Y', strtotime($asOfDate)) }}</p>
        </div>
        <div class="card-body bg-light">
            <!-- Branch Filter -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <div class="input-group">
                            <span class="input-group-text bg-navy text-white">Branch</span>
                            <select wire:model.live="branchId" class="form-select border-navy"
                                style="width: 200px;">
                                <option value="">All Branches</option>
                                @foreach (\App\Models\Branch::all() as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <button wire:click="toggleDetails" class="btn btn-outline-navy">
                            {{ $showDetails ? 'Hide Details' : 'Show Details' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Balance Overview Card -->
            <div class="card mb-4 border-0 bg-white">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light-teal">
                                <div class="card-body">
                                    <h5 class="card-title text-teal">Total Assets</h5>
                                    <h2 class="text-teal fw-bold">{{ number_format($totalAssets, 2) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light-gray">
                                <div class="card-body">
                                    <h5 class="card-title text-secondary">Total Liabilities</h5>
                                    <h2 class="text-secondary fw-bold">{{ number_format($totalLiabilities, 2) }}</h2>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 border-0 bg-light-green">
                                <div class="card-body">
                                    <h5 class="card-title text-navy">Total Equity</h5>
                                    <h2 class="text-navy fw-bold">{{ number_format($totalEquity, 2) }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="alert {{ $balancesMatch ? 'alert-success' : 'alert-danger' }} mt-3 text-white">
                        <strong>{{ $balancesMatch ? '✓ Balanced' : '⚠ Unbalanced' }}:</strong>
                        Assets {{ $balancesMatch ? '=' : '≠' }} Liabilities + Equity
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Assets Column -->
                <div class="col-md-6">
                    <div class="card mb-4 h-100 border-success">
                        <div class="card-header bg-navy text-white">
                            <h4 class="mb-0 text-white">Assets</h4>
                        </div>
                        <div class="card-body bg-white">
                            @foreach ($assetAccounts->groupBy('type') as $type => $accounts)
                                @if ($type !== 'cash_drawer')
                                    <h5 class="mt-3 border-bottom pb-2 text-navy">
                                        {{ ucwords(str_replace('_', ' ', $type)) }}</h5>
                                    @if ($showDetails)
                                        @foreach ($accounts as $account)
                                            <div class="d-flex justify-content-between py-1">
                                                <span>{{ $account->account_name }}</span>
                                                <span class="text-end">{{ number_format($account->balance, 2) }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="d-flex justify-content-between py-1">
                                            <span>{{ ucwords(str_replace('_', ' ', $type)) }} Total</span>
                                            <span class="text-end">{{ number_format($accounts->sum('balance'), 2) }}</span>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                            <div class="d-flex justify-content-between py-2 mt-3 border-top border-2">
                                <strong>Total Assets</strong>
                                <strong class="text-end text-teal">{{ number_format($totalAssets, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Liabilities and Equity Column -->
                <div class="col-md-6">
                    <!-- Liabilities -->
                    <div class="card mb-4 border-secondary">
                        <div class="card-header bg-secondary text-white">
                            <h4 class="mb-0 text-white">Liabilities</h4>
                        </div>
                        <div class="card-body bg-white">
                            @if ($liabilityAccounts->count() > 0)
                                @foreach ($liabilityAccounts->groupBy('type') as $type => $accounts)
                                    <h5 class="mt-3 border-bottom pb-2 text-secondary">
                                        {{ ucwords(str_replace('_', ' ', $type)) }}</h5>
                                    @if ($showDetails)
                                        @foreach ($accounts as $account)
                                            <div class="d-flex justify-content-between py-1">
                                                <span>{{ $account->account_name }}</span>
                                                <span class="text-end">{{ number_format($account->balance, 2) }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="d-flex justify-content-between py-1">
                                            <span>{{ ucwords(str_replace('_', ' ', $type)) }} Total</span>
                                            <span class="text-end">{{ number_format($accounts->sum('balance'), 2) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-muted py-3 text-center">No liability accounts</div>
                            @endif
                            <div class="d-flex justify-content-between py-2 mt-3 border-top border-2">
                                <strong>Total Liabilities</strong>
                                <strong class="text-end text-secondary">{{ number_format($totalLiabilities, 2) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Equity -->
                    <div class="card mb-4 border-navy">
                        <div class="card-header bg-navy text-white">
                            <h4 class="mb-0 text-white">Equity</h4>
                        </div>
                        <div class="card-body bg-white">
                            <div class="d-flex justify-content-between py-1">
                                <span>Branch Capital</span>
                                <span class="text-end">{{ number_format($capital, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span>Total Owner Draw</span>
                                <span class="text-end">{{ number_format($ownerDraw, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span>Less: Owner Draw</span>
                                <span class="text-end text-danger">-{{ number_format($ownerDraw, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span>Net Income</span>
                                <span class="text-end {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($netIncome, 2) }}
                                </span>
                            </div>
                            <div class="d-flex justify-content-between py-2 mt-3 border-top border-2">
                                <strong>Total Equity</strong>
                                <strong class="text-end text-navy">{{ number_format($totalEquity, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Income Summary (Revenue and Expenses) -->
            @if ($showDetails)
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4 border-info">
                            <div class="card-header bg-info text-white">
                                <h4 class="mb-0">Revenue</h4>
                            </div>
                            <div class="card-body bg-white">
                                @if ($revenueAccounts->count() > 0)
                                    @foreach ($revenueAccounts->groupBy('type') as $type => $accounts)
                                        <h5 class="mt-3 border-bottom pb-2 text-info">
                                            {{ ucwords(str_replace('_', ' ', $type)) }}</h5>
                                        @foreach ($accounts as $account)
                                            <div class="d-flex justify-content-between py-1">
                                                <span>{{ $account->account_name }}</span>
                                                <span class="text-end">{{ number_format($account->balance, 2) }}</span>
                                            </div>
                                        @endforeach
                                    @endforeach
                                @else
                                    <div class="text-muted py-3 text-center">No revenue accounts</div>
                                @endif
                                <div class="d-flex justify-content-between py-2 mt-3 border-top border-2">
                                    <strong>Total Revenue</strong>
                                    <strong class="text-end text-info">{{ number_format($totalRevenue, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h4 class="mb-0 text-white">Expenses</h4>
                            </div>
                            <div class="card-body bg-white">
                                @if ($expenseAccounts->count() > 0)
                                    @foreach ($expenseAccounts->groupBy('type') as $type => $accounts)
                                        <h5 class="mt-3 border-bottom pb-2 text-danger">
                                            {{ ucwords(str_replace('_', ' ', $type)) }}</h5>
                                        @foreach ($accounts as $account)
                                            <div class="d-flex justify-content-between py-1">
                                                <span>{{ $account->account_name }}</span>
                                                <span class="text-end">{{ number_format($account->balance, 2) }}</span>
                                            </div>
                                        @endforeach
                                    @endforeach
                                @else
                                    <div class="text-muted py-3 text-center">No expense accounts</div>
                                @endif
                                <div class="d-flex justify-content-between py-2 mt-3 border-top border-2">
                                    <strong>Total Expenses</strong>
                                    <strong class="text-end text-danger">{{ number_format($totalExpenses, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>


<!-- Custom Styles for Accounting Colors -->
<style>
.bg-navy {
    background-color: #23395d !important;
}

.text-navy {
    color: #23395d !important;
}

.border-navy {
    border-color: #23395d !important;
}

.bg-light-green {
    background-color: #eafaf1 !important;
}

.bg-light-gray {
    background-color: #f4f6fa !important;
}

.btn-outline-navy {
    border-color: #23395d;
    color: #23395d;
}

.btn-outline-navy:hover {
    background-color: #23395d;
    color: #fff;
}

.bg-light-teal {
    background-color: #e0f7fa !important;
}

.text-teal {
    color: #008080 !important;
}
</style>
</div>
