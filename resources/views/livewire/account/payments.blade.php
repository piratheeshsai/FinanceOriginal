<div>
    <div class="card">
        <div class="card-header bg-gradient-dark">
            <h5 class="text-white mb-0">{{ $isEditing ? 'Edit Payment' : 'Create New Payment' }}</h5>
        </div>

        <div class="card-body">
            <form wire:submit.prevent="save">
                <div class="row g-3">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label mb-2">Payment Category</label>
                                <div wire:ignore>
                                    <select id="paymentCategory" wire:model.live="selectedCategory"
                                        class="form-select @error('selectedCategory') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('selectedCategory')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <a href="{{ route('accounts.PaymentCategory') }}"
                                class="text-info cursor-pointer font-weight-bold clickable-text">
                                Add / Edit
                            </a>
                        </div>
                    </div>

                    <!-- Supplier Selection -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label mb-2">Select Supplier</label>
                                <select id="supplierSelect" class="form-control @error('selectedSupplier') is-invalid @enderror" wire:model="selectedSupplier">
                                    <option value="">Select a supplier</option>
                                    @if (count($filteredSuppliers) > 0)
                                        @foreach ($filteredSuppliers as $supplier)
                                            <option value="{{ $supplier['id'] }}">
                                                {{ $supplier['name'] }} -
                                                {{ $supplier['payment_category']['name'] }}
                                                @if ($supplier['payment_category']['name'] === 'Salary')
                                                    ({{ number_format($supplier['salary'], 2) }})
                                                @endif
                                            </option>
                                        @endforeach
                                    @else
                                        <option disabled>
                                            {{ $selectedCategory ? 'No suppliers available' : 'Select a category first' }}
                                        </option>
                                    @endif
                                </select>
                                @error('selectedSupplier')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <a href="{{ route('accounts.PaymentSupplier') }}"
                                class="text-info cursor-pointer font-weight-bold clickable-text">
                                Add / Edit
                            </a>
                        </div>
                    </div>
                    <!-- Amount Field -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label mb-2">Amount</label>
                                <input type="number" wire:model.live="amount" id="amountInput"
                                    class="form-control @error('amount') is-invalid @enderror" step="0.01"
                                    @if ($isSalary) readonly @endif required autocomplete="off">
                                @error('amount')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Payment Date -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label mb-2">Payment Date</label>
                                <input type="date" wire:model="paymentDate"
                                    class="form-control @error('paymentDate') is-invalid @enderror" required>
                                @error('paymentDate')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Form Actions -->
                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-3 mt-4 border-top pt-4">
                            <button type="button" wire:click="resetForm" class="btn btn-secondary px-4">
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary px-4" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    {{ $isEditing ? 'Update Payment' : 'Create Payment' }}
                                </span>
                                <span wire:loading>{{ $isEditing ? 'Updating...' : 'Saving...' }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <!-- Payment Table -->
    <div class="card mt-4">
        <div class="card-body">
            <div class="card card-background card-background-after-none align-items-start">
                <div class="full-background"
                    style="background-image: linear-gradient(310deg, #141727 0%, #3a416f 100%)"></div>
                <div class="card-body text-start p-4 w-100">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <h5 class="text-white">Recent Payments</h5>
                        </div>
                        <div class="col-md-9 text-end">
                            <div class="row justify-content-end">
                                <div class="col-lg-4 mb-2 mb-lg-0">
                                    <input type="text" wire:model.live="search" placeholder="Search supplier..."
                                        class="form-control">
                                </div>
                                <div class="col-lg-4 mb-2 mb-lg-0">
                                    <select wire:model.live="statusFilter" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <input type="date" wire:model.live="dateFilter" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table align-items-center mb-0">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Category
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Suppliers
                            </th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Amount</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            <tr>
                                <td class="text-sm font-weight-bold">{{ $payment->created_at->format('Y-m-d') }}</td>
                                <td class="text-sm font-weight-bold">{{ $payment->paymentCategory->name }}</td>
                                <td class="text-sm font-weight-bold">
                                    @foreach ($payment->suppliers as $supplier)
                                        {{ $supplier->name }}@if (!$loop->last)
                                            ,
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-sm font-weight-bold">Rs. {{ number_format($payment->total_amount, 2) }}
                                </td>
                                <td class="align-middle">
                                    @if ($payment->status === 'pending')
                                        @can('approve-payment')
                                            {{-- Replace with your actual permission name --}}
                                            <div class="d-flex gap-2 align-items-center">
                                                <button
                                                    onclick="confirmAction({
                                                        title: 'Approve Payment?',
                                                        text: 'Are you sure you want to approve this payment?',
                                                        icon: 'warning',
                                                        confirmButtonText: 'Yes, approve it!'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            Livewire.dispatch('approvePayment', { paymentId: {{ $payment->id }} });
                                                        }
                                                    })"
                                                    class="btn btn-sm btn-dark d-flex align-items-center gap-1 px-2"
                                                    wire:loading.attr="disabled"
                                                    wire:target="approvePayment({{ $payment->id }})">
                                                    <span wire:loading.remove
                                                        wire:target="approvePayment({{ $payment->id }})">
                                                        <i class="fas fa-check-circle"></i>
                                                    </span>
                                                    <span wire:loading wire:target="approvePayment({{ $payment->id }})">
                                                        <span class="spinner-border spinner-border-sm"
                                                            role="status"></span>
                                                    </span>
                                                    Approve
                                                </button>

                                                <button wire:click="openRejectModal({{ $payment->id }})"
                                                    class="btn btn-sm btn-danger px-3">
                                                    <i class="fas fa-times-circle"></i>
                                                    Reject
                                                </button>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-warning rounded-pill d-flex align-items-center">
                                                    <i class="fas fa-clock me-2"></i>
                                                    Pending Review
                                                </span>
                                            </div>
                                        @endcan
                                    @else
                                        <div class="d-flex align-items-center gap-2">
                                            <span
                                                class="badge rounded-pill d-flex align-items-center
                                                    {{ $payment->status === 'approved' ? 'bg-success' : 'bg-danger' }}">
                                                @if ($payment->status === 'approved')
                                                    <i class="fas fa-check-circle me-2"></i>
                                                @else
                                                    <i class="fas fa-times-circle me-2"></i>
                                                @endif
                                                {{ ucfirst($payment->status) }}
                                            </span>

                                            @if ($payment->status === 'rejected' && $payment->rejection_reason)
                                                {{-- <div class="alert alert"> <!-- Removed d-none -->
                                                    Rejection Reason: {{ $payment->rejection_reason }}<br>
                                                    Reason Length: {{ strlen($payment->rejection_reason) }}
                                                </div> --}}
                                                <button class="btn btn-sm text-danger px-2" data-bs-toggle="popover"
                                                    data-bs-placement="top" data-bs-trigger="hover"
                                                    data-bs-title="Rejection Reason"
                                                    data-bs-content="{{ e($payment->rejection_reason) }}"
                                                    data-bs-html="true">
                                                    <i class="fas fa-info-circle"></i>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if ($payment->status === 'approved' && $payment->voucher)
                                            <a href="{{ route('vouchers.show', $payment->voucher) }}" target="_blank"
                                                class="btn btn-sm btn-info">
                                                Voucher
                                            </a>
                                        @endif
                                        <a wire:click="enterEditMode({{ $payment->id }})"
                                            class="btn btn-sm btn-primary {{ $payment->status === 'approved' ? 'disabled' : '' }}"
                                            {{ $payment->status === 'approved' ? 'disabled' : '' }}>
                                            <i class="fas fa-edit"></i>
                                         </a>
                                        <button wire:click="deletePayment({{ $payment->id }})"
                                            class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $payments->links('livewire::bootstrap') }}
            </div>
        </div>
    </div>
    @if ($showRejectModal)
        <div class="modal fade show" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Request</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <textarea wire:model="rejectReason" class="form-control" placeholder="Enter rejection reason..." rows="4"></textarea>
                        @error('rejectReason')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">Cancel</button>
                        <button type="button" class="btn btn-danger" wire:click="rejectRequest">
                            Submit Rejection
                            <span wire:loading wire:target="rejectRequest"
                                class="spinner-border spinner-border-sm"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif


</div>




<script>
    document.addEventListener("DOMContentLoaded", function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl, {
                html: true // Enable HTML content if needed
            });
        });
    });
    document.addEventListener("DOMContentLoaded", function() {
        function initPopovers() {
            var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        }

        initPopovers(); // Initialize on page load

        // Reinitialize after Livewire updates
        document.addEventListener('livewire:load', initPopovers);
        document.addEventListener('livewire:update', initPopovers);
    });




    document.addEventListener('DOMContentLoaded', function() {
    // Get the supplier select element
    const supplierSelect = document.getElementById('supplierSelect');

    // Listen for changes on the supplier select
    supplierSelect.addEventListener('change', function() {
        // Get all selected options
        const selectedOptions = Array.from(this.selectedOptions).map(option => option.value);

        // Dispatch the selected suppliers to Livewire
        Livewire.dispatch('setSelectedSuppliers', {
            suppliers: selectedOptions
        });
    });

    // Listen for the suppliersUpdated event from Livewire
    window.addEventListener('suppliersUpdated', (event) => {
        console.log('Suppliers updated:', event.detail.suppliers);

        // Clear existing options
        supplierSelect.innerHTML = "";

        // Add new supplier options
        if (event.detail.suppliers && event.detail.suppliers.length > 0) {
            event.detail.suppliers.forEach((supplier) => {
                let option = document.createElement("option");
                option.value = supplier.id;

                // Format the option text
                let optionText = `${supplier.name} - ${supplier.payment_category.name}`;
                if (supplier.payment_category.name === 'Salary') {
                    optionText += ` (${parseFloat(supplier.salary).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})})`;
                }
                option.textContent = optionText;

                supplierSelect.appendChild(option);
            });
        } else {
            // Add placeholder if no suppliers
            let option = document.createElement("option");
            option.disabled = true;
            option.textContent = "No suppliers available for this category";
            supplierSelect.appendChild(option);
        }
    });

    // Listen for amount-updated event
    window.addEventListener('amount-updated', (event) => {
        const amountInput = document.getElementById('amountInput');
        if (amountInput) {
            amountInput.value = event.detail.amount;
            amountInput.dispatchEvent(new Event('input'));
        }
    });
});






    document.addEventListener('livewire:init', () => {
        Livewire.on('amount-updated', (event) => {
            const amountInput = document.getElementById('amountInput');
            if (amountInput) {
                // Force UI update for readonly fields
                amountInput.value = event.amount;
                amountInput.dispatchEvent(new Event('input'));
            }
        });
    });
</script>

</div>
