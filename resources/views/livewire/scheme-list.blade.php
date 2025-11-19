<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            @if (session('success'))
                <script>
                    Swal.fire({
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                </script>
            @endif


            <div class="card-header pb-0 d-flex justify-content-between align-items-center pb-0">
                <h5>Loan Schemes</h5>
                @can('Create Schemes')
                <a href="javascript:void(0);" class="btn btn-dark mb-3" data-bs-toggle="modal"
                    data-bs-target="#loanSchemeModal">
                    New Loan Scheme
                </a>
                @endcan
            </div>
            <div class="card-body px-0 pt-0 pb-2">
                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0 table-flush" >
                        <thead>
                            <tr>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                    #</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                    Loan Name</th>
                                <th class="text-uppercase text-secondary text-xxs
                                    Loan Type</th>

                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Interest Rate (%)</th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                    Repayment Duration</th>

                                <th
                                    class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Loan Term</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
    Doc. Charge (%)
</th>

                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                    Action
                                </th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($loanSchemes as $scheme)
                                <tr>

                                     <td class="text-center" style="width: 50px;">
                                                            <h6 class="mb-0 text-sm" style="font-size: 0.9rem;">{{ $loop->iteration }}</h6>
                                                        </td>
                                    <td>
                                        <h6 class="mb-0 me-2 ps-2 text-sm">{{ $scheme->loan_name }}</h6>
                                    </td>

                                    <td class="align-middle">
                                        <p class="text-xs font-weight-bold mb-0">{{ ucfirst($scheme->loan_type) }}
                                        </p>
                                    </td>

                                    <td>
                                        <h6 class="mb-0 align-middle text-center text-sm">
                                            {{ $scheme->interest_rate }}%</h6>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ ucfirst($scheme->collecting_duration) }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0"> {{ $scheme->loan_term }}</p>
                                    </td>
                                    <td class="align-middle text-center">
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $scheme->document_charge_percentage ?? '-' }}
                                        </p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-3 py-1">

                                            <div class="me-2">

                                                <!-- Trigger Button -->
                                                <button wire:click="openPaymentModal({{ $scheme->id }})"
                                                    class="btn btn-dark btn-sm px-2">
                                                    <i class="fa-solid fa-calculator"></i>
                                                </button>


                                            </div>

                                            <div class="me-2">

                                                @can('Edit Schemes')
                                                <button wire:click="openEditModal({{ $scheme->id }})"
                                                    class="btn btn-dark btn-sm px-2">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                @endcan

                                            </div>

                                            @can('Delete Schemes')
                                                 <div class="d-flex flex-column justify-content-center">
                                                    <button type="button" wire:click="deleteConformation({{$scheme->id}})" class="btn btn-danger btn-sm px-2"><i class="fa-solid fa-trash"></i></button>
                                                </div>
                                            @endcan
                                        </div>
                                    </td>
                                    {{-- <td>{{ $scheme->created_at->format('d-m-Y') }}</td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No Loan Schemes Available</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                    <div wire:ignore.self class="modal fade" id="paymentModal" tabindex="-1"
                        aria-labelledby="paymentModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="paymentModalLabel">Calculate Repayment</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Input for Loan Amount -->
                                    <div class="mb-3">
                                        <label for="amount" class="form-label">Enter Loan Amount</label>
                                        <input type="number" wire:model="amount" class="form-control"
                                            placeholder="Enter amount">
                                    </div>
                                    <!-- Calculate Button -->
                                    <button wire:click="calculateDue" class="btn btn-dark">Check</button>
                                    <div class="modal-footer d-flex justify-content-between">
                                        <div class="btn-group">
                                            <button wire:click="exportExcel" class="btn btn-success">Export
                                                Excel</button>
                                            <button wire:click="exportCsv" class="btn btn-info">Export CSV</button>
                                            <button wire:click="exportPDF"  class="btn btn-danger">Export PDF</button>
                                        </div>
                                    </div>
                                    <!-- Display Results Table -->
                                    @if ($payments)
                                    <div class="table-responsive p-0">
                                        <table class="table table-flush mt-3">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Due Amount</th>
                                                    <th>Interest</th>
                                                    <th>Total Due</th>
                                                </tr>
                                            </thead>
                                            <tbody>


                                                @foreach ($payments as $payment)
                                                    <tr>
                                                        <td class="text-center" style="width: 50px;">
                                                            <h6 class="mb-0 text-sm" style="font-size: 0.9rem;">{{ $loop->iteration }}</h6>
                                                        </td>
                                                        <td>{{ number_format($payment['due'], 2) }}</td>
                                                        <td>{{ number_format($payment['interest'], 2) }}</td>
                                                        <td>{{ number_format($payment['total'], 2) }}</td>
                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <hr class="my-4">
                                                    <td> <strong>Total:</strong></td>
                                                    <td> {{ number_format(collect($payments)->sum('due'), 3) }}</td>
                                                    <td> {{ number_format(collect($payments)->sum('interest'), 3) }}</td>
                                                    <td> {{ number_format(collect($payments)->sum('total'), 3) }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="modal fade" id="loanSchemeModal" tabindex="-1" aria-labelledby="loanSchemeModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="loanSchemeModalLabel">Create New Loan Scheme</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    @livewire('loan-scheme-form')
                                </div>
                            </div>
                        </div>
                    </div>

                    <div wire:ignore.self class="modal fade" id="editLoanSchemeModal" tabindex="-1" aria-labelledby="editLoanSchemeModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editLoanSchemeModalLabel">Edit Loan Scheme</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form wire:submit.prevent="updateLoanScheme">
                                        <div class="mb-3">
                                            <label for="editLoanName" class="form-label">Loan Name</label>
                                            <input type="text" id="editLoanName" class="form-control" wire:model="editLoanName">
                                            @error('editLoanName') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        {{-- <div class="mb-3">
                                            <label for="editLoanType" class="form-label">Loan Type</label>
                                            <input type="text" id="editLoanType" class="form-control" wire:model="editLoanType">
                                            @error('editLoanType') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div> --}}

                                        <div class="mb-3">
                                            <label for="editLoanType" class="form-label">Loan Type</label>
                                            <select wire:model="editLoanType" class="form-control" id="editLoanType">
                                                <option value="">Select Loan Type</option>
                                                <option value="group">Group</option>
                                                <option value="individual">Individual</option>
                                            </select>
                                            @error('editLoanType') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="editInterestRate" class="form-label">Interest Rate (%)</label>
                                            <input type="number" id="editInterestRate" class="form-control" wire:model="editInterestRate" step="0.01">
                                            @error('editInterestRate') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>



                                        <div class="mb-3">
                                            <label for="editRepaymentDuration" class="form-label">Collecting Duration</label>
                                            <select wire:model="editRepaymentDuration" class="form-control" id="editRepaymentDuration">
                                                <option value="">Select Duration</option>
                                                <option value="daily">Daily</option>
                                                <option value="weekly">Weekly</option>
                                                <option value="monthly">Monthly</option>
                                            </select>
                                            @error('editRepaymentDuration') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="editLoanTerm" class="form-label">Loan Term</label>
                                            <input type="number" id="editLoanTerm" class="form-control" wire:model="editLoanTerm">
                                            @error('editLoanTerm') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="editDocumentChargePercentage" class="form-label">Document Charge Percentage</label>
                                            <input type="number" id="editDocumentChargePercentage" class="form-control" wire:model="editDocumentChargePercentage" step="0.01">
                                            @error('editDocumentChargePercentage') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>

                                        <button type="submit" class="btn btn-primary">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>



        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    {{ $loanSchemes->links('livewire::bootstrap') }}
                </div>
            </div>
        </div>

    </div>
</div>




<script>


window.addEventListener('show-edit-modal', () => {
        const modal = new bootstrap.Modal(document.getElementById('editLoanSchemeModal'));
        modal.show();
    });

    window.addEventListener('hide-edit-modal', () => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('editLoanSchemeModal'));
        modal.hide();
    });

    window.addEventListener('showModal', () => {
        new bootstrap.Modal(document.getElementById('paymentModal')).show();
    });
</script>
<script type="text/javascript">

window.addEventListener('show-delete-conformation', event=> {

           Swal.fire({
            icon: "question",
            title: "{{__('Are you sure?')}}",
            showCancelButton: true,
            confirmButtonText: "{{__('Delete')}}",
            cancelButtonText: "{{__('Cancel')}}",
            }).then((result) => {
            if (result.isConfirmed) {
                    Livewire.dispatch('deleteConf')
            }
            });
    });

       window.addEventListener('LoanSchemeDeleted', event=> {

                    Swal.fire(
                        'Deleted!',
                        "{{__('Loan Scheme has been deleted successfully.')}}",
                       'success'
                    )

       });

       window.addEventListener('SchemeUpdated', event => {

Swal.fire(
    'Updated',
    "{{ __('Scheme has been Updated successfully.') }}",
    'success'
)

});


document.addEventListener('livewire:initialized', () => {
    Livewire.on('showAlert', (params) => {
        Swal.fire({
            icon: params.type,
            title: params.message,
            confirmButtonColor: '#3085d6',
        });
    });
});
</script>

