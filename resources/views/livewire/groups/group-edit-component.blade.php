
<div>
    <form wire:submit.prevent="updateGroup">
        <div class="mb-3">
            <label for="Group Code" class="form-label">Group Name</label>
            <input type="text" id="groupCode" class="form-control" wire:model="groupCode" readonly>
            @error('groupName') <span class="text-danger">{{ $message }}</span> @enderror
        </div>
        <div wire:ignore class="mb-3">
            <label for="center" class="form-label">Select Center</label>
            <select wire:model="selectedCenter" id="centerE" class="form-control center-selectE" disabled>
                <option value="">Select Center</option>
                @foreach ($centers as $center)
                    <option value="{{ $center->id }}">{{ $center->name }}</option>
                @endforeach
            </select>
            @error('selectedCenter')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <div wire:ignore class="form-group mb-3">
            <label for="members" class="text-start">Select Group Members (3-5)</label>
            <select wire:model="selectedMembers" class="form-control w-full" id="membersE" name="members[]" multiple>
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
        <button type="submit" class="btn btn-success">Save Changes</button>
    </form>
</div>
@script()
    <script>
        $(document).ready(function() {
            // Initialize Choice.js on the #members select element
            const choices = new Choices('#membersE', {
                removeItemButton: true,
                maxItemCount: 5,
                searchResultLimit: 10,
                renderChoiceLimit: 5
            });

            // Listen for change event and update the Livewire property
            $('#membersE').on('change', function() {
                let data = choices.getValue(true); // Use Choices.js method to get selected values
                console.log(data);
                $wire.set('selectedMembers', data, true);
                $wire.selectedMembers = data;
            });
        });


    // Listen for success message
    Livewire.on('successMessage', (message) => {
        Swal.fire({
            title: 'Success!',
            text: message,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    });

    // Listen for modal close
    Livewire.on('closeModal', () => {
        const modal = document.getElementById('editGroupModal');
        if (modal) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            }
        }
    });
</script>

@endscript



