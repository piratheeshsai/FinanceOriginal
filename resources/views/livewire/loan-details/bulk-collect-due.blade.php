

<div class=" card card-body">
    @if (session()->has('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <form wire:submit.prevent="submit">
        <div class="table-responsive">
            <table class="table align-items-center mb-0">
                <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Row</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Customer </th>
                         <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Amount</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Method</th>
                         <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                         <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Collection By</th>
                         <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description <br/>(optional)</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($collections as $index => $collection)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td style="width: 300px;">
                            <select
                                wire:model="collections.{{ $index }}.loan_id"
                                wire:key="loan-select-{{ $index }}"
                                class="form-select loan-select">
                                <option value="" disabled selected>Choose Loan</option>
                                @foreach($loans as $loan)
                                    <option value="{{ $loan['id'] }}">{{ $loan['borrower_name'] }} - {{ $loan['nic']}}</option>
                                @endforeach
                            </select>
                            @error("collections.{$index}.loan_id") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="number" wire:model="collections.{{ $index }}.amount" class="form-control" placeholder="Amount">
                            @error("collections.{$index}.amount") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <select wire:model="collections.{{ $index }}.method" class="form-control">
                                <option value="Cash">Cash</option>
                                <option value="ATM">ATM</option>
                                <option value="Bank Deposit">Bank Deposit</option>
                                <option value="Online Transfer">Online Transfer</option>
                            </select>
                            @error("collections.{$index}.method") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="date" wire:model="collections.{{ $index }}.collection_date" class="form-control">
                            @error("collections.{$index}.collection_date") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <select wire:model="collections.{{ $index }}.collected_by" class="form-control">
                                <option value="{{ auth()->user()->name }}">{{ auth()->user()->name }}</option>
                            </select>
                            @error("collections.{$index}.collected_by") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                        <td>
                            <input type="text" wire:model="collections.{{ $index }}.description" class="form-control" placeholder="Description">
                            @error("collections.{$index}.description") <span class="text-danger">{{ $message }}</span> @enderror
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <br />
        <hr class="horizontal dark my-1">

        <div class="d-flex justify-content-between align-items-center mt-3">
            <div class="fw-bold text-primary">
                Total Amount: <span class="text-dark" wire:poll.500ms>Rs. {{ array_sum(array_column($collections, 'amount')) }}</span>
            </div>
            <button type="submit" class="btn btn-primary">Submit All Collections</button>
        </div>
    </form>



    <script>
document.addEventListener('DOMContentLoaded', function() {
        // Initialize Choices.js for all select elements with loan-select class
        const elements = document.querySelectorAll('.loan-select');
        elements.forEach(function(element) {
            new Choices(element, {
                searchEnabled: true,
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Choose Loan ',
                searchPlaceholderValue: "Type to search...",
                noResultsText: 'No borrowers found',
                itemSelectText: ''
            });
        });

        // Re-initialize after Livewire updates
        Livewire.on('contentChanged', () => {
            elements.forEach(function(element) {
                if (!element.classList.contains('choices__input')) {
                    new Choices(element, {
                        searchEnabled: true,
                        removeItemButton: true,
                        placeholder: true,
                        placeholderValue: 'Choose Loan',
                        searchPlaceholderValue: "Type to search...",
                        noResultsText: 'No borrowers found',
                        itemSelectText: ''
                    });
                }
            });
        });
    });





    </script>

</div>
