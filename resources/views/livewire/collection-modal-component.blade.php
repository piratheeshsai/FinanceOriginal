<div>
    @if ($showModal && $selectedCollection)
    <div class="modal fade show"
         style="display: block; background-color: rgba(0,0,0,0.5)"
         tabindex="-1"
         aria-modal="true"
         role="dialog"
         wire:click.self="closeModal"
         wire:key="collection-modal-{{ $scheduleId }}">
        <div class="modal-dialog modal-lg" wire:ignore.self>
            <div class="modal-content" wire:click.stop>
                <div class="modal-header bg-light">
                    <h5 class="modal-title">
                        <i class="fas fa-file-invoice me-2 text-primary"></i>
                        Collection Details
                    </h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Repayment Form -->
                        <div class="col-12 col-md-6">
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title border-bottom pb-2">Repayment Form</h6>
                                    <form wire:submit.prevent="submitPayment">
                                        <div class="form-group mb-3">
                                            <label for="repaymentAmount">Repayment Amount:</label>
                                            <input type="number"
                                                class="form-control @error('repaymentAmount') is-invalid @enderror"
                                                id="repaymentAmount" wire:model="repaymentAmount"
                                                placeholder="{{ $selectedCollection->pending_due }}" step="0.01"
                                                required>
                                            @error('repaymentAmount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="repaymentMethod">Repayment Method:</label>
                                            <select
                                                class="form-select @error('repaymentMethod') is-invalid @enderror"
                                                id="repaymentMethod" wire:model="repaymentMethod" required>
                                                <option value="">Select Method</option>
                                                <option value="Cash">Cash</option>
                                                <option value="Check">Check</option>
                                                <option value="Bank Deposit">Bank Deposit</option>
                                                <option value="Online Transfer">Online Transfer</option>
                                            </select>
                                            @error('repaymentMethod')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="description">Description (Optional):</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" wire:model="description"
                                                rows="2" placeholder="Additional notes..."></textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Hidden collectedBy -->
                                        <input type="hidden" name="collectedBy"
                                            value="{{ auth()->user()->name }}">

                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-primary" onclick="confirmSubmit()" wire:loading.attr="disabled">
                                                <span wire:loading.remove>Submit Payment</span>
                                                <span wire:loading>
                                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                                    Processing...
                                                </span>
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information - Improved for mobile -->
                        <div class="col-12 col-md-6">
                            <div class="card border-0 bg-light mb-3">
                                <div class="card-body">
                                    <h6 class="card-title border-bottom pb-2">Payment Information</h6>
                                    <dl class="row mb-0">
                                        <dt class="col-5 col-sm-4">Date:</dt>
                                        <dd class="col-7 col-sm-8">{{ $selectedCollection->date->format('d M, Y') }}</dd>

                                        <dt class="col-5 col-sm-4">Name:</dt>
                                        <dd class="col-7 col-sm-8 fw-bold text-break">
                                            {{ $selectedCollection->loan->customer->full_name }}</dd>

                                        <dt class="col-5 col-sm-4">Phone:</dt>
                                        <dd class="col-7 col-sm-8">
                                            {{ $selectedCollection->loan->customer->phone }}</dd>

                                        <dt class="col-5 col-sm-4">Due:</dt>
                                        <dd class="col-7 col-sm-8 text-danger fw-bold">
                                            {{ number_format($selectedCollection->due, 2) }}</dd>

                                        <dt class="col-5 col-sm-4">Paid:</dt>
                                        <dd class="col-7 col-sm-8 text-success fw-bold">
                                            {{ number_format($selectedCollection->loan->loanProgress->total_paid_amount, 2) }}
                                        </dd>

                                        <dt class="col-5 col-sm-4">Pending:</dt>
                                        <dd class="col-7 col-sm-8 fw-bold">
                                            {{ number_format($selectedCollection->pending_due, 2) }}</dd>
                                    </dl>
                                </div>
                            </div>

                            <!-- Address section -->
                            @if ($selectedCollection->loan->customer->permanent_address)
                                <div class="card border-0 bg-light mb-3">
                                    <div class="card-body">
                                        <h6 class="card-title border-bottom pb-2">Address</h6>
                                        <p class="mb-0 text-break">
                                            {{ $selectedCollection->loan->customer->permanent_address }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 text-white">
                        <i class="fas fa-info-circle me-2"></i>
                        To collect customer signatures, please export to PDF and have customers sign in the
                        signature column.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-backdrop fade show" wire:click="closeModal"></div>
    @endif
</div>

<script>
// Confirm payment submission
function confirmSubmit() {
    Swal.fire({
        title: 'Submit Payment?',
        text: "Are you sure you want to submit this payment?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, submit it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            Livewire.dispatch('submitPayment');
        }
    });
}

// Listen for successful payment event with print options
document.addEventListener('livewire:initialized', () => {
    Livewire.on('paymentSuccessWithPrintOptions', (data) => {
        const collectionId = data[0].collectionId;
        const message = data[0].message;

        console.log('Payment success event received:', collectionId, message);

        Swal.fire({
            title: 'Success!',
            text: message,
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-receipt"></i> POS Receipt',
            cancelButtonText: 'Close',
            showDenyButton: true,
            denyButtonText: '<i class="bi bi-file-pdf"></i> PDF Invoice'
        }).then((result) => {
            if (result.isConfirmed) {
                // POS Receipt - Using the component's wire:click method instead
                console.log('Printing POS receipt for collection:', collectionId);
                @this.printPOSReceipt(collectionId);
            } else if (result.isDenied) {
                // PDF Invoice - Using the component's wire:click method instead
                console.log('Printing PDF invoice for collection:', collectionId);
                @this.printA4Receipt(collectionId);
            }
        });
    });

    // Display error alert
    Livewire.on('show-error-alert', (data) => {
        Swal.fire({
            title: data.title,
            text: data.message,
            icon: data.icon
        });
    });

    Livewire.on('openInNewWindow', (data) => {
        const url = data.url || data[0].url;
        console.log('Opening in new window:', url);
        window.open(url, '_blank');
    });

});
</script>
