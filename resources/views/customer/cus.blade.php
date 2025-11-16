@extends('layouts.app')


@section('breadcrumb')
    Customer
@endsection

@section('page-title')
    Create Customer
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>


    <style>
        /* Limit the visible dropdown items to 5 and enable scroll */
        .choices__list--dropdown {
            max-height: 230px;
            /* Adjust height to fit approximately 5 items */
            overflow-y: auto;
            /* Enable vertical scrolling for additional items */
            border: 1px solid #ccc;
            /* Optional: Add a border for styling */
            border-radius: 8px;
            /* Rounded corners */
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            /* Optional: Add slight shadow */
        }

        /* Add border radius to the main selection box */
        .choices__inner {
            border-radius: 8px;
            /* Adjust the value as needed */
            border: 1px solid #ccc;
            /* Optional: define a border color */
        }

        /* Add border radius to the dropdown menu */
        .choices__list--dropdown,
        .choices__list[aria-expanded] {
            border-radius: 8px;
            /* Adjust the value as needed */
            border: 1px solid #ccc;
            /* Optional: border for the dropdown */
            overflow: hidden;
            /* Ensure rounded corners */
        }

        /* Optional: Add border radius for single selected items */
        .choices__item {
            border-radius: 4px;
            /* For individual selected items */
        }



        .small-font-select {
            font-size: 8px !important;
            /* Adjust font size */
            height: 30px;
            /* Adjust dropdown height */
            line-height: 1.2;
        }

        #signUpForm .form-header {
            gap: 5px;
            text-align: center;
            font-size: .9em;
        }

        #signUpForm .form-header .stepIndicator {
            position: relative;
            flex: 1;
            padding-bottom: 30px;
        }

        #signUpForm .form-header .stepIndicator {
            position: relative;
            flex: 1;
            padding-bottom: 30px;
        }

        #signUpForm .form-header .stepIndicator.active {
            font-weight: 600;
        }

        #signUpForm .form-header .stepIndicator.finish {
            font-weight: 600;
            color: #009688;
        }

        #signUpForm .form-header .stepIndicator::before {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            z-index: 9;
            width: 20px;
            height: 20px;
            background-color: #d5efed;
            border-radius: 50%;
            border: 3px solid #ecf5f4;
        }

        #signUpForm .form-header .stepIndicator.active::before {
            background-color: #a7ede8;
            border: 3px solid #d5f9f6;
        }

        #signUpForm .form-header .stepIndicator.finish::before {
            background-color: #009688;
            border: 3px solid #b7e1dd;
        }

        #signUpForm .form-header .stepIndicator::after {
            content: "";
            position: absolute;
            left: 50%;
            bottom: 8px;
            width: 100%;
            height: 3px;
            background-color: #f3f3f3;
        }

        #signUpForm .form-header .stepIndicator.active::after {
            background-color: #a7ede8;
        }

        #signUpForm .form-header .stepIndicator.finish::after {
            background-color: #009688;
        }

        #signUpForm .form-header .stepIndicator:last-child:after {
            display: none;
        }



        #signUpForm input.invalid {
            border: 1px solid #ffaba5;
        }

        #signUpForm .step {
            display: none;
        }

        #centre_id {
            border: 2px solid #888;
            /* Darker border color */
            border-radius: 8px;
            /* Apply border radius */
            padding: 5px;
            /* Optional padding for better visual appearance */
            cursor: pointer;
            /* Pointer cursor on hover */
        }

        /* Apply custom scrollbar to the select element */
        #choices-button {
            overflow-y: auto;
            /* Ensure the scrollbar appears when the options overflow */
            max-height: 150px;
            /* Adjust the height of the select box to show the scrollbar */
        }

        /* Style the scrollbar */
        .choices__list::-webkit-scrollbar {
            width: 6px;
            /* Smaller width for the scrollbar */
        }

        .choices__list::-webkit-scrollbar-thumb {
            background-color: #888;
            /* Color of the scrollbar thumb */
            border-radius: 5px;
            /* Round corners of the scrollbar thumb */
        }

        .choices__list::-webkit-scrollbar-track {
            background: #f0f0f0;
            /* Background of the scrollbar track */
            border-radius: 5px;
            /* Round corners of the scrollbar track */
        }

        #choices-button .choices__button::after {
            content: none;
        }


        /* Optional: Styling for the Choices.js dropdown */
        .choices__item--selectable:hover {
            background-color: #f0f0f0;
            /* Light gray */
        }


        #choices-button .choices__button::after {
            content: '';
            /* Clear the default content */
            display: none !important;
            /* Ensure the default arrow is not displayed */
        }
    </style>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">



    <!-- google font -->

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <form id="signUpForm" action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf {{-- CSRF Token --}}

                    <div class="card mb-4" id="basic-info">
                        {{-- <div class="card-header d-flex justify-content-between align-items-center">
                            <h5>Customer Create </h5>
                        </div> --}}
                        <div class="form-header d-flex mt-3 mb-4">
                            <span class="stepIndicator">Personal Details</span>
                            <span class="stepIndicator">Spouse Details</span>
                            <span class="stepIndicator">Family Details</span>

                            <span class="stepIndicator">Documents</span>
                        </div>

                        <div class="card-body pt-0">

                            <!--  Personal Details Section -->
                            <div class="step">
                                <h6 class="text-primary mb-3">Personal Details</h6>
                                <div class="row">
                                    <div class="col-md-4 col-12 mb-2">
                                        <label class="form-label">Full Name</label>
                                        <div class="input-group">
                                            <input id="name" name="full_name" class="form-control" type="text"
                                                placeholder="Full Name" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12 mb-2">
                                        <label class="form-label">NIC Number</label>
                                        <div class="input-group">
                                            <input id="nic" name="nic" class="form-control" type="text"
                                                placeholder="Nic number" required>
                                        </div>
                                    </div>

                                    <div class="col-md-4 col-12 mb-2">
                                        <label class="form-label">Phone Number</label>
                                        <div class="input-group">
                                            <input id="customer_phone" name="customer_phone" class="form-control"
                                                type="number" placeholder="Phone Number" required>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <!-- Permanent Address -->
                                    <div class="col-md-3 col-12 mb-2">
                                        <label for="permanent_address" class="form-label mt-4">Permanent Address</label>
                                        <div class="input-group">
                                            <textarea id="permanent_address" name="permanent_address" class="form-control" rows="1"
                                                placeholder="Enter Permanent address" required aria-label="Permanent address"></textarea>
                                        </div>
                                    </div>

                                    <!-- Permanent City -->
                                    <div class="col-md-3 col-12 mb-2">
                                        <label for="permanent_city" class="form-label mt-4">City</label>
                                        <select class="form-select" name="permanent_city" id="permanent_city">
                                            <option value="" disabled selected>City</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->name }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Mailing Address -->
                                    <div class="col-md-3 col-12 mb-2">
                                        <label for="living_address" class="form-label mt-4">Mailing Address</label>
                                        <div class="d-flex align-items-center">
                                            <textarea id="living_address" name="living_address" class="form-control align-self-start" rows="1"
                                                style="height: 40px;" placeholder="Enter Mailing Address" required aria-label="Living address"></textarea>
                                            <button type="button" class="btn btn-outline-primary btn-sm p-2 ms-2"
                                                id="copyAddressButton" title="Copy Permanent Address">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Living City -->
                                    <div class="col-md-3 col-12 mb-2">
                                        <label for="living_city" class="form-label mt-4">City</label>
                                        <select class="form-select" name="living_city" id="living_city" required>
                                            <option value="" disabled selected>City</option>
                                            @foreach ($cities as $city)
                                                <option value="{{ $city->name }}">{{ $city->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-3 col-12 mb-2">
                                        <label class="form-label mt-4">Occupation</label>
                                        <div class="input-group">
                                            <input id="" name="occupation" class="form-control" type="text"
                                                placeholder="" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12 mb-2">
                                        <label for="date_of_birth" class="form-label mt-4">Date Of Birth</label>
                                        <div class="input-group">
                                            <input id="date_of_birth" placeholder="Date Of Birth" name="date_of_birth"
                                                class="form-control" type="text" required>
                                        </div>
                                    </div>

                                    <div class="col-md-3 col-12 mb-2">
                                        <label class="form-label mt-4">Gender</label>
                                        <select class="form-control" name="gender" id="gender" required>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                            <option value="other">Other</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3 col-12 mb-2">
                                        <label for="centre" class="form-label mt-4">Choose Centre</label>
                                        <select id="centre_id" name="center_id" class="form-control" required>
                                            <option value="" disabled selected required> Select Centre </option>
                                            @foreach ($centers as $center)
                                                <option required value="{{ $center->id }}">{{ $center->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-7 mb-3">
                                        <label class="form-label mb-2 mt-4">Civil Status:</label>
                                        <div class="d-flex flex-wrap align-items-center">
                                            <div class="form-check me-4 mb-2">
                                                <input class="form-check-input" type="radio" name="civil_status"
                                                    onchange="toggleSpouseDetails()" id="single" value="single">
                                                <label class="form-check-label" for="single">Single</label>
                                            </div>
                                            <div class="form-check me-4 mb-2">
                                                <input class="form-check-input" type="radio" name="civil_status"
                                                    id="marriedCheckbox" onchange="toggleSpouseDetails()"
                                                    value="married">
                                                <label class="form-check-label" for="marriedCheckbox">Married</label>
                                            </div>
                                            <div class="form-check me-4 mb-2">
                                                <input class="form-check-input" type="radio" name="civil_status"
                                                    onchange="toggleSpouseDetails()" id="divorced" value="divorced">
                                                <label class="form-check-label" for="divorced">Divorced</label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="civil_status"
                                                    id="widowed" value="widowed">
                                                <label class="form-check-label" for="widowed">Widowed</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5 mb-3">
                                        <label for="customer_types" class="form-label mb-2 mt-4">Select Customer
                                            Type</label>
                                        <div id="customer_types" class="d-flex align-items-center">
                                            <div class="form-check me-4">
                                                <input type="checkbox" class="form-check-input" name="customer_types[]"
                                                    value="1" id="customer">
                                                <label class="form-check-label" for="customer">Customer</label>
                                            </div>
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" name="customer_types[]"
                                                    value="2" id="guarantor">
                                                <label class="form-check-label" for="guarantor">Guarantor</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>


                            <div class="step">
                                <hr class="my-4">

                                <!-- Spouse Details Section -->
                                <h6 class="text-primary mb-3">Spouse Details</h6>
                                <div id="spouseDetails"">
                                    <div class="row">
                                        <div class="col-md-4 col-12 mb-2">
                                            <label class="form-label">Spouse Name</label>
                                            <div class="input-group">
                                                <input id="spouse_name" name="spouse_name" class="form-control"
                                                    type="text" placeholder="Alec" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12 mb-2">
                                            <label class="form-label">Spouse NIC </label>
                                            <div class="input-group">
                                                <input id="spouse_nic" name="spouse_nic" class="form-control"
                                                    type="text" placeholder="000" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12 mb-2">
                                            <label class="form-label">Phone Number</label>
                                            <div class="input-group">
                                                <input id="phone_number" name="phone_number" class="form-control"
                                                    type="text" placeholder="0711234567 or 011-1234567" maxlength="11"
                                                    required>
                                                <div class="invalid-feedback">
                                                    Please enter a valid number (e.g., 0711234567 or 011-1234567).
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4 col-12 mb-2">
                                            <label class="form-label">Spouse Occupation</label>
                                            <div class="input-group">
                                                <input id="spouse_age" name="spouse_occupation" class="form-control"
                                                    type="text" placeholder="Spouse Occupation" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12 mb-2">
                                            <label class="form-label">Spouse Age</label>
                                            <div class="input-group">
                                                <input id="spouse_age" name="spouse_age" class="form-control"
                                                    type="text" placeholder="Spouse Age" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="step">
                                <hr class="my-4">
                                <h6 class="text-primary mb-3">Family Details</h6>
                                <div id="familyDetails">
                                    <div class="row">
                                        <div class="col-md-4 col-12 mb-2">
                                            <label class="form-label">No Of Family Members</label>
                                            <div class="input-group">
                                                <input id="family_members" name="family_members" class="form-control"
                                                    type="text" placeholder="No Of Family Members" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 mb-2">
                                            <label class="form-label">Income Earners </label>
                                            <div class="input-group">
                                                <input id="income_earners" name="income_earners" class="form-control"
                                                    type="text" placeholder="Income Earners" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12 mb-2">
                                            <label class="form-label">Family Income</label>
                                            <div class="input-group">
                                                <input id="family_income" name="family_income" class="form-control"
                                                    type="text" placeholder="family income" required>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-12 mb-2">
                                            <label class="form-label">Home Phone</label>
                                            <div class="input-group">
                                                <input id="home_phone" name="home_phone" class="form-control"
                                                    type="text" placeholder="home phone " required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="step">
                                <hr class="my-4">

                                <h6 class="text-primary mb-3">Documents</h6>

                                <div class="row">
                                    <div class="col-md-4 col-12 mb-2 ">
                                        <label class="form-label">Customer Photo</label>
                                        <input type="file" name="photo" class="form-control m-0">
                                    </div>
                                    <div class="col-md-4 col-12 mb-2 ">
                                        <label class="form-label">Nic Copy</label>
                                        <input type="file" name="nic_copy" class="form-control m-0"
                                            accept=".jpg,.jpeg,.png,.pdf" required>
                                    </div>
                                </div>
                            </div>
                            <div class="button-row d-flex mt-4">
                                <button class="btn bg-gradient-dark mb-0" type="button" id="prevBtn"
                                    onclick="nextPrev(-1)">Previous</button>
                                <button class="btn bg-gradient-dark mb-0 ms-auto" type="button" id="nextBtn"
                                    onclick="nextPrev(1)">Next</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>

    <script>
        const regex = /^\d{0,15}[a-zA-Z]?$/;

        // Function to apply the regex condition
        function validateInput(event) {
            const input = event.target;
            const value = input.value;

            if (!regex.test(value)) {
                input.value = value.slice(0, -1); // Remove last invalid character
            }
        }

        // Event listeners for both inputs
        document.getElementById("spouse_nic").addEventListener("input", validateInput);
        document.getElementById("nic").addEventListener("input", validateInput);
    </script>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/js/bootstrap-datepicker.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.9.0/dist/css/bootstrap-datepicker.min.css"
        rel="stylesheet">

    <script src="{{ asset('js/customer.js') }}"></script>
@endsection
