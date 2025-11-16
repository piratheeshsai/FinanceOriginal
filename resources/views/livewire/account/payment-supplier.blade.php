<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header Card -->


            <div class="card bg-gradient-dark">
                <div class="card-body p-4">
                    <div class="row align-items-center justify-content-between">
                        <div class="col">
                            <h6 class="text-white mb-0">Manage Suppliers</h6>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('accounts.payments') }}" class="btn btn-light btn-sm">
                                Back to Payments
                            </a>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Form Card -->
            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $isEditing ? 'Edit Supplier' : 'Add New Supplier' }}</h5>
                    <form wire:submit.prevent="save">
                        <div class="row g-3">
                            <!-- Payment Category -->
                            <div class="col-12 col-md-6">
                                <label class="form-label">Payment Category</label>
                                <select wire:model="payment_category_id"
                                        class="form-select @error('payment_category_id') is-invalid @enderror">
                                    <option value="">Select Category</option>
                                    @foreach ($paymentCategories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('payment_category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Supplier Name -->
                            <div class="col-12 col-md-6">
                                <label class="form-label">Supplier Name</label>
                                <input type="text"
                                       wire:model="name"
                                       placeholder="Supplier's name"
                                       class="form-control @error('name') is-invalid @enderror">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NIC Number -->
                            <div class="col-12 col-md-4">
                                <label class="form-label d-flex justify-content-between">
                                    <span>NIC Number</span>
                                    <span class="text-muted" style="font-size: 0.8rem">Optional</span>
                                </label>
                                <input type="text"
                                       wire:model="nic"
                                       placeholder="NIC Number"
                                       class="form-control @error('nic') is-invalid @enderror">
                                @error('nic')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Salary -->
                            <div class="col-12 col-md-4">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Salary</span>
                                    <span class="text-muted" style="font-size: 0.8rem">Optional</span>
                                </label>
                                <input type="number"
                                       wire:model="salary"
                                       step="0.01"
                                       placeholder="Salary Amount"
                                       class="form-control @error('salary') is-invalid @enderror">
                                @error('salary')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bank Details -->
                            <div class="col-12 col-md-4">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Account Number</span>
                                    <span class="text-muted" style="font-size: 0.8rem">Optional</span>
                                </label>
                                <input type="text"
                                       wire:model="bank_account_number"
                                       placeholder="Bank Account"
                                       class="form-control @error('bank_account_number') is-invalid @enderror">
                                @error('bank_account_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Bank Branch -->
                            <div class="col-12">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Bank Branch</span>
                                    <span class="text-muted" style="font-size: 0.8rem">Optional</span>
                                </label>
                                <input type="text"
                                       wire:model="bank_account_name"
                                       placeholder="Bank Branch Name"
                                       class="form-control @error('bank_account_name') is-invalid @enderror">
                                @error('bank_account_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 text-end">
                                <div class="d-flex gap-2 justify-content-end">
                                    @if($isEditing)
                                        <button type="button"
                                                wire:click="cancelEdit"
                                                class="btn btn-secondary">
                                            Cancel
                                        </button>
                                    @endif
                                    <button type="submit" class="btn btn-primary">
                                        {{ $isEditing ? 'Update' : 'Save' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Table Card -->
            <div class="card mt-4">
                <div class="card-header p-4 bg-gradient-dark">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="text-white mb-0">Supplier List</h5>
                        </div>
                        <div class="col-auto">
                            <input type="text"
                                   wire:model.live="search"
                                   placeholder="Search suppliers..."
                                   class="form-control form-control-sm">
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs text-center">#</th>
                                    <th class="text-uppercase text-secondary text-xxs text-center">Category</th>
                                    <th class="text-uppercase text-secondary text-xxs text-center">Supplier</th>
                                    <th class="text-uppercase text-secondary text-xxs text-center">NIC</th>
                                    <th class="text-uppercase text-secondary text-xxs text-center">Salary</th>
                                    <th class="text-uppercase text-secondary text-xxs text-center">Bank Details</th>
                                    <th class="text-uppercase text-secondary text-xxs text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($PaymentSuppliers as $supplier)
                                    <tr>
                                        <td class="text-center">
                                            <span class="text-xs font-weight-bold">{{ $loop->iteration }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-xs font-weight-bold">
                                                {{ $supplier->paymentCategory->name }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-xs font-weight-bold">{{ $supplier->name }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-xs font-weight-bold">{{ $supplier->nic ?? '-' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="text-xs font-weight-bold">
                                                {{ $supplier->salary ? number_format($supplier->salary, 2) : '-' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="text-xs">
                                                <div class="font-weight-bold">{{ $supplier->bank_account_name ?? '-' }}</div>
                                                <div class="text-muted">{{ $supplier->bank_account_number ?? '' }}</div>
                                            </div>
                                        </td>
                                        <td class="text-end pe-3">
                                            <button wire:click="edit({{ $supplier->id }})"
                                                    class="btn btn-link text-dark px-2">
                                                <i class="fa-solid fa-pen-to-square fa-sm"></i>
                                            </button>
                                            <button wire:click="delete({{ $supplier->id }})"
                                                    class="btn btn-link text-danger px-2">
                                                <i class="fa-solid fa-trash fa-sm"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">No suppliers found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer px-4">
                        {{ $PaymentSuppliers->links('livewire::bootstrap') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
