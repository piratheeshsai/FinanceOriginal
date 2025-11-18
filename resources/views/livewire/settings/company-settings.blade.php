<div class="container py-5">
    <div class="row g-4">
        <!-- Company Details Section -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-navy text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-white">Company Information</h4>
                    @if($company)
                        <span class="badge bg-light text-navy">Established: {{ $company->created_at->format('M Y') }}</span>
                    @endif
                </div>
                <div class="card-body bg-light">
                    <form wire:submit.prevent="updateCompany">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Company Name</label>
                                <input type="text" class="form-control" wire:model="name">
                                <label class="form-label mt-3">Email</label>
                                <input type="email" class="form-control" wire:model="email">
                                <label class="form-label mt-3">Phone</label>
                                <input type="text" class="form-control" wire:model="phone">
                                <label class="form-label mt-3">Website</label>
                                <input type="url" class="form-control" wire:model="website">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Registration No</label>
                                <input type="text" class="form-control" wire:model="registration_no">
                                <label class="form-label mt-3">Capital Balance</label>
                                <div class="input-group">
                                    <span class="input-group-text">LKR</span>
                                    <input type="number" step="0.01" class="form-control" wire:model="capital_balance" placeholder="0.00">
                                </div>
                                <label class="form-label mt-3">Address</label>
                                <textarea class="form-control" wire:model="address" rows="2"></textarea>
                                <label class="form-label mt-3">Logo</label>
                                <input type="file" class="form-control" wire:model="logo">
                                <div class="mt-2">
                                    @if ($logo)
                                        <img src="{{ $logo->temporaryUrl() }}" alt="New Logo Preview" class="img-thumbnail" style="max-height: 80px">
                                    @elseif ($company && $company->logo)
                                        <img src="{{ Storage::url('logos/' . $company->logo) }}" alt="Company Logo" class="img-thumbnail" style="max-height: 80px">
                                    @else
                                        <div class="text-center text-muted py-2 bg-light rounded">
                                            <i class="bi bi-image fs-4"></i><br>
                                            <small>No logo uploaded</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-navy">
                                <i class="bi bi-save"></i> {{ $company ? 'Update Company Details' : 'Save Company Details' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Financial Overview Section -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-teal text-white">
                    <h4 class="mb-0 text-white">Financial Overview</h4>
                </div>
                <div class="card-body bg-light">


                    <div class="mb-3">
                        <label class="form-label">Bank Balance</label>
                        <input type="text" class="form-control" value="{{ number_format($bankBalance ?? 0, 2) }}" readonly>
                    </div>



                    <div class="d-flex gap-2 mt-4">
                        <button class="btn btn-outline-navy flex-fill" wire:click="openWithdrawalModal">
                            <i class="bi bi-cash"></i> Withdraw Funds
                        </button>
                        <button class="btn btn-outline-teal flex-fill" wire:click="openAddFundsModal">
                            <i class="bi bi-wallet2"></i> Add Funds
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal Modal -->
    <div class="modal fade" id="withdrawalModal" tabindex="-1" aria-labelledby="withdrawalModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-navy text-white">
                    <h5 class="modal-title text-white" id="withdrawalModalLabel">Withdraw Capital Funds</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="processWithdrawal">
                        <div class="mb-3">
                            <label class="form-label">Select Branch</label>
                            <select class="form-select" wire:model="branch_id">
                                <option value="">Choose Branch</option>
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Withdrawal Amount</label>
                            <input type="number" step="0.01" class="form-control" wire:model="withdrawal_amount" placeholder="0.00">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" wire:model="withdrawal_description" rows="2" placeholder="Purpose of withdrawal"></textarea>
                        </div>
                        <div class="alert alert-info">
                            <small class="text-white">
                                <i class="bi bi-info-circle text-white"></i> This withdrawal will be recorded as an Owner's Draw transaction. Available balance: LKR{{ number_format($capital_balance, 2) }}
                            </small>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-navy">
                                <i class="bi bi-check2"></i> Confirm Withdrawal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Funds Modal -->
    <div class="modal fade" id="openAddFundsModal" tabindex="-1" aria-labelledby="openAddFundsModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-teal text-white">
                    <h5 class="modal-title text-white" id="openAddFundsModalLabel">Add Funds</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="AddFundProcess">
                        <div class="mb-3">
                            <label class="form-label">Add Amount</label>
                            <input type="number" step="0.01" class="form-control" wire:model="amount" placeholder="0.00">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-teal">
                                <i class="bi bi-check2"></i> Confirm Add Funds
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('livewire:initialized', function () {
        const withdrawalModal = new bootstrap.Modal(document.getElementById('withdrawalModal'));
        Livewire.on('showWithdrawalModal', () => withdrawalModal.show());
        Livewire.on('hideWithdrawalModal', () => withdrawalModal.hide());

        const openAddFundsModal = new bootstrap.Modal(document.getElementById('openAddFundsModal'));
        Livewire.on('showOpenAddFundsModal', () => openAddFundsModal.show());
        Livewire.on('hideOpenAddFundsModal', () => openAddFundsModal.hide());
    });
    </script>


<style>
.bg-navy { background-color: #23395d !important; }
.text-navy { color: #23395d !important; }
.btn-navy, .btn-outline-navy { background-color: #23395d; color: #fff; border-color: #23395d; }
.btn-outline-navy:hover { background-color: #1a2940; color: #fff; }
.bg-teal { background-color: #008080 !important; }
.text-teal { color: #008080 !important; }
.btn-teal, .btn-outline-teal { background-color: #008080; color: #fff; border-color: #008080; }
.btn-outline-teal:hover { background-color: #006666; color: #fff; }
.bg-light { background-color: #f8f9fa !important; }
.card { border-radius: 0.75rem; }
.card-header { border-radius: 0.75rem 0.75rem 0 0; }
</style>
</div>
