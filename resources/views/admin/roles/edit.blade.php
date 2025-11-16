@extends('layouts.app')


@section('breadcrumb')
Roles
@endsection

@section('page-title')
<h6 class="mb-0">  Edit</h6>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid py-3">
    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <span>{{ session('message') }}</span>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0 mb-4">
                <form action="{{ route('role.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-header bg-white p-4">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="roleName" class="form-label fw-bold">Role Name</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        <input
                                            type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="roleName"
                                            name="name"
                                            value="{{ old('name', $role->name) }}"
                                            required
                                        >
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <small class="text-muted">Role names should be unique and descriptive</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <h5 class="mb-4 pb-2 border-bottom">Edit Permissions</h5>

                        @if ($permissionGroups->count())
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label fw-bold" for="selectAll">
                                            Select/Deselect All
                                        </label>
                                    </div>
                                </div>

                                @foreach ($permissionGroups as $permissionGroup)
                                <div class="col-md-6 mb-4">
                                    <div class="card border">
                                        <div class="card-header bg-light d-flex justify-content-between align-items-center py-2">
                                            <h6 class="mb-0">{{ $permissionGroup->name }}</h6>
                                            <div class="form-check">
                                                <input
                                                    class="form-check-input group-select"
                                                    type="checkbox"
                                                    id="group-{{ $permissionGroup->id }}"
                                                    data-group="{{ $permissionGroup->id }}"
                                                >
                                                <label class="form-check-label small" for="group-{{ $permissionGroup->id }}">
                                                    Select all
                                                </label>
                                            </div>
                                        </div>

                                        <div class="card-body py-2">
                                            @if ($permissionGroup->permissions->count())
                                                <div class="row">
                                                    @foreach ($permissionGroup->permissions as $permission)
                                                    <div class="col-md-6">
                                                        <div class="form-check mb-2">
                                                            <input
                                                                type="checkbox"
                                                                name="permission_ids[]"
                                                                value="{{ $permission->id }}"
                                                                id="permission-{{ $permission->id }}"
                                                                class="form-check-input permission-checkbox"
                                                                data-group="{{ $permissionGroup->id }}"
                                                                @if (in_array($permission->id, $role->permissions->pluck('id')->toArray())) checked @endif
                                                            >
                                                            <label
                                                                class="form-check-label"
                                                                for="permission-{{ $permission->id }}"
                                                            >
                                                                {{ $permission->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <p class="text-muted mb-0">No permissions found in this group</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-info">
                                No permission groups found. Please create permissions first.
                            </div>
                        @endif

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="{{ route('role.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i> Back to Roles
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Role
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if all permissions are selected on page load
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
    const selectAllCheckbox = document.getElementById('selectAll');

    // Initialize group selects
    document.querySelectorAll('.group-select').forEach(groupSelect => {
        const groupId = groupSelect.getAttribute('data-group');
        const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupId}"]`);
        const allChecked = Array.from(groupCheckboxes).every(box => box.checked);
        groupSelect.checked = allChecked;
    });

    // Initialize select all checkbox
    if (selectAllCheckbox) {
        selectAllCheckbox.checked = Array.from(permissionCheckboxes).every(box => box.checked);
    }

    // Select all checkbox functionality
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            // Also update group selectors
            document.querySelectorAll('.group-select').forEach(groupSelect => {
                groupSelect.checked = selectAllCheckbox.checked;
            });
        });
    }

    // Group select functionality
    document.querySelectorAll('.group-select').forEach(groupSelect => {
        groupSelect.addEventListener('change', function() {
            const groupId = this.getAttribute('data-group');
            const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupId}"]`);

            groupCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });

            // Check if all groups are selected, then update the "Select All" checkbox
            updateSelectAllCheckbox();
        });
    });

    // Individual permission checkboxes
    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const groupId = this.getAttribute('data-group');
            const groupCheckboxes = document.querySelectorAll(`.permission-checkbox[data-group="${groupId}"]`);
            const groupSelect = document.querySelector(`.group-select[data-group="${groupId}"]`);

            // Check if all permissions in this group are selected
            const allChecked = Array.from(groupCheckboxes).every(box => box.checked);
            groupSelect.checked = allChecked;

            // Update the "Select All" checkbox
            updateSelectAllCheckbox();
        });
    });

    function updateSelectAllCheckbox() {
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = Array.from(permissionCheckboxes).every(box => box.checked);
        }
    }

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        const roleName = document.getElementById('roleName').value;
        const selectedPermissions = document.querySelectorAll('input[name="permission_ids[]"]:checked');

        if (!roleName.trim()) {
            event.preventDefault();
            Swal.fire({
                title: 'Error',
                text: 'Please enter a role name',
                icon: 'error',
            });
            return;
        }

        if (selectedPermissions.length === 0) {
            event.preventDefault();
            Swal.fire({
                title: 'Warning',
                text: 'No permissions selected. Are you sure you want to update this role without any permissions?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update anyway',
                cancelButtonText: 'No, I\'ll select permissions'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    });
});
</script>
@endsection
