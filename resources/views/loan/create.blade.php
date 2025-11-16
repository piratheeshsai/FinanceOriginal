@extends('layouts.app')


@section('breadcrumb')
    Loan
@endsection

@section('page-title')
    New Loan
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-lg-9 col-12 mx-auto">
                <div class="card card-body mt-4">
                    <div class="card-header bg-light text-dark p-3 border rounded">
                        <h5 class="mb-0 fw-bold">New Loan</h5>
                    </div>
                    <hr class="horizontal dark my-3">



                    <form id="createLoanForm" method="POST" action="{{ route('loan.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label>Loan Type</label>
                                    <select id="loan_type" class="form-control" name="loan_type">
                                        <option value="">Select a loan type</option>
                                        <option value="individual">Individual</option>
                                        <option value="group">Group</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="group_id">Group</label>
                                    <select id="group_id" name="group_id" class="form-control" disabled>
                                        <option value="">Select Group</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->group_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="loan_scheme">Loan Scheme</label>
                                    <select id="loan_scheme" name="scheme_id" class="form-control">
                                        <option value="standard">Select Loan Scheme</option>
                                        @foreach ($loan_schemes as $loan_scheme)
                                            <option value="{{ $loan_scheme->id }}">{{ $loan_scheme->loan_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-6 col-12 mb-2">
                                <div id="customerFieldContainer" class="form-group">
                                    <label for="customer_id">Customer</label>
                                    <select id="customer_id" class="form-control" name="customer_id">
                                        <option value="">Select a customer</option>
                                        <!-- Add customer options dynamically -->
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-md-6 col-12 mb-2">
                                <div class="mb-3 position-relative">
                                    <label for="loan_amount" class="form-label fw-bold">Loan Amount</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="loan_amount" name="loan_amount"
                                            placeholder="Enter amount in LKR" required>
                                        <span class="input-group-text">LKR</span>
                                    </div>
                                    <div id="loanAmountFeedback" class="invalid-feedback">
                                        Please enter a valid loan amount.
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6 col-12 mb-2">
                                <div class="mb-3 position-relative">
                                    <label for="document_charge" class="form-label fw-bold">Document Charge</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="document_charge" name="document_charge"
                                            placeholder="Enter amount in LKR" required>
                                        <span class="input-group-text">LKR</span>
                                    </div>
                                    <div id="loanAmountFeedback" class="invalid-feedback">
                                        Please enter a valid  amount.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="center_id">Center</label>
                                    <select id="center_id" name="center_id" class="form-control">
                                        <option value="" disabled>Select Center</option>

                                        @foreach ($centers as $center)
                                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="loan_start">Start Date</label>
                                    <input type="text" id="loan_start" name="loan_start" class="form-control"
                                         placeholder="Loan Start Date" required>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-6 col-12 mb-2">


                                <div class="form-group"> <label for="guarantor">Guarantors</label> <select
                                        id="loan_guarantor" name="loan_guarantor[]" multiple class="form-control" required>
                                        <option value="standard" disabled>Select Guarantors</option>
                                        @foreach ($Guarantor as $Guarantors)
                                            <option value="{{ $Guarantors->id }}">{{ $Guarantors->full_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select at least one guarantor.</div>
                                </div>


                            </div>

                            <div class="col-md-6 col-12 mb-2">
                                <div class="form-group">
                                    <label for="loan_date">Loan Date</label>
                                    <input type="date" id="loan_date" name="loan_date" class="form-control"
                                         placeholder="Loan Date" required>
                                    </div>
                            </div>
                            <div class="col-12 text-right">
                                <button type="submit" class="btn btn-dark">Create Loan</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css"
        rel="stylesheet">
    <style>
        .text-right {
            text-align: right;
        }
    </style>



    <script>


// $(document).ready(function () {
//         $('#loan_start').datepicker({
//             format: 'yyyy-mm-dd',
//             autoclose: true,
//             todayHighlight: true
//         });
//     });

        document.getElementById('createLoanForm').addEventListener('submit', function(event) {
            event.preventDefault();
            var form = event.target;
            if (form.checkValidity() === false) {
                event.stopPropagation();
                form.classList.add('was-validated');
                alert('Please fill out all required fields correctly.');
            } else {
                form.submit();
            }
        }, false);



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


        document.querySelector('#loan_amount').addEventListener('input', function(event) {
            const input = event.target;
            const value = input.value.replace(/[^0-9.]/g, ''); // Allow only numbers and decimals
            input.value = value;

            if (!value || isNaN(value) || Number(value) <= 0) {
                input.classList.add('is-invalid'); // Invalid input
            } else {
                input.classList.remove('is-invalid'); // Valid input
            }
        });
    </script>
@endsection
