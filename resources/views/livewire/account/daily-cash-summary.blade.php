{{-- <div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card card-background card-background-after-none align-items-start">
                <div class="full-background" style="background-image: linear-gradient(310deg, #141727 0%, #3a416f 100%)">
                </div>
                <div class="card-body text-start p-4 w-100">
                    <div class="row align-items-center justify-content-between">
                        <!-- Left Side: Branch Selection -->
                        <div class="col-md-6">
                            <label class="text-white text-sm opacity-8 mb-2">Select Branch</label>
                            @if ($showBranchFilter)
                                <select class="form-control bg-dark text-white" wire:model.live="branch_id">
                                    <option value="all" class="text-white">All Branches</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" class="text-white">{{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <p class="text-white fw-bold h5">
                                    {{ $branches->first()->name ?? 'No Branch Assigned' }}
                                </p>
                            @endif
                        </div>

                        <!-- Right Side: Date Selection -->
                        <div class="col-md-4 text-end">
                            <label class="text-white text-sm opacity-8 mb-2">Select Date</label>
                            <input type="date" class="form-control bg-dark text-white"
                                wire:model.live="selectedDate" />
                        </div>
                    </div>
                </div>
            </div>


            @if ($branch_id !== null)
                <div class="card mt-4">
                    <div class="card-header pb-0 p-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-2">
                                Summary Report
                                @if ($selectedDate)
                                    <span class="text-sm text-muted">
                                        - {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('F d, Y') }}
                                    </span>
                                @endif
                            </h6>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-hover align-items-center mb-0">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                    Particulars</th>
                                                <th
                                                    class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2 text-end">
                                                    Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $summaryItems = [
                                                    ['Opening Balance', $summary['opening_balance'], 'success'],
                                                    ['Principal Collected', $summary['principal_collected'], ''],
                                                    ['Interest Collected', $summary['interest_collected'], ''],
                                                    ['Penalties Collected', $summary['penalties_collected'], ''],
                                                    ['Staff Total Collection', $summary['staff_total_collection'], ''],
                                                    ['Total Collected', $summary['total_collected'], 'info'],
                                                    [
                                                        'Staff Transfers to Cashier',
                                                        $summary['staff_transfers_to_cashier'],
                                                        '',
                                                    ],
                                                    [
                                                        'Cashier Transfers to Capital',
                                                        $summary['cashier_transfers_to_capital'],
                                                        '',
                                                    ],
                                                    [
                                                        'Transfers from Capital to Cashier',
                                                        $summary['transfers_from_capital_to_cashier'],
                                                        '',
                                                    ],
                                                    ['Closing Balance', $summary['closing_balance'], 'success'],
                                                ];
                                            @endphp

                                            @foreach ($summaryItems as $item)
                                                <tr class="{{ $item[2] ? 'table-' . $item[2] : '' }}">
                                                    <td>
                                                        <div class="d-flex px-2 py-1">
                                                            <div class="d-flex flex-column justify-content-center">
                                                                <h6 class="mb-0 text-sm">{{ $item[0] }}</h6>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="align-middle text-end">
                                                        <span class="text-dark font-weight-bold">
                                                            {{ number_format($item[1], 2) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-warning text-center mt-4">
                    No branches assigned. Please contact the administrator.
                </div>
            @endif
        </div>





    </div>
</div> --}}
<div class="card shadow-lg mb-4">
    <div class="card-header bg-gradient-primary text-white py-3">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h3 class="mb-0 text-white"><i class="fas fa-calendar-day  me-2"></i>Daily Cash Summary</h3>
            <span class="badge bg-light text-primary">{{ date('l, F d, Y', strtotime($date)) }}</span>
        </div>

        <div class="row g-3 mt-2">
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-calendar text-primary"></i>
                    </span>
                    <input type="date" wire:model.live="date" class="form-control border-0 shadow-sm" id="datePicker">
                </div>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <span class="input-group-text bg-white border-0">
                        <i class="fas fa-building text-primary"></i>
                    </span>
                    <select wire:model.live="branchId" class="form-select border-0 shadow-sm">
                        <option value="">All Branches</option>
                        @foreach(\App\Models\Branch::all() as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-light shadow-sm" wire:click="$toggle('showDetails')">
                    <i class="fas fa-{{ $showDetails ? 'eye-slash' : 'eye' }} me-1"></i>
                    {{ $showDetails ? 'Hide Details' : 'Show Details' }}
                </button>
                {{-- <button class="btn btn-light shadow-sm ms-2" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print
                </button> --}}
            </div>
        </div>
    </div>

    <div class="card-body">
        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm bg-gradient-light">
                    <div class="card-body text-center position-relative">
                        <div class="position-absolute top-0 start-50 translate-middle-x mt-3">
                            <i class="fas fa-wallet text-primary fa-3x opacity-10"></i>
                        </div>
                        <h5 class="text-muted mt-5 mb-3">Opening Balance</h5>
                        <h3 class="text-primary mb-0">{{ number_format($openingBalance, 2) }}</h3>
                        <small class="text-muted">Previous day closing</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm bg-gradient-light">
                    <div class="card-body text-center position-relative">
                        <div class="position-absolute top-0 start-50 translate-middle-x mt-3">
                            <i class="fas fa-arrow-circle-down text-success fa-3x opacity-10"></i>
                        </div>
                        <h5 class="text-muted mt-5 mb-3">Total Cash In</h5>
                        <h3 class="text-success mb-0">{{ number_format($totalCashIn, 2) }}</h3>
                        <small class="text-muted">{{ $cashIn->count() }} transaction(s)</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm bg-gradient-light">
                    <div class="card-body text-center position-relative">
                        <div class="position-absolute top-0 start-50 translate-middle-x mt-3">
                            <i class="fas fa-arrow-circle-up text-danger fa-3x opacity-10"></i>
                        </div>
                        <h5 class="text-muted mt-5 mb-3">Total Cash Out</h5>
                        <h3 class="text-danger mb-0">{{ number_format($totalCashOut, 2) }}</h3>
                        <small class="text-muted">{{ $cashOut->count() }} transaction(s)</small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm bg-primary text-white">
                    <div class="card-body text-center position-relative">
                        <div class="position-absolute top-0 start-50 translate-middle-x mt-3">
                            <i class="fas fa-balance-scale fa-3x opacity-10"></i>
                        </div>
                        <h5 class="text-white mt-5 mb-3">Closing Balance</h5>
                        <h3 class="mb-0 text-white">{{ number_format($closingBalance, 2) }}</h3>
                        <small class="text-white">End of day</small>
                    </div>
                </div>
            </div>
        </div>


        <!-- Cash Flow Visual Chart -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-muted mb-3">Cash Flow Overview</h5>

                <div class="progress mt-2" style="height: 35px;">
                    <div class="progress-bar bg-primary text-white d-flex align-items-center justify-content-center"
                        role="progressbar"
                        style="width: {{ max(15, min(100, ($openingBalance / max(1, $openingBalance + $totalCashIn)) * 100)) }}%;
                               min-width: 15%;
                               line-height: 35px; font-size: 14px; font-weight: bold;"
                        aria-valuenow="{{ $openingBalance }}" aria-valuemin="0"
                        aria-valuemax="{{ $openingBalance + $totalCashIn }}">
                        Opening ({{ number_format($openingBalance, 2) }})
                    </div>
                </div>

                <div class="progress mt-2" style="height: 35px;">
                    <div class="progress-bar bg-success text-white d-flex align-items-center justify-content-center"
                        role="progressbar"
                        style="width: {{ max(15, min(100, ($totalCashIn / max(1, $openingBalance + $totalCashIn)) * 100)) }}%;
                               min-width: 15%;
                               line-height: 35px; font-size: 14px; font-weight: bold;"
                        aria-valuenow="{{ $totalCashIn }}" aria-valuemin="0"
                        aria-valuemax="{{ $openingBalance + $totalCashIn }}">
                        Cash In ({{ number_format($totalCashIn, 2) }})
                    </div>
                </div>

                <div class="progress mt-2" style="height: 35px;">
                    <div class="progress-bar bg-danger text-white d-flex align-items-center justify-content-center"
                        role="progressbar"
                        style="width: {{ max(15, min(100, ($totalCashOut / max(1, $openingBalance + $totalCashIn)) * 100)) }}%;
                               min-width: 15%;
                               line-height: 35px; font-size: 14px; font-weight: bold;"
                        aria-valuenow="{{ $totalCashOut }}" aria-valuemin="0"
                        aria-valuemax="{{ $openingBalance + $totalCashIn }}">
                        Cash Out ({{ number_format($totalCashOut, 2) }})
                    </div>
                </div>

                <div class="progress mt-2" style="height: 35px;">
                    <div class="progress-bar bg-info text-white d-flex align-items-center justify-content-center"
                        role="progressbar"
                        style="width: {{ max(15, min(100, ($closingBalance / max(1, $openingBalance + $totalCashIn)) * 100)) }}%;
                               min-width: 15%;
                               line-height: 35px; font-size: 14px; font-weight: bold;"
                        aria-valuenow="{{ $closingBalance }}" aria-valuemin="0"
                        aria-valuemax="{{ $openingBalance + $totalCashIn }}">
                        Closing ({{ number_format($closingBalance, 2) }})
                    </div>
                </div>

                <div class="d-flex justify-content-between mt-3 text-muted small">
                    <span>Opening: {{ number_format($openingBalance, 2) }}</span>
                    <span>In: +{{ number_format($totalCashIn, 2) }}</span>
                    <span>Out: -{{ number_format($totalCashOut, 2) }}</span>
                    <span>Closing: {{ number_format($closingBalance, 2) }}</span>
                </div>
            </div>
        </div>


        <!-- Cash Account Breakdown -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title text-muted mb-3">Cash Account Breakdown</h5>
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <thead class="table-light">
                            <tr>
                                <th>Account</th>
                                <th class="text-end">Opening</th>
                                <th class="text-end">Cash In</th>
                                <th class="text-end">Cash Out</th>
                                <th class="text-end">Closing</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cashAccounts as $account)
                            <tr class="border-bottom">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-light rounded-circle me-2 d-flex align-items-center justify-content-center">
                                            <i class="fas fa-coins text-warning"></i>
                                        </div>
                                        <div>
                                            <div>{{ $account->account_name }}</div>
                                            <small class="text-muted">{{ $account->account_number }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">{{ number_format($account->opening_balance ?? 0, 2) }}</td>
                                <td class="text-end text-success">
                                    @php
                                        $inAmount = $cashIn->where('debit_account_id', $account->id)->sum('amount');
                                    @endphp
                                    +{{ number_format($inAmount, 2) }}
                                </td>
                                <td class="text-end text-danger">
                                    @php
                                        $outAmount = $cashOut->where('credit_account_id', $account->id)->sum('amount');
                                    @endphp
                                    -{{ number_format($outAmount, 2) }}
                                </td>
                                <td class="text-end fw-bold">
                                    {{ number_format(($account->opening_balance ?? 0) + $inAmount - $outAmount, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Transaction Details -->
        @if($showDetails)
        <div class="row g-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-gradient-success text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Cash Inflows</h5>
                            <span class="badge bg-white text-success">{{ number_format($totalCashIn, 2) }}</span>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($cashIn as $transaction)
                            <div class="list-group-item border-0 border-bottom py-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="mb-1">{{ $transaction->description }}</h6>
                                        <div class="d-flex align-items-center text-muted small">
                                            <span class="me-2">
                                                <i class="fas fa-clock me-1"></i>{{ $transaction->created_at->format('h:i A') }}
                                            </span>
                                            <span class="me-2">
                                                <i class="fas fa-tag me-1"></i>{{ $transaction->transaction_type ?? 'Transaction' }}
                                            </span>
                                            <span>
                                                <i class="fas fa-exchange-alt me-1"></i>From: {{ $transaction->creditAccount->account_name }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="text-success fw-bold text-end">
                                        {{ number_format($transaction->amount, 2) }}
                                        <div class="text-muted small">{{ $transaction->debitAccount->account_name }}</div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                <p>No cash inflows recorded for this day</p>
                            </div>
                            @endforelse
                        </div>

                        @if($cashInTotal > $perPage)
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Showing {{ ($cashInPage - 1) * $perPage + 1 }}-{{ min($cashInPage * $perPage, $cashInTotal) }} of {{ $cashInTotal }}
                            </small>
                            <div class="btn-group">
                                <button wire:click="previousCashInPage" class="btn btn-sm btn-outline-secondary" {{ $cashInPage == 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button wire:click="nextCashInPage" class="btn btn-sm btn-outline-secondary"
                                        {{ $cashInPage * $perPage >= $cashInTotal ? 'disabled' : '' }}>
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-gradient-danger text-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class="fas fa-hand-holding-usd me-2"></i>Cash Outflows</h5>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-white text-danger me-3">{{ number_format($totalCashOut, 2) }}</span>
                                <span class="badge bg-white text-dark">{{ $cashOut->count() }} transactions</span>
                            </div>
                        </div>
                    </div>

                    <div class="list-group list-group-flush">
                        @forelse($cashOut as $transaction)
                        <div class="list-group-item border-0 border-bottom py-3">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="mb-1">{{ $transaction->description }}</h6>
                                    <div class="d-flex align-items-center text-muted small">
                                        <span class="me-2">
                                            <i class="fas fa-clock me-1"></i>{{ $transaction->created_at->format('h:i A') }}
                                        </span>
                                        <span class="me-2">
                                            <i class="fas fa-tag me-1"></i>{{ $transaction->transaction_type ?? 'Transaction' }}
                                        </span>
                                        <span>
                                            <i class="fas fa-exchange-alt me-1"></i>To: {{ $transaction->debitAccount->account_name }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-danger fw-bold text-end">
                                    {{ number_format($transaction->amount, 2) }}
                                    <div class="text-muted small">{{ $transaction->creditAccount->account_name }}</div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                            <p>No cash outflows recorded for this day</p>
                        </div>
                        @endforelse
                    </div>

                    @if($cashOutTotal > $perPage)
                    <div class="card-footer d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Showing {{ ($cashOutPage - 1) * $perPage + 1 }}-{{ min($cashOutPage * $perPage, $cashOutTotal) }} of {{ $cashOutTotal }}
                        </small>
                        <div class="btn-group">
                            <button wire:click="previousCashOutPage" class="btn btn-sm btn-outline-secondary" {{ $cashOutPage == 1 ? 'disabled' : '' }}>
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button wire:click="nextCashOutPage" class="btn btn-sm btn-outline-secondary"
                                    {{ $cashOutPage * $perPage >= $cashOutTotal ? 'disabled' : '' }}>
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    @endif

                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Cash Reconciliation Alert -->
        <div class="card mt-4 border-0 shadow-sm {{ $closingBalance >= 0 ? 'bg-light' : 'bg-danger text-white' }}">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-calculator fa-2x {{ $closingBalance >= 0 ? 'text-primary' : 'text-white' }} me-3"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h4 class="mb-1">Final Cash Balance: {{ number_format($closingBalance, 2) }}</h4>
                        <p class="mb-0 {{ $closingBalance >= 0 ? 'text-muted' : 'text-white-50' }}">
                            Opening ({{ number_format($openingBalance, 2) }}) + Cash In ({{ number_format($totalCashIn, 2) }}) - Cash Out ({{ number_format($totalCashOut, 2) }})
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        @if($closingBalance < 0)
                            <span class="badge bg-warning text-dark">Negative Balance</span>
                        @else
                            <span class="badge bg-success">Positive Balance</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
