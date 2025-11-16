<div>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0 text-white">Balance Sheet</h3>
            <p class="mb-0">As of {{ date('F d, Y', strtotime($asOfDate)) }}</p>
        </div>

        <div class="card-body">
            <!-- Branch Filter -->
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <div class="input-group">
                            <span class="input-group-text">Branch</span>
                            <select wire:model.live="branchId" class="form-select" style="width: 200px;">
                                <option value="">All Branches</option>
                                @foreach (\App\Models\Branch::all() as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div>
                        <button wire:click="toggleDetails" class="btn btn-outline-secondary">
                            {{ $showDetails ? 'Hide Details' : 'Show Details' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Balance Overview Card -->
            <div class="card mb-4 border-primary">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card h-100 bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Assets</h5>
                                    <h3 class="text-primary">{{ number_format($totalAssets, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Liabilities</h5>
                                    <h3 class="text-secondary">{{ number_format($totalLiabilities, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card h-100 bg-light">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Total Equity</h5>
                                    <h3 class="text-success">{{ number_format($totalEquity, 2) }}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert {{ $balancesMatch ? 'alert-success' : 'alert-danger' }} mt-3">
                        <strong>{{ $balancesMatch ? '✓ Balanced' : '⚠ Unbalanced' }}:</strong>
                        Assets {{ $balancesMatch ? '=' : '≠' }} Liabilities + Equity
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Assets Column -->
                <div class="col-md-6">
                    <div class="card mb-4 h-100 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h4 class="mb-0 text-white">Assets</h4>
                        </div>
                        <div class="card-body">
                            @foreach ($assetAccounts->groupBy('type') as $type => $accounts)
                                <h5 class="mt-3 border-bottom pb-2">{{ ucwords(str_replace('_', ' ', $type)) }}</h5>
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

                            <div class="d-flex justify-content-between py-2 mt-3 border-top border-2">
                                <strong>Total Assets</strong>
                                <strong class="text-end">{{ number_format($totalAssets, 2) }}</strong>
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
                        <div class="card-body">
                            @if ($liabilityAccounts->count() > 0)
                                @foreach ($liabilityAccounts->groupBy('type') as $type => $accounts)
                                    <h5 class="mt-3 border-bottom pb-2">{{ ucwords(str_replace('_', ' ', $type)) }}
                                    </h5>
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
                                            <span
                                                class="text-end">{{ number_format($accounts->sum('balance'), 2) }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            @else
                                <div class="text-muted py-3 text-center">No liability accounts</div>
                            @endif

                            <div class="d-flex justify-content-between py-2 mt-3 border-top border-2">
                                <strong>Total Liabilities</strong>
                                <strong class="text-end">{{ number_format($totalLiabilities, 2) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Equity -->
                    <div class="card mb-4 border-success">
                        <div class="card-header bg-success text-white">
                            <h4 class="mb-0">Equity</h4>
                        </div>
                        <div class="card-body">
                            @foreach ($equityAccounts->groupBy('type') as $type => $accounts)
                                <h5 class="mt-3 border-bottom pb-2">{{ ucwords(str_replace('_', ' ', $type)) }}</h5>
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

                            <!-- Net Income -->
                            <h5 class="mt-3 border-bottom pb-2">Net Income</h5>
                            <div class="d-flex justify-content-between py-1">
                                <span>Net Income (Revenue - Expenses)</span>
                                <span class="text-end {{ $netIncome >= 0 ? 'text-success' : 'text-danger' }}">
                                    {{ number_format($netIncome, 2) }}
                                </span>
                            </div>

                            <div class="d-flex justify-content-between py-2 mt-3 border-top border-2">
                                <strong>Total Equity</strong>
                                <strong class="text-end">{{ number_format($totalEquity, 2) }}</strong>
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
                            <div class="card-body">
                                @if ($revenueAccounts->count() > 0)
                                    @foreach ($revenueAccounts->groupBy('type') as $type => $accounts)
                                        <h5 class="mt-3 border-bottom pb-2">{{ ucwords(str_replace('_', ' ', $type)) }}
                                        </h5>
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
                                    <strong class="text-end">{{ number_format($totalRevenue, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4 border-danger">
                            <div class="card-header bg-danger text-white">
                                <h4 class="mb-0 text-white">Expenses</h4>
                            </div>
                            <div class="card-body">
                                @if ($expenseAccounts->count() > 0)
                                    @foreach ($expenseAccounts->groupBy('type') as $type => $accounts)
                                        <h5 class="mt-3 border-bottom pb-2">{{ ucwords(str_replace('_', ' ', $type)) }}
                                        </h5>
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
                                    <strong class="text-end">{{ number_format($totalExpenses, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
