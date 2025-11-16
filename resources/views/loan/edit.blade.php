@extends('layouts.app')

@section('breadcrumb')
    Loan
@endsection

@section('page-title')
    Edit Loan
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-9 col-12 mx-auto">
                <div class="card card-body mt-4">
                    <div class="card-header">
                        <h6 class="card-title">Edit Loan</h6>
                    </div>
                    <hr class="horizontal dark my-3">

                    <!-- Update Form -->
                    <form id="updateLoanForm" method="POST" action="{{ route('loan.update', $loan->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Loan Type -->
                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label>Loan Type</label>
                                    <select id="loan_type" class="form-control" name="loan_type">
                                        <option value="">Select a loan type</option>
                                        <option value="individual" {{ old('loan_type', $loan->loan_type) == 'individual' ? 'selected' : '' }}>Individual</option>
                                        <option value="group" {{ old('loan_type', $loan->loan_type) == 'group' ? 'selected' : '' }}>Group</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Group -->
                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="group_id">Group</label>
                                    <select id="group_id" name="group_id" class="form-control" {{ old('loan_type', $loan->loan_type) == 'individual' ? 'disabled' : '' }}>
                                        <option value="">Select Group</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}" {{ old('group_id', $loan->group_id) == $group->id ? 'selected' : '' }}>
                                                {{ $group->group_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Loan Scheme -->
                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="loan_scheme">Loan Scheme</label>
                                    <select id="loan_scheme" name="scheme_id" class="form-control">
                                        <option value="">Select Loan Scheme</option>
                                        @foreach ($loan_schemes as $loan_scheme)
                                            <option value="{{ $loan_scheme->id }}" {{ old('scheme_id', $loan->scheme_id) == $loan_scheme->id ? 'selected' : '' }}>
                                                {{ $loan_scheme->loan_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Customer -->
                            <div class="col-md-6 col-12 mb-2">
                            <!-- Customer Dropdown -->

                            <div id="customerFieldContainer" class="form-group">
                                <label for="customer_id">Customer</label>
                                <select id="customer_id" class="form-control" name="customer_id">
                                    @if ($customer)
                                        <option value="{{ $customer->id }}" selected>
                                            {{ $customer->full_name }} ({{ $customer->customer_no }})
                                        </option>
                                    @else
                                        <option value="">No customer assigned</option>
                                    @endif
                                </select>
                            </div>

                            </div>


                        </div>

                        <div class="row">
                            <!-- Loan Amount -->
                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="loan_amount" class="form-label fw-bold">Loan Amount</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="loan_amount" name="loan_amount"
                                            value="{{ old('loan_amount', $loan->loan_amount) }}" placeholder="Enter amount in LKR" required>
                                        <span class="input-group-text">LKR</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-2">
                                <div class="mb-3 position-relative">
                                    <label for="document_charge" class="form-label fw-bold">Document Charge</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="document_charge" name="document_charge"
                                            placeholder="Enter amount in LKR"   value="{{ old('loan_amount', $loan->document_charge) }}" required>
                                        <span class="input-group-text">LKR</span>
                                    </div>
                                    <div id="loanAmountFeedback" class="invalid-feedback">
                                        Please enter a valid  amount.
                                    </div>
                                </div>
                            </div>
                            <!-- Start Date -->
                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="loan_start">Start Date</label>
                                    <input type="text" id="loan_start" name="loan_start" class="form-control"
                                        value="{{ old('loan_start', $loan->start_date) }}" placeholder="Loan Start Date" required>
                                </div>
                            </div>




                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="center_id">Center</label>
                                    <select id="center_id" name="center_id" class="form-control">
                                        <option value="" disabled>Select Center</option>
                                        @foreach ($centers as $center)
                                            <option value="{{ $center->id }}" {{ old('center_id', $loan->center_id) == $center->id ? 'selected' : '' }}>
                                                {{ $center->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Center -->


                            <!-- Guarantor -->
                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="loan_guarantor">Guarantor</label>
                                    <select id="loan_guarantor" name="loan_guarantor[]" multiple class="form-control" required>
                                        @foreach ($Guarantor as $guarantor)
                                            <option value="{{ $guarantor->id }}" {{ in_array($guarantor->id, old('loan_guarantor', $loan->guarantors->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $guarantor->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                             <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="loan_date">Loan Date</label>
                                    <input type="date" id="loan_date" name="loan_date" class="form-control" value="{{ old('loan_start', $loan->loan_date) }}"
                                         placeholder="Loan Date" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-dark">Update Loan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // $(document).ready(function() {
        //     $('#loan_start').datepicker({
        //         format: 'yyyy-mm-dd',
        //         todayHighlight: true,
        //         autoclose: true
        //     });
        // });



        document.addEventListener('DOMContentLoaded', function() {

// new Choices('#loan_amount');

const LoansScheme = new Choices('#loan_scheme', {
    searchEnabled: true,
    // removeItemButton: true
});
const center = new Choices('#center_id', {
    searchEnabled: true,
    removeItemButton: true,
    placeholder: true,
    placeholderValue: 'Select Center'

});

const Guarantor = new Choices('#loan_guarantor', {
    searchEnabled: true,
    removeItemButton: true,
    maxItemCount: 3

});

});
    </script>
@endsection
