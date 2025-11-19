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
        <div class="row justify-content-center">
            <div class="col-lg-10 col-md-10 col-12">
                <div class="card border-0 shadow-lg rounded-4 mt-4">
                    <div class="card-header bg-navy text-white py-3 rounded-top-4">
                        <h5 class="mb-0 fw-bold text-white"> Create New Loan</h5>
                    </div>
                    <div class="card-body bg-light-teal rounded-bottom-4">
                        <form id="createLoanForm" method="POST" action="{{ route('loan.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                     <label>Loan Type</label>
                                    <select id="loan_type" class="form-control" name="loan_type">
                                        <option value="">Select a loan type</option>
                                        <option value="individual">Individual</option>
                                        <option value="group">Group</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="group_id">Group</label>
                                    <select id="group_id" name="group_id" class="form-select border-teal" disabled>
                                        <option value="">Select Group</option>
                                        @foreach ($groups as $group)
                                            <option value="{{ $group->id }}">{{ $group->group_code }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="loan_scheme">Loan Scheme <span class="text-danger">*</span></label>
                                    <select id="loan_scheme" name="scheme_id" class="form-select border-teal">
                                        <option value="standard">Select Loan Scheme</option>
                                        @foreach ($loan_schemes as $loan_scheme)
                                            <option
                                                value="{{ $loan_scheme->id }}"
                                                data-doc-charge="{{ $loan_scheme->document_charge_percentage ?? 0 }}">
                                                {{ $loan_scheme->loan_name }}
                                            </option>
                                        @endforeach
                                    </select>
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
                                <div class="col-md-6">
                                    <label for="loan_amount" class="form-label fw-bold text-navy mb-1">Loan Amount <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-teal" id="loan_amount" name="loan_amount"
                                            placeholder="Enter amount in LKR" required>
                                        <span class="input-group-text bg-teal text-white">LKR</span>
                                    </div>
                                    <div id="loanAmountFeedback" class="invalid-feedback">
                                        Please enter a valid loan amount.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="document_charge" class="form-label fw-bold text-navy mb-1">Document Charge <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border-teal" id="document_charge" name="document_charge"
                                            placeholder="Enter amount in LKR" required readonly>
                                        <span class="input-group-text bg-teal text-white">LKR</span>
                                    </div>
                                    <div id="loanAmountFeedback" class="invalid-feedback">
                                        Please enter a valid amount.
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="center_id">Center <span class="text-danger">*</span></label>
                                    <select id="center_id" name="center_id" class="form-select border-teal">
                                        <option value="" disabled>Select Center</option>
                                        @foreach ($centers as $center)
                                            <option value="{{ $center->id }}">{{ $center->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="loan_start">Start Date <span class="text-danger">*</span></label>
                                    <input type="text" id="loan_start" name="loan_start" class="form-control border-teal"
                                         placeholder="Loan Start Date" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="text-navy fw-semibold mb-1" for="guarantor">Guarantors <span class="text-danger">*</span></label>
                                    <select id="loan_guarantor" name="loan_guarantor[]" multiple class="form-select border-teal" required>
                                        <option value="" disabled>Select Guarantors</option>
                                    </select>
                                    <div class="invalid-feedback">Please select at least one guarantor.</div>
                                </div>
                                <div class="col-12 text-end mt-3">
                                    <button type="submit" class="btn btn-teal px-4 py-2 rounded-pill fw-bold">Create Loan</button>
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
        .form-select, .form-control { font-size: 0.95rem !important; }
        .form-select.border-teal, .form-control.border-teal { border-color: #008080 !important; }
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
        .form-control {
            background-color: #fff !important;
            color: #23395d !important;
            border-color: #008080 !important;
        }
        .form-select {
            background-color: #f8f9fa !important; /* light gray */
            color: #23395d !important;
            border-color: #008080 !important;
        }
    </style>

    <script>
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

document.querySelector('#loan_amount').addEventListener('input', function(event) {
    const input = event.target;
    const value = input.value.replace(/[^0-9.]/g, '');
    input.value = value;
    if (!value || isNaN(value) || Number(value) <= 0) {
        input.classList.add('is-invalid');
    } else {
        input.classList.remove('is-invalid');
    }
});

$(document).ready(function() {
    $('#loan_start').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });
});

// Document charge calculation
document.addEventListener('DOMContentLoaded', function() {
    function calculateDocumentCharge() {
        const schemeSelect = document.getElementById('loan_scheme');
        const amountInput = document.getElementById('loan_amount');
        const docChargeInput = document.getElementById('document_charge');

        const selectedOption = schemeSelect.options[schemeSelect.selectedIndex];
        const docChargePercent = parseFloat(selectedOption.getAttribute('data-doc-charge')) || 0;
        const amount = parseFloat(amountInput.value) || 0;

        if (docChargePercent > 0 && amount > 0) {
            const charge = ((docChargePercent / 100) * amount).toFixed(2);
            docChargeInput.value = charge;
        } else {
            docChargeInput.value = '';
        }
    }

    document.getElementById('loan_scheme').addEventListener('change', calculateDocumentCharge);
    document.getElementById('loan_scheme').addEventListener('input', calculateDocumentCharge);
    document.getElementById('loan_amount').addEventListener('input', calculateDocumentCharge);
    document.getElementById('loan_amount').addEventListener('change', calculateDocumentCharge);
});

// Main loan type and customer/guarantor logic
document.addEventListener('DOMContentLoaded', function () {
    let currentGroupMembers = [];

    // Initialize Choices.js for dropdowns
    const loanTypeField = new Choices('#loan_type', { searchEnabled: false });
    const LoansScheme = new Choices('#loan_scheme', { searchEnabled: true });
    const center = new Choices('#center_id', {
        searchEnabled: true,
        removeItemButton: true,
        placeholder: true,
        placeholderValue: 'Select Center'
    });

    let groupField = new Choices('#group_id', { shouldSort: false, searchEnabled: true });
    let customerField = initializeCustomerField();
    // DO NOT initialize guarantorField here - it will be initialized dynamically

    const customerFieldContainer = document.getElementById('customerFieldContainer');

    // Handle loan type change
    loanTypeField.passedElement.element.addEventListener('change', function () {
        const loanType = loanTypeField.getValue(true);

        if (loanType === 'individual') {
            switchToIndividualLoanType();
        } else if (loanType === 'group') {
            switchToGroupLoanType();
        } else {
            resetFields();
        }
    });

    // Handle group selection change
    groupField.passedElement.element.addEventListener('change', function () {
        const groupId = groupField.getValue(true);
        if (groupId) {
            fetchCustomersForGroup(groupId);
        } else {
            reinitializeCustomerField([]);
            reinitializeGuarantorField([], 'No group members available');
        }
    });

    // Handle customer selection change
    document.getElementById('customer_id').addEventListener('change', function () {
        const loanType = loanTypeField.getValue(true);
        const selectedCustomerId = this.value;

        if (loanType === 'group' && currentGroupMembers.length > 0) {
            const filteredGuarantors = currentGroupMembers.filter(member => String(member.id) !== String(selectedCustomerId));
            reinitializeGuarantorField(filteredGuarantors, 'No group members available');
        }
    });

    function switchToIndividualLoanType() {
        groupField.disable();
        clearSelectedCustomer();
        customerFieldContainer.style.display = 'block';
        fetchCustomersForIndividual();
        reinitializeGuarantorField([], 'Select group loan type');
    }

    function switchToGroupLoanType() {
        groupField.enable();
        clearSelectedCustomer();
        customerFieldContainer.style.display = 'block';
        reinitializeGuarantorField([], 'Select a group first');
    }

    function resetFields() {
        groupField.disable();
        clearSelectedCustomer();
        reinitializeCustomerField([]);
        reinitializeGuarantorField([], 'Select loan type first');
        customerFieldContainer.style.display = 'none';
    }

    async function fetchCustomersForIndividual() {
        try {
            const response = await fetch('/api/customers?group_id=null');
            const data = await response.json();
            currentGroupMembers = [];
            reinitializeCustomerField(data, 'No customers available');
        } catch (error) {
            console.error('Error fetching individual customers:', error);
        }
    }

    async function fetchCustomersForGroup(groupId) {
        try {
            const response = await fetch(`/api/customers?group_id=${groupId}`);
            const data = await response.json();
            currentGroupMembers = data;
            reinitializeCustomerField(data, 'No customers found');
            reinitializeGuarantorField(data, 'No group members available');
        } catch (error) {
            console.error('Error fetching group customers:', error);
        }
    }

    function reinitializeCustomerField(data, emptyMessage = 'No customers available') {
        if (customerField) {
            customerField.destroy();
        }

        const selectElement = document.querySelector('#customer_id');
        selectElement.innerHTML = '';

        if (data && data.length > 0) {
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                const displayParts = [];
                if (item.full_name) displayParts.push(item.full_name);
                if (item.nic) displayParts.push(`(${item.nic})`);

                option.textContent = displayParts.join(' ') || item.group_code || `Item ${item.id}`;
                selectElement.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = emptyMessage;
            option.disabled = true;
            selectElement.appendChild(option);
        }

        customerField = initializeCustomerField();
    }

    function reinitializeGuarantorField(data, emptyMessage = 'No group members available') {
        if (window.guarantorField) {
            window.guarantorField.destroy();
            window.guarantorField = null;
        }

        const selectElement = document.querySelector('#loan_guarantor');
        selectElement.innerHTML = '';

        if (data && data.length > 0) {
            data.forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.full_name;
                selectElement.appendChild(option);
            });
        } else {
            const option = document.createElement('option');
            option.value = '';
            option.textContent = emptyMessage;
            option.disabled = true;
            selectElement.appendChild(option);
        }

        window.guarantorField = new Choices('#loan_guarantor', {
            shouldSort: false,
            removeItemButton: true,
            placeholder: true,
            placeholderValue: 'Select Guarantors',
            maxItemCount: 5,
            searchEnabled: true
        });
    }

    function initializeCustomerField() {
        return new Choices('#customer_id', {
            shouldSort: false,
            removeItemButton: true,
            placeholder: true,
            placeholderValue: 'Select Customer'
        });
    }

    function clearSelectedCustomer() {
        if (customerField) {
            customerField.clearStore();
            customerField.clearInput();
            customerField.removeActiveItems();
        }
    }
});
</script>
@endsection
