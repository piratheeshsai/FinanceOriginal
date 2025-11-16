@extends('layouts.app')

@section('breadcrumb')
    Users
@endsection

@section('page-title')
    Edit User
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Edit User Information</h5>
                    <div class="d-flex align-items-center">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm me-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to Users
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="position-relative mb-3">
                                    <div class="avatar-upload border rounded-circle mx-auto" style="width: 150px; height: 150px; overflow: hidden;">
                                        <label for="profile_photo" class="d-block h-100">
                                            <img id="profilePhotoPreview"
                                                src="{{ asset('storage/profile_photos/' . optional($userDetail)->profile_photo ?: 'default.jpg') }}"
                                                alt="Profile Photo"
                                                class="w-100 h-100 object-fit-cover" style="cursor: pointer;">
                                            <div class="overlay position-absolute top-0 left-0 w-100 h-100 d-flex justify-content-center align-items-center bg-dark bg-opacity-50 opacity-0 transition-opacity">
                                                <i class="fas fa-camera text-white"></i>
                                            </div>
                                        </label>
                                    </div>
                                    <input type="file" id="profile_photo" name="profile_photo" class="d-none" accept="image/*">
                                    <div class="text-muted small mt-2">Click to change photo</div>
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Full Name</label>
                                        <input id="name" name="name" class="form-control" type="text"
                                            value="{{ old('name', $user->name ?? '') }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email Address</label>
                                        <input id="email" name="email" class="form-control" type="email"
                                            value="{{ old('email', $user->email ?? '') }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Employee ID</label>
                                <input id="employee_id" name="employee_id" class="form-control" type="text"
                                    value="{{ old('employee_id', $userDetail->employee_id ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">NIC Number</label>
                                <input id="nic_no" name="nic_no" class="form-control" type="text"
                                    value="{{ old('nic_no', $userDetail->nic_no ?? '') }}" required>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input id="phone_number" name="phone_number" class="form-control" type="tel"
                                        value="{{ old('phone_number', $userDetail->phone_number ?? '') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Address</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input id="address" name="address" class="form-control" type="text"
                                        value="{{ old('address', $userDetail->address ?? '') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-3">
                            <div class="col-md-4">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender" id="gender" required>
                                    <option value="male" {{ old('gender', $userDetail->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $userDetail->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $userDetail->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Age</label>
                                <input id="age" name="age" class="form-control" type="number" min="18"
                                    value="{{ old('age', $userDetail->age ?? '') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Branch</label>
                                <select class="form-select" name="branch_id" id="branch_id">
                                    @foreach ($branches as $branch)
                                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                                            {{ $branch->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4 pt-2 border-top">
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary px-4">Update User</button>
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

        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Initialize Choices.js if available
    if (typeof Choices !== 'undefined' && document.getElementById('branch_id')) {
        const branchSelect = document.getElementById('branch_id');
        new Choices(branchSelect, {
            searchEnabled: true,
            itemSelectText: '',
            shouldSort: false
        });
    }
</script>
@endsection
