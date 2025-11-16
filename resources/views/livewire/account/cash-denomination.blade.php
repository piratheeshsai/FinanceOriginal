<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 text-white">
            <i class="fas fa-money-bill me-2"></i>
            End of Day Cash Count
        </h5>
        <div class="badge bg-light text-dark">
            {{ $account ? $account->account_name : 'Cash in Hand' }}
        </div>
    </div>

    <div class="card-body">
        @if (session()->has('message'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="date" class="form-label">Count Date</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                        <input type="date" wire:model="date" id="date" class="form-control" wire:change.="loadExistingDenomination">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">System Balance</label>
                    <div class="input-group">
                        <span class="input-group-text">LKR</span>
                        <input type="text" class="form-control bg-light" value="{{ number_format($systemBalance, 2) }}" readonly>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Notes Column -->
            <div class="col-lg-6">
                <div class="card mb-3 border-success h-100">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-money-bill-wave me-1"></i> Notes</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Denomination</th>
                                        <th style="width: 120px;">Count</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notes as $value => $count)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bill-icon me-2  bg-opacity-10 p-1 rounded text-success">
                                                    <i class="fas fa-money-bill-wave"></i>
                                                </div>
                                                <span>LKR {{ number_format($value) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number"
                                                   wire:model="notes.{{ $value }}"
                                                   min="0"
                                                   class="form-control form-control-sm"
                                                   wire:change="calculateTotal">
                                        </td>
                                        <td class="text-end font-monospace">
                                            {{ number_format((int)$value * (float)$count) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="2">Total Notes</th>
                                        <th class="text-end font-monospace">
                                            {{ number_format(collect($notes)->reduce(function ($carry, $count, $value) {
                                                return $carry + ((int)$value * (float)$count);
                                            }, 0)) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Coins Column -->
            <div class="col-lg-6">
                <div class="card mb-3 border-warning h-100">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="fas fa-coins me-1"></i> Coins</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Denomination</th>
                                        <th style="width: 120px;">Count</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($coins as $value => $count)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="coin-icon me-2 bg-warning bg-opacity-10 p-1 rounded text-warning">
                                                    <i class="fas fa-coins"></i>
                                                </div>
                                                <span>{{ $value < 1 ? $value * 100 . '¢' : 'LKR ' . number_format($value) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="number"
                                                   wire:model.="coins.{{ $value }}"
                                                   min="0"
                                                   class="form-control form-control-sm"
                                                   wire:change="calculateTotal">
                                        </td>
                                        <td class="text-end font-monospace">
                                            {{ number_format((int)$value * (float)$count) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="2">Total Coins</th>
                                        <th class="text-end font-monospace">


{{ number_format(collect($coins)->reduce(function ($carry, $count, $value) {
    return $carry + ((int)$value * (float)$count);
}, 0),2) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">System Balance</span>
                                    <span class="h3 font-monospace mb-0">LKR {{ number_format($systemBalance, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">Physical Count</span>
                                    <span class="h3 font-monospace mb-0">LKR {{ number_format($totalAmount, 2) }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex flex-column">
                                    <span class="text-muted">Difference</span>
                                    <span class="h3 font-monospace mb-0 {{ $difference == 0 ? 'text-success' : ($difference > 0 ? 'text-info' : 'text-danger') }}">
                                        @if ($difference == 0)
                                            <i class="fas fa-check-circle me-1"></i> BALANCED
                                        @elseif ($difference > 0)
                                            <i class="fas fa-plus-circle me-1"></i> LKR {{ number_format($difference, 2) }}
                                        @else
                                            <i class="fas fa-minus-circle me-1"></i> LKR {{ number_format(abs($difference), 2) }}
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remarks section -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="remarks" class="form-label">
                        Remarks
                        @if ($difference != 0)
                            <span class="text-danger">*</span>
                            <small class="text-danger">(Required for discrepancies)</small>
                        @endif
                    </label>
                    <textarea
                        wire:model="remarks"
                        id="remarks"
                        class="form-control"
                        rows="3"
                        @if ($difference != 0) required @endif
                        placeholder="Please explain any cash differences here..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="card-footer d-flex justify-content-between">
        <button type="button" class="btn btn-secondary" wire:click="loadExistingDenomination">
            <i class="fas fa-redo me-1"></i> Reset
        </button>
        <div>
            <button type="button" class="btn btn-success" wire:click="saveCount" {{ $difference != 0 && empty($remarks) ? 'disabled' : '' }}>
                <i class="fas fa-save me-1"></i> Save Cash Count
            </button>
        </div>
    </div>



    <style>
        .font-monospace {
            font-family: 'Courier New', Courier, monospace;
        }
    </style>



</div>


