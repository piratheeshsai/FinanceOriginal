@extends('layouts.app')

@section('breadcrumb')
    Users
@endsection

@section('page-title')
    Create New User
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">User Information</h5>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to Users
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                               <div class="position-relative mb-3">
                                    <div class="avatar-upload border rounded-circle mx-auto d-flex justify-content-center align-items-center bg-light" style="width: 150px; height: 150px; overflow: hidden;">
                                        <label for="profile_photo" class="d-block h-100 w-100 position-relative">
                                            <!-- Preview Image (hidden by default) -->
                                            <img id="profilePhotoPreview"
                                                 class="h-100 w-100 object-fit-cover d-none"
                                                 alt="Profile preview"
                                                 style="display: none;">

                                            <!-- Default Icon -->
                                            <div id="defaultIcon" class="h-100 w-100 d-flex justify-content-center align-items-center">
                                                <i class="fas fa-user fa-4x text-secondary"></i>
                                            </div>

                                            <!-- Camera Overlay -->
                                            <div class="overlay position-absolute top-0 left-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50 opacity-0 transition-opacity">
                                                <i class="fas fa-camera text-white"></i>
                                            </div>
                                        </label>
                                    </div>
                                    <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*">
                                    <div class="text-muted small mt-2">Click to upload photo</div>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name</label>
                                        <input id="name" name="name" class="form-control" type="text" placeholder="Enter full name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email Address</label>
                                        <input id="email" name="email" class="form-control" type="email" placeholder="email@example.com" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Employee ID</label>
                                <input id="employee_id" name="employee_id" class="form-control" type="text" placeholder="EMP-0001" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIC Number</label>
                                <input id="nic_no" name="nic_no" class="form-control" type="text" placeholder="000000000V" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input id="phone_number" name="phone_number" class="form-control" type="tel" placeholder="0771234567">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input id="address" name="address" class="form-control" type="text" placeholder="Enter address">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender" id="gender" required>
                                    <option value="" selected disabled>Select gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Age</label>
                                <input id="age" name="age" class="form-control" type="number" min="18" placeholder="21+" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Branch</label>
                                <select class="form-select" name="branch_id" id="branch_id">
                                    <option value="" selected disabled>Select branch</option>
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-2 border-top">
                            <button type="reset" class="btn btn-outline-secondary me-2">Cancel</button>
                            <button type="submit" class="btn btn-primary px-4">Create User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-upload:hover .overlay {
        opacity: 1;
    }
    .transition-opacity {
        transition: opacity 0.3s ease;
    }
    .object-fit-cover {
        object-fit: cover;
    }
</style>

<script>
    // JavaScript to preview selected image
    document.getElementById('profile_photo').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('profilePhotoPreview');
    const defaultIcon = document.getElementById('defaultIcon');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Show preview image
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            preview.style.display = 'block';

            // Hide default icon
            defaultIcon.style.display = 'none';
        }
        reader.readAsDataURL(file);
    } else {
        // Reset to default state
        preview.src = '';
        preview.classList.add('d-none');
        defaultIcon.style.display = 'flex';
    }
});


    // Form validation enhancement
    document.querySelector('form').addEventListener('submit', function(event) {
        // Basic form validation could be added here
        // This is just a placeholder for additional validation logic
    });
</script>
@endsection
