<!-- resources/views/livewire/loan-details/collection-edit-form.blade.php -->
<div class=" mt-5">
    @if ($isOpen)
        <div class="card card-body">

            <h5 class="mb-0">Edit Collection</h5>


            <form wire:submit.prevent="save">

                <div class="form-group">
                    <label for="collectedAmount">Amount</label>
                    <input type="number" wire:model="collectedAmount" class="form-control" id="collectedAmount">
                </div>

                <div class="form-group">
                    <label for="collectionDate">Collection Date</label>
                    <input type="date" wire:model="collectionDate" class="form-control" id="collectionDate">
                </div>


                <div class="form-group">
                    <label for="collectionMethod">Collection Method</label>
                    <input type="text" wire:model="collectionMethod" class="form-control" id="collectionMethod">
                </div>

                <div class="form-group">
                    <label for="notes">Notes</label>
                    <textarea wire:model="notes" class="form-control" id="notes" rows="3"></textarea>
                </div>


                <div class="form-group">
                    <button type="button" wire:click="cancel" class="btn btn-secondary me-2">
                        Cancel
                    </button>
                    <button type="submit" class="btn bg-gradient-success">
                        Save Changes
                    </button>
                </div>

            </form>

        </div>
    @endif
</div>
