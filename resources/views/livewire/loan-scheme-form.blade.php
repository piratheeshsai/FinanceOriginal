<div>
    <form wire:submit.prevent="save">
        <div class="mb-3">
            <label for="loan_name" class="form-label">Loan Name</label>
            <input type="text" wire:model="loan_name" class="form-control" id="loan_name">
            @error('loan_name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="loan_type" class="form-label">Loan Type</label>
            <select wire:model="loan_type" class="form-control" id="loan_type">
                <option value="">Select Loan Type</option>
                <option value="group">Group</option>
                <option value="individual">Individual</option>
            </select>
            @error('loan_type') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="interest_rate" class="form-label">Interest Rate</label>
            <input type="number" step="0.01" wire:model="interest_rate" class="form-control" id="interest_rate">
            @error('interest_rate') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

         <div class="mb-3">
            <label for="document_charge_percentage" class="form-label">Document Charge Percentage</label>
            <input type="number" step="0.01" wire:model="document_charge_percentage" class="form-control" id="document_charge_percentage">
            @error('document_charge_percentage') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="collecting_duration" class="form-label">Collecting Duration</label>
            <select wire:model="collecting_duration" class="form-control" id="collecting_duration">
                <option value="">Select Duration</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
            </select>
            @error('collecting_duration') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <div class="mb-3">
            <label for="loan_term" class="form-label">Loan Term</label>
            <input type="number" wire:model="loan_term" class="form-control" id="loan_term">
            @error('loan_term') <span class="text-danger">{{ $message }}</span> @enderror
        </div>



        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
