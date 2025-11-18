@extends('layouts.app')

@section('breadcrumb')
    Loan
@endsection

@section('page-title')
    Edit Loan
@endsection

@section('content')
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10 col-12">
                <div class="card border-0 shadow-lg rounded-4 mt-4">
                    <div class="card-header bg-navy text-white py-3 rounded-top-4">
                        <h5 class="mb-0 fw-bold text-white">Edit Loan details</h5>
                    </div>
                    <div class="card-body bg-light-teal rounded-bottom-4">
                        <form id="updateLoanForm" method="POST" action="{{ route('loan.update', $loan->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1">Loan Type <span class="text-danger">*</span></label>
                                    <select id="loan_type" class="form-select border-teal" name="loan_type" required>
                                        <option value="">Select a loan type</option>
                                        <option value="individual" {{ old('loan_type', $loan->loan_type) == 'individual' ? 'selected' : '' }}>Individual</option>
                                        <option value="group" {{ old('loan_type', $loan->loan_type) == 'group' ? 'selected' : '' }}>Group</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="group_id">Group <span class="text-danger">*</span></label>
                                    <select id="group_id" name="group_id" class="form-select border-teal" {{ old('loan_type', $loan->loan_type) == 'individual' ? 'disabled' : '' }} required>
                                        <option value="">Select Group</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}" {{ old('group_id', $loan->group_id) == $group->id ? 'selected' : '' }}>
                                                {{ $group->group_code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="loan_scheme">Loan Scheme <span class="text-danger">*</span></label>
                                    <select id="loan_scheme" name="scheme_id" class="form-select border-teal" required>
                                        <option value="">Select Loan Scheme</option>
                                        @foreach ($loan_schemes as $loan_scheme)
                                            <option value="{{ $loan_scheme->id }}" {{ old('scheme_id', $loan->scheme_id) == $loan_scheme->id ? 'selected' : '' }}>
                                                {{ $loan_scheme->loan_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="customer_id">Customer <span class="text-danger">*</span></label>
                                    <select id="customer_id" class="form-select border-teal" name="customer_id" required>
                                        @if ($customer)
                                            <option value="{{ $customer->id }}" selected>
                                                {{ $customer->full_name }} ({{ $customer->customer_no }})
                                            </option>
                                        @else
                                            <option value="">No customer assigned</option>
                                        @endif
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="loan_amount" class="form-label fw-bold text-navy mb-1">Loan Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-teal" id="loan_amount" name="loan_amount"
                                            value="{{ old('loan_amount', $loan->loan_amount) }}" placeholder="Enter amount in LKR" required>
                                        <span class="input-group-text bg-teal text-white">LKR</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="document_charge" class="form-label fw-bold text-navy mb-1">Document Charge <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-teal" id="document_charge" name="document_charge"
                                            placeholder="Enter amount in LKR" value="{{ old('document_charge', $loan->document_charge) }}" required>
                                        <span class="input-group-text bg-teal text-white">LKR</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="center_id">Center <span class="text-danger">*</span></label>
                                    <select id="center_id" name="center_id" class="form-select border-teal" required>
                                        <option value="" disabled>Select Center</option>
                                        @foreach ($centers as $center)
                                            <option value="{{ $center->id }}" {{ old('center_id', $loan->center_id) == $center->id ? 'selected' : '' }}>
                                                {{ $center->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="loan_start">Start Date <span class="text-danger">*</span></label>
                                    <input type="text" id="loan_start" name="loan_start" class="form-control border-teal"
                                        value="{{ old('loan_start', $loan->start_date) }}" placeholder="Loan Start Date" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="loan_guarantor">Guarantors <span class="text-danger">*</span></label>
                                    <select id="loan_guarantor" name="loan_guarantor[]" multiple class="form-select border-teal" required>
                                        @foreach ($Guarantor as $guarantor)
                                            <option value="{{ $guarantor->id }}" {{ in_array($guarantor->id, old('loan_guarantor', $loan->guarantors->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $guarantor->full_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 text-end mt-3">
                                    <button type="submit" class="btn btn-teal px-4 py-2 rounded-pill fw-bold">Update Loan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css" rel="stylesheet">

    <style>
        .bg-navy { background-color: #23395d !important; }
        .text-navy { color: #23395d !important; }
        .bg-teal { background-color: #008080 !important; }
        .text-teal { color: #008080 !important; }
        .bg-light-teal { background-color: #e0f7fa !important; }
        .rounded-4 { border-radius: 1rem !important; }
        .card, .card-header, .card-body { border-radius: 1rem !important; }
        .form-select.border-teal, .form-control.border-teal { border-color: #008080 !important; }
        .form-control {
            background-color: #fff !important;
            color: #23395d !important;
        }
        .form-select {
            background-color: #f8f9fa !important;
            color: #23395d !important;
        }
        .input-group-text.bg-teal { background-color: #008080 !important; color: #fff !important; }
        .btn-teal {
            background-color: #008080 !important;
            color: #fff !important;
            border: none;
            border-radius: 2rem !important;
            font-weight: 600;
        }
        .btn-teal:hover {
            background-color: #006666 !important;
            color: #fff !important;
        }
        .fw-semibold { font-weight: 600 !important; }
        .fw-bold { font-weight: 700 !important; }
        .text-end { text-align: right !important; }
        .invalid-feedback { font-size: 0.85rem; }
        label .text-danger { margin-left: 2px; }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const LoansScheme = new Choices('#loan_scheme', {
                searchEnabled: true,
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
                maxItemCount: 5
            });
            $('#loan_start').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true
            });
        });
    </script>
@endsection
