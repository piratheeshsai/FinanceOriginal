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
            <h3><i class="fas fa-filter"></i> Filters</h
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
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Date & Time</th>
                        <th>Description</th>
                        <th>Debit Account</th>
                        <th>Credit Account</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $transaction)
                        <tr class="transaction-row">
                            <td class="date-cell">
                                <div class="date-primary">{{ $transaction->created_at->format('M d, Y') }}</div>
                                <div class="date-secondary">{{ $transaction->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="description-cell">
                                <div class="description-primary">
                                    {{ $transaction->description ?? 'Transaction Entry' }}
                                </div>
                                @if($transaction->reference)
                                    <div class="description-secondary">
                                        <i class="fas fa-hashtag"></i> {{ $transaction->reference }}
                                    </div>
                                @endif
                            </td>
                            <td class="account-cell">
                                @php
                                    $debitAccount = $transaction->debitAccount;
                                    $debitCategory = $debitAccount->category ?? 'unknown';

                                    // Determine if this is an increase or decrease for debit account
                                    $isDebitIncrease = in_array($debitCategory, ['asset', 'expense']);
                                @endphp

                                <div class="account-badge debit-badge {{ $isDebitIncrease ? 'increase' : 'decrease' }}">
                                    <i class="fas fa-arrow-{{ $isDebitIncrease ? 'up' : 'down' }}"></i>
                                    {{ $debitAccount->account_name ?? 'Unknown Account' }}
                                    <span class="account-type">({{ ucfirst($debitCategory) }})</span>
                                </div>
                                @if($debitAccount && $debitAccount->account_code)
                                    <div class="account-code">{{ $debitAccount->account_code }}</div>
                                @endif
                                <div class="balance-effect {{ $isDebitIncrease ? 'positive' : 'negative' }}">
                                    {{ $isDebitIncrease ? '+' : '-' }} Rs. {{ number_format($transaction->amount, 2) }}
                                </div>
                            </td>

                            <td class="account-cell">
                                @php
                                    $creditAccount = $transaction->creditAccount;
                                    $creditCategory = $creditAccount->category ?? 'unknown';

                                    // Determine if this is an increase or decrease for credit account
                                    $isCreditIncrease = in_array($creditCategory, ['liability', 'equity', 'revenue']);
                                @endphp

                                <div class="account-badge credit-badge {{ $isCreditIncrease ? 'increase' : 'decrease' }}">
                                    <i class="fas fa-arrow-{{ $isCreditIncrease ? 'up' : 'down' }}"></i>
                                    {{ $creditAccount->account_name ?? 'Unknown Account' }}
                                    <span class="account-type">({{ ucfirst($creditCategory) }})</span>
                                </div>
                                @if($creditAccount && $creditAccount->account_code)
                                    <div class="account-code">{{ $creditAccount->account_code }}</div>
                                @endif
                                <div class="balance-effect {{ $isCreditIncrease ? 'positive' : 'negative' }}">
                                    {{ $isCreditIncrease ? '+' : '-' }} Rs. {{ number_format($transaction->amount, 2) }}
                                </div>
                            </td>
                            <td class="amount-cell">
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
.general-ledger-container {
    padding: 24px;
    border-radius: 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

/* Header Styles - Base */
.page-header {
    background: white;
    border-radius: 24px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
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
    flex: 1;
    min-width: 0;
}

.page-icon {
    width: 64px;
    height: 64px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

.page-title {
    flex: 1;
    min-width: 0;
}

.page-title h1 {
    font-size: 32px;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.subtitle {
    color: #718096;
    margin: 4px 0 0 0;
    font-size: 16px;
}

.header-actions {
    flex-shrink: 0;
    margin-left: auto;
}

.btn-modern {
    padding: 12px 24px;
    border: none;
    border-radius: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
}

.btn-export {
    background: linear-gradient(135deg, #48bb78, #38a169);
    color: white;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.15);
}

/* Responsive - Mobile */
@media (max-width: 768px) {
    .page-header {
        padding: 16px;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .header-left {
        gap: 12px;
        flex: 1;
        min-width: 0;
    }

    .page-icon {
        width: 48px;
        height: 48px;
        font-size: 20px;
    }

    .page-title h1 {
        font-size: 20px;
    }

    .subtitle {
        font-size: 12px;
    }

    .header-actions {
        flex-shrink: 0;
    }

    .btn-modern {
        padding: 10px 16px;
        font-size: 14px;
    }

    .btn-text {
        display: none;
    }
}

@media (max-width: 480px) {
    .page-header {
        padding: 12px;
    }

    .header-content {
        gap: 8px;
    }

    .page-title h1 {
        font-size: 16px;
    }

    .btn-modern {
        padding: 8px 12px;
        min-width: 40px;
    }
}

/* Filters Card */
.filters-card {
    background: white;
    border-radius: 24px;
    margin-bottom: 24px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
}

.filters-header {
    background: linear-gradient(135deg, #f7fafc, #edf2f7);
    padding: 16px 24px;
    border-bottom: 1px solid #e2e8f0;
}

.filters-header h3 {
    margin: 0;
    color: #2d3748;
    font-size: 18px;
    font-weight: 600;
}

.filters-body {
    padding: 24px;
}

.filter-row {
    display: grid;
    grid-template-columns: 1fr 1fr 2fr auto;
    gap: 20px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.filter-label {
    font-weight: 600;
    color: #4a5568;
    font-size: 14px;
}

.input-wrapper {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    z-index: 1;
}

.modern-input {
    width: 100%;
    padding: 12px 12px 12px 40px;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    font-size: 14px;
    transition: all 0.3s ease;
    background: white;
}

.modern-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-clear {
    padding: 12px 20px;
    background: linear-gradient(135deg, #f56565, #e53e3e);
    color: white;
    border: none;
    border-radius: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-clear:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(245, 101, 101, 0.3);
}

/* Statistics Cards - 3 Card Layout */
.stats-grid-3 {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: white;
    border-radius: 24px;
    padding: 24px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-4px);
}

.stat-icon {
    width: 56px;
    height: 56px;
    border-radius: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    color: white;
}

.stat-today .stat-icon { background: linear-gradient(135deg, #9f7aea, #805ad5); }
.stat-filtered .stat-icon { background: linear-gradient(135deg, #48bb78, #38a169); }
.stat-monthly-avg .stat-icon { background: linear-gradient(135deg, #4299e1, #3182ce); }

.stat-number {
    font-size: 24px;
    font-weight: 700;
    color: #2d3748;
    line-height: 1;
}

.stat-label {
    color: #718096;
    font-size: 14px;
    font-weight: 500;
}

.stat-sub {
    color: #a0aec0;
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
</style>
</div>
