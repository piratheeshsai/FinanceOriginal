<div >

    <!-- Transfer Form Card -->
    <div class="card border-0 shadow-lg overflow-hidden">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h4 class="card-title mb-0 fw-semibold text-white">
                <i class="fas fa-exchange-alt me-2"></i>Create New Transfer
            </h4>
        </div>
        <div class="card-body p-4">
            <form wire:submit.prevent="submit" class="needs-validation" novalidate>
                <div class="row g-4">
                    <!-- From Account -->
                    <div class="col-md-6">
                        <label class="text-muted">
                            <i class="fas fa-wallet me-2"></i>From Account
                        </label>

                            <select wire:model="from_account_id"
                                    class="form-select"
                                    required>
                                <option value="">Select From Account</option>
                                @foreach($fromAccounts as $account)
                                <option value="{{ $account->id }}">
                                    {{ $account->account_name }} ({{ number_format($account->balance, 2) }})
                                </option>
                                @endforeach
                            </select>

                        @error('from_account_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <!-- To Account -->
                    <div class="col-md-6">
                        <label class="text-muted">
                            <i class="fas fa-bank me-2"></i>To Account
                        </label>
                        <select wire:model="to_account_id"
                                class="form-select"
                                required>
                            <option value="">Select To Account</option>
                            @foreach($toAccounts as $account)
                            <option value="{{ $account->id }}">
                                {{ $account->account_name }} ({{ number_format($account->balance, 2) }})
                            </option>
                            @endforeach
                        </select>
                        @error('to_account_id') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>


                    <!-- Transaction Details -->
                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="number" step="0.01"
                                   wire:model="amount"
                                   class="form-control"
                                   placeholder="Amount"
                                   required>
                            <label class="text-muted">
                                <i class="fas fa-coins me-2"></i>Amount
                            </label>
                        </div>
                        @error('amount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="date"
                                   wire:model="transfer_date"
                                   class="form-control"
                                   required>
                            <label class="text-muted">
                                <i class="fas fa-calendar-day me-2"></i>Transfer Date
                            </label>
                        </div>
                        @error('transfer_date') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <div class="form-floating">
                            <input type="text"
                                   wire:model="description"
                                   class="form-control"
                                   placeholder="Description">
                            <label class="text-muted">
                                <i class="fas fa-comment me-2"></i>Description
                            </label>
                        </div>
                        @error('description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit"
                                class="btn btn-primary w-100 py-3 fw-semibold"
                                wire:loading.attr="disabled">
                            <span wire:loading.remove>
                                <i class="fas fa-paper-plane me-2"></i>Submit Transfer
                            </span>
                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                Processing...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-lg mt-4">
        <div class="card-header bg-gradient-info text-white py-3">
            <h4 class="card-title mb-0 fw-semibold">
                <i class="fas fa-history me-2"></i>Transfer History
            </h4>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 25%;">From → To</th>
                            <th class="text-end" style="width: 15%;">Amount</th>
                            <th style="width: 15%;">Date</th>
                            <th style="width: 20%;">Created By</th>
                            <th style="width: 15%;">Status</th>
                            @if(auth()->user()->hasPermissionTo('Approve All Transfer'))
                            <th class="pe-4 text-center" style="width: 10%;">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transfers as $transfer)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex flex-column">
                                    <span class="text-dark fw-medium text-truncate">
                                        {{ $transfer->fromAccount->account_name }}
                                    </span>
                                    <span class="text-muted small">
                                        <i class="fas fa-arrow-right me-1"></i>
                                        {{ $transfer->toAccount->account_name }}
                                    </span>
                                    @if($transfer->description)
                                    <small class="text-muted text-truncate">{{ $transfer->description }}</small>
                                    @endif
                                </div>
                            </td>
                            <td class="text-end fw-semibold text-nowrap">
                                {{ number_format($transfer->amount, 2) }}
                            </td>
                            <td class="text-nowrap">
                                {{ $transfer->created_at->format('d M Y') }}
                            </td>
                            <td class="text-truncate">
                                {{ $transfer->user->name }}
                            </td>
                            <td>
                                @if($transfer->status == 'approved')
                                <span class="badge bg-success rounded-pill">Approved</span>
                                @elseif($transfer->status == 'pending')
                                <span class="badge bg-warning rounded-pill">Pending</span>
                                @elseif($transfer->status == 'rejected')
                                <span class="badge bg-danger rounded-pill">Rejected</span>
                                @endif
                            </td>
                            @if(auth()->user()->hasPermissionTo('Approve All Transfer') ||
                            ($transfer->fromAccount->type == 'collection_cash' &&
                             auth()->user()->hasPermissionTo('Loan C.T approval') &&
                             $transfer->branch_id == auth()->user()->branch_id))
                        <td class="pe-4 text-center">
                            @if($transfer->status == 'pending')
                            <div class="btn-group">
                                <button wire:click="approveTransfer({{ $transfer->id }})"
                                        class="btn btn-sm btn-dark px-3"
                                        wire:loading.attr="disabled">
                                    <i class="fas fa-check me-2"></i>Approve
                                </button>
                                <button wire:click="rejectTransfer({{ $transfer->id }})"
                                        class="btn btn-sm btn-danger px-3"
                                        wire:loading.attr="disabled">
                                    <i class="fas fa-times me-2"></i>Reject
                                </button>
                            </div>
                            @else
                            <span class="text-muted small">Completed</span>
                            @endif

                        </td>
                        @endif

                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->hasPermissionTo('Approve All Transfer') ? 6 : 5 }}"
                                class="text-center py-4 text-muted">
                                <i class="fas fa-exchange-alt fa-2x mb-3 text-muted"></i><br>
                                No transfers found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card-footer px-4">

                {{ $transfers->links('livewire::bootstrap') }}

            </div>
        </div>
    </div>
</div>
