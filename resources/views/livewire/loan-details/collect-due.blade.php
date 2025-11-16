<div class="card card-body">
    <div class="container mt-5">
        <h2>Payment Form</h2>

        <div wire:loading class="alert alert-info text-center">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Processing Payment, please wait...
        </div>
        <form wire:submit.prevent="submit">
            <div class="form-group">
                <label for="repaymentAmount">Repayment Amount:</label>
                <input type="number" class="form-control" id="repaymentAmount" wire:model="repaymentAmount" placeholder="Enter amount" required>
                @error('repaymentAmount') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="repaymentMethod">Repayment Method:</label>
                <select class="form-control" id="repaymentMethod" wire:model="repaymentMethod" required>
                    <option value="">Select Payment Method</option>
                    <option value="Cash">Cash</option>
                    <option value="Check">Check</option>
                    <option value="Bank Deposit">Bank Deposit</option>
                    <option value="Online Transfer">Online Transfer</option>
                </select>
                @error('repaymentMethod') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="collectionDate">Collection Date:</label>
                <input type="date" class="form-control" id="collectionDate" wire:model="collectionDate" required>
                @error('collectionDate') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="description">Description (Optional):</label>
                <textarea class="form-control" id="description" wire:model="description" placeholder="Enter description"></textarea>
                @error('description') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="form-group">
                <label for="collectedBy">Collected By:</label>
                <input type="text" class="form-control" id="collectedBy" value="{{ auth()->user()->name }}" readonly>
            </div>
            <button type="button" class="btn btn-primary" onclick="confirmQuickSubmit()">Quick Submit</button>

        </form>
    </div>



    <script>
        function confirmQuickSubmit() {
            Swal.fire({
                title: 'Submit Payment?',
                text: "Are you sure you want to submit this payment?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, submit it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('confirmQuickSubmit');
                }
            });
        }
    </script>



</div>


