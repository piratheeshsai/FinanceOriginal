<div class="container-fluid py-3">
    <div class="row g-2">
        <div class="col-12">
            <div class="card border-0 shadow-lg rounded-4">
                <!-- Header -->
                <div class="card-header bg-navy text-white py-3 rounded-top-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-white">
                        <i class="bi bi-cash-stack me-2"></i>Loan Collections
                    </h5>
                    <!-- Export Button -->
                    <div class="dropdown">
                        <button class="btn btn-teal rounded-pill px-4 py-2 fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-download me-1"></i>Export
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item" wire:click="exportExcel">
                                    <i class="bi bi-file-earmark-excel text-success me-1"></i>Excel
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" wire:click="exportPDF">
                                    <i class="bi bi-file-pdf text-danger me-1"></i>PDF
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card-body bg-light-teal border-bottom py-3 rounded-bottom-4">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-3">
                            <select wire:model="loan_type" class="form-select form-select-sm border-teal">
                                <option value="">All Loan Types</option>
                                <option value="individual">Individual Loans</option>
                                <option value="group">Group Loans</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white text-teal"><i class="bi bi-search"></i></span>
                                <input type="text" class="form-control border-start-0 border-teal" placeholder="Search by loan number, customer, or staff" wire:model.debounce.500ms="search">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control form-control-sm border-teal" wire:model="from_date" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control form-control-sm border-teal" wire:model="to_date" placeholder="To Date">
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle mb-0">
                            <thead class="table-navy text-white">
                                <tr>
                                    <th style="width: 110px;">Date</th>
                                    <th style="width: 140px; max-width: 140px;">Name</th>
                                    <th>Loan</th>
                                    <th>Staff</th>
                                    <th>Status</th>
                                    <th class="text-end" style="width: 120px;">Amount</th>
                                    <th class="text-center" style="width: 120px;">Actions</th>
                                    <th style="width: 110px;">Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($collections as $collection)
                                    <tr>
                                        <td>{{ $collection->collection_date->format('m/d/Y') }}</td>
                                        <td style="max-width: 140px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $collection->loan->customer->full_name }}
                                        </td>
                                        <td class="text-teal fw-bold">{{ $collection->loan->loan_number }}</td>
                                        <td>{{ $collection->staff->name }}</td>
                                        <td>
                                            @php
                                                $status = optional($collection->staffCollectionStatus)->status;
                                                $statusColors = [
                                                    'Pending' => 'bg-warning text-dark',
                                                    'Waiting to Accept' => 'bg-navy text-white',
                                                    'Transferred' => 'bg-teal text-white',
                                                ];
                                            @endphp
                                            <span class="badge {{ $statusColors[$status] ?? 'bg-secondary text-white' }}">{{ $status ?? 'No Status' }}</span>
                                        </td>
                                        <td class="text-end text-navy fw-bold">{{ number_format($collection->collected_amount, 2) }}</td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-action-teal rounded-pill me-1" wire:click="generateInvoice({{ $collection->id }}, 'pos')" title="POS Invoice">
                                                    <i class="bi bi-receipt"></i>
                                                </button>
                                                <button class="btn btn-action-navy rounded-pill" wire:click="generateInvoice({{ $collection->id }}, 'a4')" title="PDF Invoice">
                                                    <i class="bi bi-file-pdf"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-teal text-white">{{ $collection->collection_method }}</span></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-3">
                                            <div class="alert alert-info mb-0">
                                                <i class="bi bi-info-circle me-1"></i>No collections found.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="card-footer bg-light-navy py-2 d-flex justify-content-between align-items-center rounded-bottom-4">

                    <div>
                        {{ $collections->links('livewire::bootstrap') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .bg-navy { background-color: #23395d !important; }
        .text-navy { color: #23395d !important; }
        .bg-teal { background-color: #008080 !important; }
        .text-teal { color: #008080 !important; }
        .btn-teal {
            background-color: #008080 !important;
            color: #fff !important;
            border: none;
            border-radius: 2rem !important;
            font-weight: 600;
        }
        .btn-action-teal {
            background-color: #38bdf8 !important;
            color: #fff !important;
            border: none;
            border-radius: 2rem !important;
            padding: 0.35rem 0.7rem;
            font-size: 1rem;
        }
        .btn-action-navy {
            background-color: #23395d !important;
            color: #fff !important;
            border: none;
            border-radius: 2rem !important;
            padding: 0.35rem 0.7rem;
            font-size: 1rem;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 2px 16px rgba(44, 62, 80, 0.08);
            border: none;
        }
        .card-header, .card-footer {
            border-radius: 1rem 1rem 0 0;
            border: none;
        }
        .table-responsive {
            padding: 1rem;
            background: #fff;
            border-radius: 0.3rem !important; /* less rounded */
        }
        .table th, .table td {
            font-size: 0.85rem !important;
            vertical-align: middle;
            border-top: none;
        }
        .table-navy th {
            background-color: #23395d !important;
            color: #fff !important;
            font-size: 1.08rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            padding-top: 1em;
            padding-bottom: 1em;
            border-bottom: 2px solid #008080;
            text-align: center;
        }
        .badge {
            font-size: 0.8rem;
            padding: 0.35em 0.6em;
        }
        .btn-group-sm .btn {
            line-height: 1.2;
            padding: 0.25rem 0.5rem;
        }
        .fs-7 {
            font-size: 0.85rem;
        }
        .form-select-sm, .form-control-sm {
            font-size: 0.9rem;
        }
        .input-group-sm .input-group-text {
            font-size: 0.9rem;
        }
        .rounded-4 { border-radius: 0.3rem !important; } /* less rounded */
        .card { border-radius: 0.3rem !important; }
        .card-header, .card-footer { border-radius: 0.3rem 0.3rem 0 0 !important; }
    </style>
</div>
