@extends('layouts.app')

@section('breadcrumb')
    Role Management
@endsection

@section('page-title')
    <div class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Role Management</h6>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid py-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">User Roles</h5>

                    @can('Role assign to user')
                    <button class="btn btn-primary btn-sm d-flex align-items-center" id="assignRoleBtn">
                        <i class="fa-solid fa-user-plus me-2"></i>
                        Assign Role
                    </button>
                    @endcan

                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Role</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($users->count())
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center px-2">
                                                <div>
                                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                                    <span class="text-muted small">{{ $user->email }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if (count($user->roles->pluck('name')->toArray()) > 0)
                                                <span class="badge bg-success text-white">{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</span>
                                            @else
                                                <span class="badge bg-warning text-dark">No Role Assigned</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('Edit user Role')
                                                <button class="btn btn-sm btn-outline-primary editRoleBtn" data-user-id="{{ $user->id }}" title="Edit Role">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </button>
                                                @endcan

                                                @can('Edit user Role')
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $user->id }})" title="Remove Role">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="text-center py-4">No users found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div class="modal fade" id="editRoleModal" tabindex="-1" aria-labelledby="editRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="editRoleModalLabel">Edit User Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="editRoleFormContainer">
                <!-- Form will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Assign Role Modal -->
<div class="modal fade" id="assignRoleModal" tabindex="-1" aria-labelledby="assignRoleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="assignRoleModalLabel">Assign Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="assignRoleFormContainer">
                <!-- Form will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show sweet alert on success
    @if (session('message'))
        Swal.fire({
            title: 'Success!',
            text: "{{ session('message') }}",
            icon: 'success',
            confirmButtonText: 'OK'
        });
    @endif

    // Assign role button click
    document.getElementById('assignRoleBtn').addEventListener('click', function() {
        fetch('/assign/create')
            .then(response => response.text())
            .then(html => {
                document.getElementById('assignRoleFormContainer').innerHTML = html;
                new bootstrap.Modal(document.getElementById('assignRoleModal')).show();
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to load the form', 'error');
            });
    });

    // Edit role buttons
    document.querySelectorAll('.editRoleBtn').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            fetch(`/assign/${userId}/edit`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('editRoleFormContainer').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('editRoleModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to load the form', 'error');
                });
        });
    });
});

function deleteUser(userId) {
    Swal.fire({
        title: 'Remove Role?',
        text: "This action cannot be undone",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, remove',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/assign/${userId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                Swal.fire('Success', 'Role has been removed', 'success')
                    .then(() => {
                        window.location.reload();
                    });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to remove role', 'error');
            });
        }
    });
}
</script>
@endsection
