{{-- filepath: f:\Finance\RDI\Finance - Copy\resources\views\livewire\account\general-ledger.blade.php --}}

<div class="general-ledger-container">
    <!-- Header Section -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-left">
                <div class="page-icon">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="page-title">
                    <h1>General Ledger</h1>
                    <p class="subtitle">Transaction Register & Account History</p>
                </div>
            </div>
            <div class="header-actions">
                <button wire:click="exportExcel" class="btn-modern btn-export">
                    <i class="fas fa-download"></i>
                    <span class="btn-text">Export Excel</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="filters-card">
        <div class="filters-header">
            <h3 class="text-white"><i class="fas fa-filter"></i> Filters</h3>
        </div>
        <div class="filters-body">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">From Date</label>
                    <div class="input-wrapper">
                        <i class="fas fa-calendar-alt input-icon"></i>
                        <input type="date" wire:model.live="from_date" class="modern-input">
                    </div>
                </div>
                <div class="filter-group">
                    <label class="filter-label">To Date</label>
                    <div class="input-wrapper">
                        <i class="fas fa-calendar-alt input-icon"></i>
                        <input type="date" wire:model.live="to_date" class="modern-input">
                    </div>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Search Transactions</label>
                    <div class="input-wrapper">
                        <i class="fas fa-search input-icon"></i>
                        <input type="text" wire:model.live.debounce.300ms="search"
                               placeholder="Search by description, account..." class="modern-input">
                    </div>
                </div>
                <div class="filter-actions">
                    <button wire:click="clearFilters" class="btn-clear">
                        <i class="fas fa-times"></i>
                        Clear All
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards (Now 3 cards) -->
    <div class="stats-grid-3">
        <div class="stat-card stat-today">
            <div class="stat-icon">
                <i class="fas fa-calendar-day"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">Rs. {{ number_format($todayAmount, 2) }}</div>
                <div class="stat-label">Today's Amount</div>
                <div class="stat-sub">{{ \Carbon\Carbon::today()->format('M d, Y') }}</div>
            </div>
        </div>
        <div class="stat-card stat-filtered">
            <div class="stat-icon">
                <i class="fas fa-filter"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">Rs. {{ number_format($totalAmount, 2) }}</div>
                <div class="stat-label">Filtered Total Amount</div>
                <div class="stat-sub">Based on selected filters</div>
            </div>
        </div>
        <div class="stat-card stat-monthly-avg">
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">
                    Rs. {{ number_format($monthlyDailyAverage, 2) }}
                </div>
                <div class="stat-label">Monthly Daily Average</div>
                <div class="stat-sub">{{ \Carbon\Carbon::now()->format('M Y') }}</div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="transactions-card">
        <div class="table-header">
            <h3><i class="fas fa-list"></i> Transaction Details</h3>
            <div class="table-info">
                Showing {{ $transactions->firstItem() ?? 0 }} - {{ $transactions->lastItem() ?? 0 }}
                of {{ $transactions->total() }} transactions
            </div>
        </div>

        <div class="modern-table-wrapper">
            <table class="modern-table styled-table">
                <thead class="table-navy text-white">
                    <tr>
                        <th style="width: 110px;">Date & Time</th>
                        <th style="width: 140px;">Description</th>
                        <th style="width: 180px;">Debit Account</th>
                        <th style="width: 180px;">Credit Account</th>
                        <th style="width: 120px;" class="text-end">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="transaction-row">
                            <td style="max-width:110px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <div class="date-primary">{{ $transaction->created_at->format('M d, Y') }}</div>
                                <div class="date-secondary">{{ $transaction->created_at->format('h:i A') }}</div>
                            </td>
                            <td style="max-width:140px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <div class="description-primary">
                                    {{ $transaction->description ?? 'Transaction Entry' }}
                                </div>
                                @if($transaction->reference)
                                    <div class="description-secondary">
                                        <i class="fas fa-hashtag"></i> {{ $transaction->reference }}
                                    </div>
                                @endif
                            </td>
                            <td style="max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <div class="account-badge debit-badge">
                                    {{ $transaction->debitAccount->account_name ?? 'Unknown Account' }}
                                    <span class="account-type">({{ ucfirst($transaction->debitAccount->category ?? 'unknown') }})</span>
                                </div>
                                @if($transaction->debitAccount && $transaction->debitAccount->account_code)
                                    <div class="account-code">{{ $transaction->debitAccount->account_code }}</div>
                                @endif
                            </td>
                            <td style="max-width:180px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                <div class="account-badge credit-badge">
                                    {{ $transaction->creditAccount->account_name ?? 'Unknown Account' }}
                                    <span class="account-type">({{ ucfirst($transaction->creditAccount->category ?? 'unknown') }})</span>
                                </div>
                                @if($transaction->creditAccount && $transaction->creditAccount->account_code)
                                    <div class="account-code">{{ $transaction->creditAccount->account_code }}</div>
                                @endif
                            </td>
                            <td class="amount-cell text-end">
                                <div class="amount-value">Rs. {{ number_format($transaction->amount, 2) }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="empty-state">
                                <div class="empty-content">
                                    <div class="empty-icon">
                                        <i class="fas fa-inbox"></i>
                                    </div>
                                    <h4>No Transactions Found</h4>
                                    <p>Try adjusting your filters or date range to see transactions</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="card-footer px-3 py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="text-xs text-secondary mb-0">
                        Showing <span class="font-weight-bold">{{ $transactions->firstItem() ?? 0 }}</span>
                        to <span class="font-weight-bold">{{ $transactions->lastItem() ?? 0 }}</span>
                        of <span class="font-weight-bold">{{ $transactions->total() }}</span> entries
                    </p>
                    <div>
                        {{ $transactions->links('livewire::bootstrap') }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Loading Overlay -->
    <div wire:loading class="loading-overlay">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <p>Loading transactions...</p>
        </div>
    </div>



<style>
/* Modern General Ledger Styles */
body, .general-ledger-container {
    background-color: #f4f8fb !important;
}

.general-ledger-container {
    padding: 16px;
    background: #f4f8fb;
    min-height: 100vh;
}

/* Header Styles - Base */
.page-header {
    background: #23395d;
    border-radius: 1rem;
    padding: 18px 24px;
    margin-bottom: 18px;
    box-shadow: 0 4px 16px rgba(44,62,80,0.08);
    color: #fff;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1; /* This makes left section take all available space */
    min-width: 0;
}

.page-icon {
    width: 40px;
    height: 40px;
    background: #008080;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 20px;
    margin-right: 12px;
}

.page-title {
    flex: 1;
    min-width: 0;
}

.page-title h1 {
    font-size: 22px;
    font-weight: 700;
    color: #fff;
    margin: 0;
}

.subtitle {
    color: #b2becd;
    margin: 2px 0 0 0;
    font-size: 13px;
}

.header-actions {
    margin-left: auto;
    display: flex;
    align-items: center;
}

.header-actions .btn-modern {
    background: #008080;
    color: #fff;
    border-radius: 1rem;
    padding: 8px 18px;
    font-size: 14px;
    font-weight: 600;
    border: none;
    box-shadow: 0 2px 8px rgba(44,62,80,0.08);
}

.header-actions .btn-modern:hover {
    background: #006666;
}

/* Filters Card */
.filters-card {
    background: #fff;
    border-radius: 1rem;
    margin-bottom: 18px;
    box-shadow: 0 2px 8px rgba(44,62,80,0.08);
}

.filters-header {
    background: #23395d;
    color: #fff;
    padding: 10px 18px;
    border-radius: 1rem 1rem 0 0;
    font-size: 16px;
    font-weight: 600;
}

.filters-body {
    padding: 18px;
}

.filter-label {
    font-weight: 600;
    color: #23395d;
    font-size: 13px;
}

.input-wrapper {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    color: #008080;
    z-index: 1;
    font-size: 15px;
}

.modern-input {
    width: 100%;
    padding: 8px 12px 8px 36px;
    border: 2px solid #008080;
    border-radius: 1rem;
    background: #fff;
    font-size: 13px;
    transition: all 0.3s ease;
}

.modern-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-clear {
    background: #e3342f;
    color: #fff;
    border-radius: 1rem;
    padding: 8px 16px;
    font-size: 13px;
    font-weight: 600;
    border: none;
}

.btn-clear:hover {
    background: #c82333;
}

.filter-actions {
    width: 100%;
    justify-content: center;
    align-items: center;
    display: flex;
    margin-top: 18px;
    margin-left: 0;
}

.filter-actions {
    margin-left: 24px;
    display: flex;
    align-items: center;
    gap: 8px;
}

/* Statistics Cards - 3 Card Layout */
.stats-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 18px;
}

.stat-card {
    background: #23395d;
    border-radius: 1rem;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 2px 8px rgba(44,62,80,0.08);
    color: #fff;
}

.stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: #008080;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
}

.stat-number {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    line-height: 1;
}

.stat-label {
    color: #b2becd;
    font-size: 13px;
    font-weight: 500;
}

.stat-sub {
    color: #b2becd;
    font-size: 11px;
    font-weight: 400;
    margin-top: 2px;
}

/* Transactions Table */
.transactions-card {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.table-header {
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    padding: 20px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #e2e8f0;
}

.table-header h3 {
    margin: 0;
    color: #2d3748;
    font-size: 18px;
    font-weight: 600;
}

.table-info {
    color: #718096;
    font-size: 14px;
}

.modern-table-wrapper {
    overflow-x: auto;
}

.modern-table {
    width: 100%;
    border-collapse: collapse;
}

.modern-table th {
    background: #f8f9fa;
    padding: 16px;
    text-align: left;
    font-weight: 600;
    color: #495057;
    font-size: 14px;
    border-bottom: 2px solid #e9ecef;
}

.transaction-row {
    border-bottom: 1px solid #f1f3f4;
    transition: background-color 0.2s ease;
}

.transaction-row:hover {
    background: #f8f9ff;
}

.modern-table td {
    padding: 16px;
    vertical-align: top;
}

.date-primary {
    font-weight: 600;
    color: #2d3748;
    font-size: 14px;
}

.date-secondary {
    color: #718096;
    font-size: 12px;
    margin-top: 2px;
}

.description-primary {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 4px;
}

.description-secondary {
    color: #718096;
    font-size: 12px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Enhanced Account Badge Styles */
.account-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 6px;
    min-width: 200px;
    position: relative;
}

/* Debit Account Styles */
.debit-badge.increase {
    background: linear-gradient(135deg, rgba(72, 187, 120, 0.15), rgba(56, 161, 105, 0.1));
    color: #2f855a;
    border: 1px solid rgba(72, 187, 120, 0.3);
}

.debit-badge.decrease {
    background: linear-gradient(135deg, rgba(245, 101, 101, 0.15), rgba(229, 62, 62, 0.1));
    color: #c53030;
    border: 1px solid rgba(245, 101, 101, 0.3);
}

/* Credit Account Styles */
.credit-badge.increase {
    background: linear-gradient(135deg, rgba(66, 153, 225, 0.15), rgba(49, 130, 206, 0.1));
    color: #3182ce;
    border: 1px solid rgba(66, 153, 225, 0.3);
}

.credit-badge.decrease {
    background: linear-gradient(135deg, rgba(237, 137, 54, 0.15), rgba(221, 107, 32, 0.1));
    color: #dd6b20;
    border: 1px solid rgba(237, 137, 54, 0.3);
}

/* Account Type Label */
.account-type {
    font-size: 10px;
    font-weight: 500;
    opacity: 0.8;
    margin-left: 4px;
}

/* Balance Effect Indicator */
.balance-effect {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 8px;
    border-radius: 8px;
    display: inline-block;
    margin-top: 4px;
}

.balance-effect.positive {
    background: rgba(72, 187, 120, 0.1);
    color: #2f855a;
    border: 1px solid rgba(72, 187, 120, 0.2);
}

.balance-effect.negative {
    background: rgba(245, 101, 101, 0.1);
    color: #c53030;
    border: 1px solid rgba(245, 101, 101, 0.2);
}

/* Account Code Enhancement */
.account-code {
    font-size: 10px;
    color: #a0aec0;
    font-family: 'Courier New', monospace;
    background: rgba(160, 174, 192, 0.1);
    padding: 2px 6px;
    border-radius: 4px;
    display: inline-block;
    margin-top: 2px;
}

/* Arrow Icons Enhancement */
.account-badge i {
    font-size: 14px;
    padding: 2px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
}

/* Hover Effects */
.account-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transition: all 0.2s ease;
}

/* Legend for Account Categories (Optional - add at top of table) */
.account-legend {
    display: flex;
    gap: 16px;
    margin-bottom: 16px;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    font-size: 11px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 4px;
}

.legend-color {
    width: 12px;
    height: 12px;
    border-radius: 3px;
}

.amount-value {
    font-size: 16px;
    font-weight: 700;
    color: #2d3748;
    text-align: right;
}

.empty-state {
    padding: 60px 20px;
    text-align: center;
}

.empty-icon {
    font-size: 48px;
    color: #cbd5e0;
    margin-bottom: 16px;
}

.empty-content h4 {
    color: #4a5568;
    margin-bottom: 8px;
}

.empty-content p {
    color: #718096;
}

/* Loading Overlay */
.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.loading-content {
    text-align: center;
}

.loading-spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #e2e8f0;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 16px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive Design for 3 Cards */
@media (max-width: 1200px) {
    .stats-grid-3 {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Fixed responsive design for proper button positioning */
@media (max-width: 768px) {
    .general-ledger-container {
        padding: 16px;
    }

    .filter-row {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .stats-grid-3 {
        grid-template-columns: 1fr;
    }

    /* FORCE header to stay horizontal with proper positioning */
    .header-content {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center !important;
        width: 100% !important;
        gap: 12px !important;
        flex-wrap: nowrap !important;
    }

    /* Header left section - takes remaining space */
    .header-left {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 8px !important;
        flex: 1 !important;
        min-width: 0 !important;
        overflow: hidden !important;
    }

    /* Header actions - FORCE to right side */
    .header-actions {
        flex: 0 0 auto !important;
        flex-shrink: 0 !important;
        margin-left: auto !important;
        display: flex !important;
        align-items: center !important;
    }

    /* Compact page icon */
    .page-icon {
        width: 40px !important;
        height: 40px !important;
        font-size: 18px !important;
        flex-shrink: 0 !important;
    }

    /* Compact title text */
    .page-title {
        flex: 1 !important;
        min-width: 0 !important;
        overflow: hidden !important;
    }

    .page-title h1 {
        font-size: 18px !important;
        line-height: 1.2 !important;
        margin: 0 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    .subtitle {
        font-size: 11px !important;
        line-height: 1.2 !important;
        margin: 2px 0 0 0 !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    /* Compact export button - FORCE positioning */
    .btn-modern {
        padding: 8px 12px !important;
        font-size: 14px !important;
        min-width: 40px !important;
        white-space: nowrap !important;
        flex-shrink: 0 !important;
        margin-left: 8px !important;
    }

    .btn-text {
        display: none !important;
    }

    .btn-modern {
        gap: 0 !important;
    }
}

@media (max-width: 480px) {
    .page-header {
        padding: 12px 16px !important;
    }

    .header-content {
        gap: 8px !important;
    }

    .header-left {
        gap: 6px !important;
    }

    .page-title h1 {
        font-size: 16px !important;
    }

    .subtitle {
        font-size: 10px !important;
    }

    .btn-modern {
        padding: 6px 10px !important;
        min-width: 36px !important;
        font-size: 12px !important;
    }

    .page-icon {
        width: 36px !important;
        height: 36px !important;
        font-size: 16px !important;
    }
}

/* Extra small screens - FORCE layout */
@media (max-width: 320px) {
    .header-content {
        display: flex !important;
        flex-direction: row !important;
        justify-content: space-between !important;
        align-items: center !important;
    }

    .header-left {
        flex-direction: row !important;
        gap: 4px !important;
    }

    .page-title h1 {
        font-size: 14px !important;
    }

    .subtitle {
        display: none !important;
    }

    .btn-modern {
        padding: 6px 8px !important;
        min-width: 32px !important;
    }
}

@media print {
    .header-actions, .filters-card, .btn-clear, .loading-overlay {
        display: none !important;
    }

    .general-ledger-container {
        background: white;
        padding: 0;
    }
}

/* Custom styles for the transactions table */
.styled-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 1rem;
    overflow: hidden;
    background: #fff;
    font-size: 0.95rem;
}
.table-navy th {
    background-color: #23395d !important;
    color: #fff !important;
    font-size: 1rem;
    font-weight: 700;
    letter-spacing: 0.03em;
    padding: 0.7em 0.5em;
    border-bottom: 2px solid #008080;
    text-align: left;
    vertical-align: middle;
}
.styled-table td {
    padding: 0.6em 0.5em;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.styled-table tr:last-child td {
    border-bottom: none;
}
.amount-cell {
    text-align: right;
}
.styled-table th, .styled-table td {
    border-right: 1px solid #e9ecef;
}
.styled-table th:last-child, .styled-table td:last-child {
    border-right: none;
}
.styled-table tr:nth-child(even) {
    background-color: #f8f9fa;
}
.styled-table tr:hover {
    background-color: #e0f7fa;
}
</style>
</div>
