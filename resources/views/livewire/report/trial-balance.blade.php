<!-- resources/views/livewire/trial-balance.blade.php -->
{{-- <div class="container-fluid px-4"> --}}
    <div class="card shadow-sm border-0 rounded-3 mb-4">
        <div class="card-header bg-gradient-primary text-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-white">
                    <i class="bi bi-calculator me-2 text-white"></i>Trial Balance
                </h4>
                <span class="badge bg-light text-dark rounded-pill">
                    {{ $selectedBranch ? $branches->firstWhere('id', $selectedBranch)->name : 'All Branches' }}
                </span>
            </div>
        </div>

        <div class="card-body">
            <!-- Filters Panel -->
            <div class="bg-light p-3 rounded-3 mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="form-floating">
                            <select wire:model="selectedBranch" class="form-select" id="branchSelect">
                                <option value="">All Branches</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                            <label for="branchSelect">Branch</label>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="date" wire:model="startDate" class="form-control" id="startDate">
                            <label for="startDate">Start Date</label>
                        </div>
                        @error('startDate') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3">
                        <div class="form-floating">
                            <input type="date" wire:model="endDate" class="form-control" id="endDate">
                            <label for="endDate">End Date</label>
                        </div>
                        @error('endDate') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-3 d-flex align-items-center">
                        <button wire:click="generateTrialBalance" class="btn btn-primary w-100 py-3">
                            <div class="d-flex align-items-center justify-content-center">
                                <span wire:loading.remove wire:target="generateTrialBalance">
                                    <i class="bi bi-funnel-fill me-2"></i>Generate Report
                                </span>
                                <span wire:loading wire:target="generateTrialBalance">
                                    <span class="spinner-border spinner-border-sm me-2"></span>
                                    Processing...
                                </span>
                            </div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Results Section -->
            @if(count($trialBalanceData) > 0)
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">
                        <span class="text-muted">Period:</span>
                        <span class="badge bg-secondary">{{ date('M d, Y', strtotime($startDate)) }}</span> to
                        <span class="badge bg-secondary">{{ date('M d, Y', strtotime($endDate)) }}</span>
                    </h5>

                    <div class="btn-group" role="group">
                        {{-- <button class="btn btn-sm btn-outline-success" onclick="window.print()">
                            <i class="bi bi-printer me-1"></i> Print
                        </button> --}}
                        <button class="btn btn-sm btn-outline-primary" wire:click="exportPdf">
                            <i class="bi bi-file-pdf me-1"></i> Export PDF
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover table-striped border">
                        <thead>
                            <tr class="bg-dark text-white">
                                <th class="py-3">Account Number</th>
                                <th class="py-3">Account Name</th>
                                <th class="py-3">Type</th>
                                <th class="py-3">Category</th>
                                <th class="py-3 text-end">Debit (LKR)</th>
                                <th class="py-3 text-end">Credit (LKR)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trialBalanceData as $item)
                                <tr>
                                    <td class="font-monospace">{{ $item['account_number'] }}</td>
                                    <td>{{ $item['account_name'] }}</td>
                                    <td>
                                        <span class="badge bg-{{ getTypeColor($item['type']) }} rounded-pill text-capitalize">
                                            {{ str_replace('_', ' ', $item['type']) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ getCategoryColor($item['category']) }} rounded-pill text-capitalize">
                                            {{ $item['category'] }}
                                        </span>
                                    </td>
                                    <td class="text-end font-monospace {{ $item['debit'] > 0 ? 'fw-bold' : 'text-muted' }}">
                                        {{ $item['debit'] > 0 ? number_format($item['debit'], 2) : '-' }}
                                    </td>
                                    <td class="text-end font-monospace {{ $item['credit'] > 0 ? 'fw-bold' : 'text-muted' }}">
                                        {{ $item['credit'] > 0 ? number_format($item['credit'], 2) : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold bg-light border-top border-2">
                                <td colspan="4" class="text-end">Totals</td>
                                <td class="text-end font-monospace">{{ number_format($totalDebit, 2) }}</td>
                                <td class="text-end font-monospace">{{ number_format($totalCredit, 2) }}</td>
                            </tr>
                            @if($totalDebit != $totalCredit)
                                <tr class="bg-danger text-white">
                                    <td colspan="4" class="text-end">Difference</td>
                                    <td colspan="2" class="text-center font-monospace fw-bold">
                                        {{ number_format(abs($totalDebit - $totalCredit), 2) }}
                                    </td>
                                </tr>
                            @else
                                <tr class="bg-success text-white">
                                    <td colspan="6" class="text-center py-2">
                                        <i class="bi bi-check-circle me-2"></i>Trial Balance is in balance
                                    </td>
                                </tr>
                            @endif
                        </tfoot>
                    </table>
                </div>

                <!-- Summary Cards -->
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-3">Total Debits</h6>
                                <h3 class="card-text mb-0 fw-bold">LKR {{ number_format($totalDebit, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light border-0 h-100">
                            <div class="card-body p-3">
                                <h6 class="card-title text-muted mb-3">Total Credits</h6>
                                <h3 class="card-text mb-0 fw-bold">LKR {{ number_format($totalCredit, 2) }}</h3>
                            </div>
                        </div>
                    </div>
                </div>

            @elseif($trialBalanceData !== null && count($trialBalanceData) === 0)
                <div class="alert alert-info d-flex align-items-center p-4">
                    <i class="bi bi-info-circle-fill fs-3 me-3"></i>
                    <div>
                        <h5 class="alert-heading text-white">No Data Found</h5>
                        <p class="mb-0 text-white">No transactions found for the selected criteria. Try adjusting your filters.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function () {
        // Add any JavaScript enhancements here
    });
</script>
@endpush

@php
// Helper functions for badges
function getTypeColor($type) {
    $colors = [
        'cash' => 'success',
        'bank' => 'primary',
        'cash_drawer' => 'success',
        'loan_receivable' => 'info',
        'collection_cash' => 'success',
        'petty_cash' => 'success',
        'capital' => 'dark',
        'branch_capital' => 'dark',
        'owner_draw' => 'warning',
        'retained_earnings' => 'secondary',
        'interest_income' => 'primary',
        'late_fee_income' => 'primary',
        'salary_expense' => 'danger',
        'rent_expense' => 'danger',
        'utilities_expense' => 'danger',
        'office_supplies_expense' => 'danger',
        'petty_cash_expenses' => 'danger',
        'other_expenses' => 'danger'
    ];

    return $colors[$type] ?? 'secondary';
}

function getCategoryColor($category) {
    $colors = [
        'asset' => 'primary',
        'liability' => 'warning',
        'equity' => 'dark',
        'revenue' => 'success',
        'expense' => 'danger'
    ];

    return $colors[$category] ?? 'secondary';
}
@endphp
