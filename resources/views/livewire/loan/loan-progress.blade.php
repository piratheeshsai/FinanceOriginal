<div class="card mb-4 shadow rounded border-0">
    <div class="card-header bg-primary text-white border-0">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-0 fw-bold text-white">Loan Progress</h4>
            </div>
            <div class="col-12 col-md-4 d-flex justify-content-end align-items-center">
                <div class="input-group me-2">
                    <input wire:model.live.debounce.300ms="search"
                           type="text"
                           class="form-control"
                           placeholder="Search by name or NIC..."
                           style="height: 32px; padding: 5px;">
                            <button wire:click="$set('search', '')"
                            class="btn btn-light border"
                            type="button"
                            style="height: 32px; padding: 5px;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <button wire:click="exportCsv"
                        class="btn btn-light border btn-sm"
                        style="padding: 2px 10px; font-size: 0.85rem; line-height: 1.2;">
                    <i class="fas fa-file-csv"></i> Export CSV
                </button>
            </div>
        </div>
    </div>

    <div class="card-body bg-white">
        <div class="mb-3">
            <label for="branchFilter" class="form-label">Branch</label>
            <select wire:model.live="branchFilter" id="branchFilter" class="form-select">
                <option value="all">All Branches</option>
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle mb-0 table-sm-custom">
                <thead class="bg-light">
                    <tr>
                        <th class="text-center" style="width: 40px;">#</th>
                        <th class="text-center" style="width: 100px;">Loan No</th>
                        <th style="width: 180px;">Customer</th>
                        <th class="text-end">Principal</th>
                        <th class="text-end">Total</th>
                        <th class="text-end">Principal Collected</th>
                        <th class="text-end">Interest Collected</th>
                        <th class="text-end">Paid</th>
                        <th class="text-end">Balance</th>
                        <th class="text-center">Status</th>
                        <th>Branch</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($loanProgress as $index => $item)
                        <tr>
                            <td class="text-center bg-white">{{ $start + $loop->iteration }}</td>
                            <td class="text-center bg-white">
                                <span class="badge bg-primary text-white">{{ $item->loan->loan_number }}</span>
                            </td>
                            <td style="max-width: 180px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" class="bg-white">
                                <strong title="{{ $item->loan->customer->full_name }}">{{ $item->loan->customer->full_name }}</strong>
                                <div class="text-muted small">{{ $item->loan->customer->nic }}</div>
                            </td>
                            <td class="text-end text-success bg-white">{{ number_format($item->loan->loan_amount, 2) }}</td>
                            <td class="text-end text-primary bg-white">{{ number_format($item->total_amount, 2) }}</td>
                            <td class="text-end bg-white">
                                <span class="badge bg-success text-white">{{ number_format($item->loan->loanCollections->sum('principal_amount'), 2) }}</span>
                            </td>
                            <td class="text-end bg-white">
                                <span class="badge bg-warning text-dark">{{ number_format($item->loan->loanCollections->sum('interest_amount'), 2) }}</span>
                            </td>
                            <td class="text-end text-success bg-white">{{ number_format($item->total_paid_amount, 2) }}</td>
                            <td class="text-end text-danger bg-white">{{ number_format($item->balance, 2) }}</td>
                            <td class="text-center bg-white">
                                <span class="badge bg-info text-dark">{{ $item->status }}</span>
                            </td>
                            <td class="bg-white">
                                <span class="fw-bold">{{ $item->loan->center->branch->name }}</span>
                                <br>
                                <span class="text-secondary small">
                                    {{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $loanProgress->links('livewire::bootstrap') }}
        </div>
    </div>
    <style>
    .table-sm-custom td, .table-sm-custom th {
        font-size: 0.95rem !important;/* or 14px */
        padding-top: 0.4rem !important;
        padding-bottom: 0.4rem !important;
    }
</style>
</div>
