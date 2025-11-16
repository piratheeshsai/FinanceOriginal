<div class="container py-4">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 text-white">Company Settings</h4>
                    @if($company)
                        <span class="badge bg-light text-primary">Established: {{ $company->created_at->format('M Y') }}</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <!-- Company Details Card -->
                        @can('Company Create')
                        <div class="col-md-7">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">
                                        <i class="bi bi-building"></i>
                                        {{ $company ? 'Update Company Information' : 'Create Company Profile' }}
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <form wire:submit.prevent="updateCompany">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">Company Name</label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" wire:model="name">
                                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email Address</label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" wire:model="email">
                                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="phone" class="form-label">Phone Number</label>
                                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" wire:model="phone">
                                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label for="website" class="form-label">Website</label>
                                                    <input type="url" class="form-control @error('website') is-invalid @enderror" id="website" wire:model="website">
                                                    @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="registration_no" class="form-label">Registration Number</label>
                                                    <input type="text" class="form-control @error('registration_no') is-invalid @enderror" id="registration_no" wire:model="registration_no">
                                                    @error('registration_no') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>
                                                <div class="mb-3">
                                                    <label for="capital_balance" class="form-label">Capital Balance</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">LKR</span>
                                                        <input type="number" step="0.01" class="form-control @error('capital_balance') is-invalid @enderror" id="capital_balance" wire:model="capital_balance" placeholder="0.00">
                                                    </div>
                                                    @error('capital_balance') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label for="address" class="form-label">Address</label>
                                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" wire:model="address" rows="3"></textarea>
                                                    @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                                </div>


                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Company Logo</label>
                                            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" wire:model="logo">
                                            @error('logo') <div class="invalid-feedback">{{ $message }}</div> @enderror

                                            <div class="mt-2">
                                                @if ($logo)
                                                    <img src="{{ $logo->temporaryUrl() }}" alt="New Logo Preview" class="img-thumbnail" style="max-height: 100px">
                                                @elseif ($company && $company->logo)
                                                    <img src="{{ Storage::url('logos/' . $company->logo) }}" alt="Company Logo" class="img-thumbnail" style="max-height: 100px">
                                                @else
                                                    <div class="text-center text-muted py-3 bg-light rounded">
                                                        <i class="bi bi-image fs-3"></i><br>
                                                        <small>No logo uploaded</small>
                                                    </div>
                                                @endif


                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-save"></i>
                                                {{ $company ? 'Update Company Details' : 'Save Company Details' }}
                                            </button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endcan
                        <!-- Financial Overview Card -->
                        <div class="col-md-5">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="bi bi-cash-stack"></i> Financial Overview</h5>
                                </div>
                                <div class="card-body">
                                    <!-- Capital Account -->
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h6 class="text-muted mb-0">Owner's Capital</h6>
                                            <span class="badge bg-success">CAPITAL-001</span>
                                        </div>
                                        <div class="d-flex align-items-baseline">
                                            <h2 class="mb-0 me-2">LKR {{ number_format($capital_balance, 2) }}</h2>
                                            <small class="text-muted">Available</small>
                                        </div>
                                        @can('withdrawal from Company')

                                        <div class="mt-3">
                                            <button class="btn btn-primary" wire:click="openAddFundsModal">
                                                <i class="bi bi-wallet2"></i> Add Funds
                                            </button>
                                        </div>



                                        <div class="mt-3">
                                            <button class="btn btn-primary" wire:click="openWithdrawalModal">
                                                <i class="bi bi-cash"></i> Withdraw Funds
                                            </button>
                                        </div>

                                        

                                        @endcan
                                    </div>

                                    <hr>

                                    <!-- Other Company Accounts -->
                                    <h6 class="text-muted mb-3">Company Accounts</h6>

                                    <!-- Main Bank Account -->
                                    <div class="card bg-light mb-3">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0">Main Bank Account</h6>
                                                    <small class="text-muted">BANK-MAIN</small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="fw-bold">LKR{{ number_format($bankBalance ?? 0, 2) }}</span>
                                                    <div><small class="badge bg-info">Asset</small></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Owner's Draw Account -->
                                    <div class="card bg-light mb-3">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0">Owner's Draw</h6>
                                                    <small class="text-muted">OWNER-DRAW-company</small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="fw-bold">LKR{{ number_format($ownerDrawBalance ?? 0, 2) }}</span>
                                                    <div><small class="badge bg-warning">Equity</small></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cash Drawer Account -->
                                    <div class="card bg-light">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h6 class="mb-0">Cash Drawer</h6>
                                                    <small class="text-muted">CASH-DRAWER-company</small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="fw-bold">LKR{{ number_format($cashDrawerBalance ?? 0, 2) }}</span>
                                                    <div><small class="badge bg-info">Asset</small></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Withdrawal Modal -->
    <div class="modal fade" id="withdrawalModal" tabindex="-1" aria-labelledby="withdrawalModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="withdrawalModalLabel">Withdraw Capital Funds</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="processWithdrawal">
                        <div class="mb-3">
                            <label for="withdrawal_amount text-white" class="form-label">Withdrawal Amount</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" class="form-control @error('withdrawal_amount') is-invalid @enderror" id="withdrawal_amount" wire:model="withdrawal_amount" placeholder="0.00">
                            </div>
                            @error('withdrawal_amount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="withdrawal_description" class="form-label">Description</label>
                            <textarea class="form-control @error('withdrawal_description') is-invalid @enderror" id="withdrawal_description" wire:model="withdrawal_description" rows="3" placeholder="Purpose of withdrawal"></textarea>
                            @error('withdrawal_description') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>

                        <div class="alert alert-info">
                            <small>
                                <i class="bi bi-info-circle"></i> This withdrawal will be recorded as an Owner's Draw transaction. Available balance: LKR{{ number_format($capital_balance, 2) }}
                            </small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="processWithdrawal">
                        <i class="bi bi-check2"></i> Confirm Withdrawal
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="openAddFundsModal" tabindex="-1" aria-labelledby="openAddFundsModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title text-white" id="openAddFundsModalLabel" >Add Funds</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="AddFundProcess">
                        <div class="mb-3">
                            <label for="amount" class="form-label">Add Amount</label>
                            <div class="input-group">
                                <span class="input-group-text"></span>
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" wire:model="amount" placeholder="0.00">
                            </div>
                            @error('amount') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" wire:click="AddFundProcess">
                        <i class="bi bi-check2"></i> Confirm Inverse
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
   document.addEventListener('livewire:initialized', function () {
    const withdrawalModal = new bootstrap.Modal(document.getElementById('withdrawalModal'));

    // Listen for the exact event name you're dispatching
    Livewire.on('showWithdrawalModal', () => {
        withdrawalModal.show();
    });

    Livewire.on('hideWithdrawalModal', () => {
        withdrawalModal.hide();
    });

    const openAddFundsModal = new bootstrap.Modal(document.getElementById('openAddFundsModal'));

    Livewire.on('showOpenAddFundsModal', () => {
        openAddFundsModal.show();
    });

    Livewire.on('hideshowOpenAddFundsModal', () => {
        openAddFundsModal.hide();
    });
});
</script>

