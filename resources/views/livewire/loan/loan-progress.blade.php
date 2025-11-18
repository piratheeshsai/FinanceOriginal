<div class="container-fluid py-3">
    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-navy text-white py-3 rounded-top-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-0 fw-bold text-white">
                        <i class="bi bi-bar-chart-steps me-2"></i>Loan Progress
                    </h4>
                </div>
                <div class="col-12 col-md-4 d-flex justify-content-end align-items-center">
                    <div class="input-group me-2">
                        <input wire:model.live.debounce.300ms="search"
                               type="text"
                               class="form-control form-control-sm"
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
                            class="btn btn-teal btn-sm rounded-pill ms-2 d-flex align-items-center"
                            style="font-size: 0.85rem; height: 32px; padding: 0 1rem;">
                        <i class="fas fa-file-csv me-1"></i> Export
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body bg-white rounded-bottom-4">
            <div class="mb-3">
                <label for="branchFilter" class="form-label text-navy fw-semibold">Branch</label>
                <select wire:model.live="branchFilter" id="branchFilter" class="form-select form-select-sm border-teal" style="max-width: 240px;">
                    <option value="all">All Branches</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="table-responsive" style="border-radius: 0.3rem; background: #fff; padding: 1rem;">
                <table class="table table-bordered table-sm align-middle mb-0">
                    <thead style="background-color: #f4f6fa;">
                        <tr>
                            <th class="text-center" style="width: 40px;">#</th>
                            <th class="text-center" style="width: 100px;">Loan No</th>
                            <th style="width: 180px;">Customer</th>
                            <th class="text-end" style="width: 90px;">Principal</th>
                            <th class="text-end" style="width: 90px;">Total</th>
                            <th class="text-end" style="width: 110px;">Pri Collected</th>
                            <th class="text-end" style="width: 110px;">Int Collected</th>
                            <th class="text-end" style="width: 90px;">Paid</th>
                            <th class="text-end" style="width: 90px;">Balance</th>
                            <th class="text-center" style="width: 80px;">Status</th>
                            <th style="width: 120px;">Branch</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loanProgress as $index => $item)
                            <tr>
                                <td class="text-center">{{ $start + $loop->iteration }}</td>
                                <td class="text-center">{{ $item->loan->loan_number }}</td>
                                <td>
                                    <strong title="{{ $item->loan->customer->full_name }}">{{ $item->loan->customer->full_name }}</strong>
                                    <div class="text-muted small">{{ $item->loan->customer->nic }}</div>
                                </td>
                                <td class="text-end">{{ number_format($item->loan->loan_amount, 2) }}</td>
                                <td class="text-end">{{ number_format($item->total_amount, 2) }}</td>
                                <td class="text-end">{{ number_format($item->loan->loanCollections->sum('principal_amount'), 2) }}</td>
                                <td class="text-end">{{ number_format($item->loan->loanCollections->sum('interest_amount'), 2) }}</td>
                                <td class="text-end">{{ number_format($item->total_paid_amount, 2) }}</td>
                                <td class="text-end">{{ number_format($item->balance, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge bg-info text-dark">{{ $item->status }}</span>
                                </td>
                                <td>
                                    <span>{{ $item->loan->center->branch->name }}</span>
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
    </div>

    <style>
        .bg-navy { background-color: #23395d !important; }
        .text-navy { color: #23395d !important; }
        .bg-teal { background-color: #008080 !important; }
        .text-teal { color: #008080 !important; }
        .bg-light-teal { background-color: #e0f7fa !important; }
        .bg-light-navy { background-color: #e3eafc !important; }
        .table-navy th {
            background-color: #23395d !important;
            color: #fff !important;
            font-size: 1.05rem;
            font-weight: 700;
            letter-spacing: 0.03em;
            padding-top: 0.7em;
            padding-bottom: 0.7em;
            border-bottom: 2px solid #008080;
            text-align: center;
            vertical-align: middle;
        }
        .table-sm-custom td, .table-sm-custom th {
            font-size: 0.85rem !important;
            padding-top: 0.4rem !important;
            padding-bottom: 0.4rem !important;
        }
        .badge {
            font-size: 0.8rem;
            padding: 0.35em 0.6em;
        }
        .btn-teal {
            background-color: #008080 !important;
            color: #fff !important;
            border: none;
            border-radius: 2rem !important;
            font-weight: 600;
            font-size: 0.85rem !important;
            height: 32px !important;
            padding: 0 1rem !important;
            display: flex;
            align-items: center;
        }
        .form-select-sm, .form-control-sm {
            font-size: 0.9rem;
        }
        .table-responsive {
            border-radius: 1rem !important;
        }
        .card, .card-header, .card-footer {
            border-radius: 1rem !important;
        }
        .table th, .table td {
            font-size: 0.85rem !important;
            vertical-align: middle;
            padding-top: 0.4rem !important;
            padding-bottom: 0.4rem !important;
        }
        .table thead th {
            background-color: #f4f6fa !important;
            color: #23395d !important;
            font-weight: 700;
            border-bottom: 2px solid #dee2e6 !important;
            text-align: center;
        }
    </style>
</div>
