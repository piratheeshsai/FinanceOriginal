@extends('layouts.app')

@section('breadcrumb')
    Branches
@endsection

@section('page-title')
    Manage Branches
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection


@section('content')
    <style>
        /* Align Center Number column to the right */
        .table td:nth-child(2),
        .table th:nth-child(2) {
            text-align: center;
        }

        .btn-link i {
            font-size: 16px;
            /* Adjust icon size */
            padding: 0;
            /* Remove any extra padding around icons */
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;

        }
    </style>
    <div class="container-fluid py-4">

        {{-- <div class="row">
            <!-- Branches Table -->
            <div class="col-md-7 mt-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0 px-2">
                        <h6 class="mb-0">Branches Information</h6>
                        @can('branch Create')
                        <a class="btn bg-gradient-dark mb-0 w-100 w-sm-auto" href="javascript:;" data-bs-toggle="modal"
                            data-bs-target="#createBranchModal" style="max-width: 170px;">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Add New Branch
                        </a>
                        @endcan
                    </div>
                    <div class="card-body pt-4 p-3">
                        <ul class="list-group" id="branchesList">
                            @foreach ($branches as $branch)
                                <li id="branch-{{ $branch->id }}"
                                    class="list-group-item border-0 d-flex p-4 mb-2 bg-gray-100 border-radius-lg">
                                    <!-- Branch Details Section -->
                                    <div class="d-flex flex-column flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-3 text-sm">{{ $branch->name }}</h6>
                                            <!-- Buttons Section aligned to the right -->
                                            <div class="ms-auto d-flex gap-2">
                                                @can('branch Delete')
                                                    <a href="javascript:;" onclick="deleteBranch({{ $branch->id }})"
                                                        class="btn btn-link text-danger text-gradient px-3 mb-0">
                                                        <i class="far fa-trash-alt me-2"></i>Delete
                                                    </a>
                                                @endcan
                                                @can('branch Update')
                                                    <a href="javascript:;" onclick="editBranch({{ $branch->id }})"
                                                        class="btn btn-link text-dark px-3 mb-0">
                                                        <i class="fas fa-pencil-alt text-dark me-2" aria-hidden="true"></i>Edit
                                                    </a>
                                                @endcan

                                                <button class="btn btn-link text-primary px-3 mb-0"
                                                    onclick="fetchCenters({{ $branch->id }}, '{{ $branch->name }}')">
                                                    <i class="fas fa-eye text-primary me-2"></i>Centers
                                                </button>
                                                <!-- Placeholder for spacing when no buttons are visible -->
                                                @unless(Gate::check('branch Delete') || Gate::check('branch Update'))
                                                    <span class="px-3 mb-0"></span>
                                                @endunless
                                            </div>
                                        </div>


                                        </span>
                                        <span class="mb-2 text-xs">Email Address:
                                            <span class="text-dark font-weight-bold ms-sm-2">{{ $branch->email }}</span>
                                        </span>
                                        <span class="mb-2 text-xs">Phone Number:
                                            <span class="text-dark ms-sm-2 font-weight-bold">{{ $branch->phone }}</span>
                                        </span>
                                        <span class="text-xs mb-2">Created by:
                                            <span class="text-dark ms-sm-2 font-weight-bold">{{ $branch->creator->name ?? 'Unknown' }}</span>
                                        </span>
                                        <span class="text-xs">Created Date:
                                            <span class="text-dark ms-sm-2 font-weight-bold">{{ $branch->created_at }}</span>
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Centers Table -->
            <div class="col-md-5 mt-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between pb-0 px-2">
                        <h6 class="mb-0" id="centersHeader">Centers Information</h6>

                        <button class="btn bg-gradient-primary mb-0" data-bs-toggle="modal" data-bs-target="#createCenterModal"
                            id="addCenterButton" data-branch-id="" style="display: none;">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Add New Center
                        </button>

                    </div>
                    <div class="card-body pt-4 p-3">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th scope="col"
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name
                                        </th>
                                        <th scope="col"
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Center Number</th>
                                        <th scope="col"
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="centersTableBody">
                                    <tr>
                                        <td colspan="3" class="text-center text-secondary">Select a branch to view its
                                            centers.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div> --}}

        @livewire('branch.branch')
    </div>



{{--
    <!-- Edit Center Modal -->

    <div class="modal fade" id="editCenterModal" tabindex="-1" aria-labelledby="editCenterModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editCenterForm">
                    @csrf <!-- CSRF token for Laravel -->
                    @method('PUT') <!-- Method spoofing for PUT -->

                    <div class="modal-header">
                        <h5 class="modal-title" id="editCenterModalLabel">Edit Center</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Center Name -->
                        <div class="mb-3">
                            <label for="centerName" class="form-label">Center Name</label>
                            <input type="text" class="form-control" id="centerName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>







    <!-- Modal Structure -->
   





    <div class="modal fade" id="editBranchModal" tabindex="-1" aria-labelledby="editBranchModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editBranchForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editBranchModalLabel">Edit Branch</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editBranchId" name="id">
                        <div class="mb-3">
                            <label for="editBranchName" class="form-label">Branch Name</label>
                            <input type="text" class="form-control" id="editBranchName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editBranchEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editBranchEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="editBranchPhone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="editBranchPhone" name="phone" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade mt-6" id="createCenterModal" tabindex="-1" aria-labelledby="createCenterModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCenterModalLabel">Create New Center</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="createCenterForm">
                        @csrf


                        <div class="mb-3">
                            <label for="name" class="form-label">Center Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>



                        <!-- Branch Selection Dropdown -->
                        <div class="mb-3">
                            <label for="branch_id" class="form-label">Select Branch</label>
                            <select class="form-select" id="branch_id" name="branch_id" required>
                                <option value="">Select a Branch</option>
                                @foreach ($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn bg-gradient-dark">Submit</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Function to load the center data into the modal for editing
        function editCenter(centerId) {
            // Send a GET request to fetch the center details by ID
            $.ajax({
                url: `/centers/${centerId}/edit`, // Fetch data for the given center ID
                method: 'GET',
                success: function(data) {
                    // Populate the modal fields with current data (center name only)
                    $('#centerName').val(data.name); // Set the center name in the modal input

                    // Set the action URL for the form submission (use the center ID in the URL)
                    $('#editCenterForm').attr('action', `/centers/${data.id}`);

                    // Show the modal
                    $('#editCenterModal').modal('show');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('Failed to load center details.');
                }
            });
        }

        // Handle form submission via AJAX
        $('#editCenterForm').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            // Get the CSRF token from the meta tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Serialize the form data (only name field will be serialized)
            const formData = $(this).serialize();

            // Send an AJAX PUT request to update the center details
            $.ajax({
                url: $(this).attr('action'), // Get the action URL from the form (center ID)
                method: 'PUT',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken // Include the CSRF token in the request headers
                },
                success: function(response) {
                    // Hide the modal
                    $('#editCenterModal').modal('hide');
                    // Show a success message
                    alert('Center updated successfully!');
                    // Optionally, update the UI with the updated center data (e.g., update center name in the list)
                    $('#center-' + response.id).find('.center-name').text(response
                        .name); // Example: update the center name in the list
                },
                error: function(xhr) {
                    // Handle errors
                    console.log(xhr.responseText);
                    alert('Failed to update center.');
                }
            });
        });








        function selectBranch(branchId) {
            $('#addCenterButton').data('branch-id', branchId); // Set branch ID
            $('#addCenterButton').show(); // Show button
            $('#centersTableBody').html(
                '<tr><td colspan="3" class="text-center text-secondary">Loading centers...</td></tr>');
            loadCenters(branchId); // Fetch centers dynamically
        }

        $('#addCenterButton').click(function() {
            const branchId = $(this).data('branch-id');
            $('#branchId').val(branchId); // Populate hidden input field
            console.log('Branch ID:', branchId); // Debugging
        });

        $('#createCenterForm').submit(function(e) {
            e.preventDefault();
            const formData = $(this).serialize();

            $.ajax({
                url: '/centers',
                method: 'POST',
                data: formData,
                success: function(center) {
                    $('#createCenterModal').modal('hide');
                    alert('Center created successfully!');
                    $('#centersTableBody').append(`
                <tr id="center-${center.id}">
                    <td>${center.name}</td>
                    <td>${center.center_code}</td>
                    <td>
                        <a href="javascript:;" onclick="editCenter(${center.id})" class="btn btn-link text-dark px-3">
                            <i class="fas fa-pencil-alt text-dark me-2"></i>
                        </a>
                        <a href="javascript:;" onclick="deleteCenter(${center.id})" class="btn btn-link text-danger text-gradient px-3">
                            <i class="far fa-trash-alt me-2"></i>
                        </a>
                    </td>
                </tr>
            `);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to create center.');
                }
            });
        });


        $('#createBranchForm').submit(function(e) {
            e.preventDefault(); // Prevent the default form submission

            var formData = $(this).serialize(); // Serialize the form data

            $.ajax({
                url: '/branches', // Adjust the URL to the correct route for creating branches
                method: 'POST',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                        'content') // Add CSRF token to headers

                },
                success: function(response) {
                    alert('Branch added successfully!');
                    $('#createBranchModal').modal('hide'); // Hide the modal after success
                    $('#branchesList').append(`
                <li class="list-group-item">${response.name}</li>
            `);
                },
                error: function(error) {
                    alert('An error occurred while adding the branch.');
                }
            });
        });



        function deleteBranch(branchId) {
            // SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the AJAX delete request if confirmed
                    $.ajax({
                        url: '/branches/' + branchId, // URL for the delete route
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // Add CSRF token
                        },
                        success: function(response) {
                            // Remove the branch row from the list after successful deletion
                            $('#branch-' + branchId).remove();
                            Swal.fire(
                                'Deleted!',
                                'The branch has been deleted.',
                                'success'
                            );
                        },
                        error: function(xhr, status, error) {
                            // Show an error alert if something goes wrong
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the branch.',
                                'error'
                            );
                        }
                    });
                }
            });
        }


        // edit branches


        function editBranch(branchId) {
            $.ajax({
                url: `/branches/${branchId}`,
                method: 'GET',
                success: function(response) {
                    // Populate the form with the branch data
                    $('#editBranchId').val(response.id);
                    $('#editBranchName').val(response.name);
                    $('#editBranchEmail').val(response.email);
                    $('#editBranchPhone').val(response.phone);

                    // Show the modal
                    $('#editBranchModal').modal('show');
                },
                error: function(xhr) {
                    alert('Failed to fetch branch details.');
                    console.error(xhr.responseText);
                }
            });
        }



        // update code

        $('#editBranchForm').submit(function(e) {
            e.preventDefault();

            var branchId = $('#editBranchId').val();
            var formData = $(this).serialize();

            $.ajax({
                url: `/branches/${branchId}`,
                method: 'PUT',
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    alert('Branch updated successfully!');
                    $('#editBranchModal').modal('hide');

                    // Update the branch details in the list
                    $(`#branch-${response.id} h6`).text(response.name);
                    $(`#branch-${response.id} span:contains('Email Address')`).next().text(response
                        .email);
                    $(`#branch-${response.id} span:contains('Phone Number')`).next().text(response
                        .phone);
                },
                error: function(xhr) {
                    alert('Failed to update branch.');
                    console.error(xhr.responseText);
                }
            });
        });

        // Assuming you have a modal with id="myModal"
        $('.list-group-item').on('click', function(event) {
            event.preventDefault();
            event.stopPropagation();

            const branchId = $(this).data('id');
            const branchName = $(this).find('h6').text();

            // Open the modal if not already open
            var myModal = new bootstrap.Modal(document.getElementById('myModal'));
            myModal.show();

            // Fetch centers data
            fetchCenters(branchId, branchName);
        });

        /// show center table

        function fetchCenters(branchId, branchName) {
            $.ajax({
                url: `/branches/${branchId}/centers`, // Make sure this endpoint is correct
                method: 'GET',
                success: function(centers) {
                    $('#centersHeader').text(`Centers for ${branchName}`);
                    $('#centersTableBody').empty(); // Clear the table body before adding new rows
                    $('#addCenterButton').show(); // Show the "Add New Center" button

                    // Check if there are centers to display
                    if (centers.length > 0) {
                        centers.forEach(center => {
                            $('#centersTableBody').append(`
                        <tr id="center-${center.id}">
                            <td>${center.name}</td>
                            <td>${center.center_code}</td>
                            <td>
                                <button class="btn btn-link text-dark" onclick="editCenter(${center.id})">
                                    <i class="fas fa-pencil-alt text-dark"></i>
                                </button>
                                <button class="btn btn-link text-danger px-1" onclick="deleteCenter(${center.id})">
                                    <i class="far fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    `);
                        });
                    } else {
                        $('#centersTableBody').append(`
                    <tr>
                        <td colspan="3" class="text-center text-secondary">No centers available for this branch.</td>
                    </tr>
                `);
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert('Failed to fetch centers. Try again.');
                }
            });
        }





        function deleteCenter(centerId) {
            // SweetAlert2 confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Proceed with the AJAX delete request if confirmed
                    $.ajax({
                        url: `/centers/${centerId}`,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content') // CSRF Token
                        },
                        success: function(response) {
                            // Remove the center row from the table after successful deletion
                            $(`#center-${centerId}`).remove();
                            Swal.fire(
                                'Deleted!',
                                'The center has been deleted.',
                                'success'
                            );
                        },
                        error: function(xhr) {
                            // Handle errors (e.g., if center not found)
                            Swal.fire(
                                'Error!',
                                'There was an error deleting the center.',
                                'error'
                            );
                        }
                    });
                }
            });
        }
    </script>
     --}}
@endsection
