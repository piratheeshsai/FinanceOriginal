<div>
    <form wire:submit.prevent="saveGroup">
        <div wire:ignore class="mb-3">
            <label for="center" class="form-label">Select Center</label>
            <select wire:model="selectedCenter" id="center" class="form-control center-select">
                <option value="">Select Center</option>
                @foreach ($centers as $center)
                    <option value="{{ $center->id }}">{{ $center->name }}</option>
                @endforeach
            </select>
            @error('selectedCenter')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>


        {{-- <div class="mb-3">
            <label for="groupCode" class="form-label">Group Code</label>
            <input type="text" wire:model="groupCode" id="groupCode" class="form-control" readonly>
        </div> --}}

        <div wire:ignore class="form-group mb-3">
            <label for="members" class="text-start">Select Group Members (3-5)</label>
            <select wire:model="selectedMembers" class="form-control w-full" id="members" name="members[]" multiple>
                @foreach ($allCustomers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->full_name }} ({{ $customer->nic }})</option>
                @endforeach
            </select>
            @error('selectedMembers')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="group_leader" class="text-start">Select Group Leader</label>
            <select wire:model="group_leader" class="form-control" id="group_leader">
                <option value="">Select Group Leader</option>

                <!-- Iterate through all customers -->
                @foreach ($allCustomers as $customer)
                    <!-- Only show selected members -->
                    @if (in_array($customer->id, $this->selectedMembers))
                        <option value="{{ $customer->id }}"
                            @if ($customer->id == $group_leader) selected @endif>
                            <!-- Display customer name and NIC, preselect if it matches group_leader -->
                            {{ $customer->full_name }} ({{ $customer->nic }})
                        </option>
                    @endif
                @endforeach
            </select>

            <!-- Error display for group_leader -->
            @error('group_leader')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>


        <button type="submit" class="btn btn-primary">Create Group</button>
    </form>


</div>


@script()
    <script>
        $(document).ready(function() {
            // Initialize Choice.js on the #members select element
            const choices = new Choices('#members', {
                removeItemButton: true,
                maxItemCount: 5,
                searchResultLimit: 5, // Limit search results to 10
                renderChoiceLimit: -1
            });


            // Listen for change event and update the Livewire property
            $('#members').on('change', function() {
                let data = choices.getValue(true); // Use Choices.js method to get selected values
                console.log(data);
                $wire.set('selectedMembers', data, true);
                $wire.selectedMembers = data; //
            });

        });


        $(document).ready(function() {
    // Initialize Choices.js for single selection
    const centerSelect = new Choices('.center-select', {
        searchEnabled: true, // Enables search
        placeholderValue: 'Select Center',
        maxItemCount: 1,
        noResultsText: 'No centers found',
        itemSelectText: '', // Optional: This can remove the default text when an item is selected
    });
});



Livewire.on('closeModal', () => {
    const modalElement = document.getElementById('createGroupModal');
    if (modalElement) {
        const modal = bootstrap.Modal.getInstance(modalElement);
        if (modal) {
            modal.hide();  // Hide the modal
        } else {
            const newModal = new bootstrap.Modal(modalElement);
            newModal.hide();
        }

        // Reset the form fields in the modal
        const form = modalElement.querySelector('form');
        if (form) {
            form.reset(); // Reset form fields
        }
    }
});



    </script>
@endscript

<script>
// Listen for reload page event
document.addEventListener('livewire:init', () => {
    Livewire.on('show-success-alert', (data) => {
        // Show success message
        showSuccessMessage(data[0].message);

        // Reload page after showing message (with delay)
        setTimeout(() => {
            location.reload();
        }, 1500); // 1.5 seconds delay
    });

    // Alternative: Listen for specific reload event
    Livewire.on('reloadPage', () => {
        setTimeout(() => {
            location.reload();
        }, 1500);
    });

    Livewire.on('show-error-alert', (data) => {
        // Show error message (no reload for errors)
        showErrorMessage(data[0].message);
    });
});
    </script>
