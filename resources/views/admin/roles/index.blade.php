@extends('layouts.app')

@section('breadcrumb')
    Role Management
@endsection

@section('page-title')
    <div class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Roles & Permissions</h6>
    </div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
    <div class="container-fluid py-3">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">Role Permissions</h5>
                        @can('Create Role')
                            <a href="{{ route('role.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                                <i class="fa-solid fa-plus me-2"></i>
                                Create Role
                            </a>
                        @endcan
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Role Name</th>
                                        <th>Permissions</th>
                                        <th class="text-center" width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($roles->count())
                                        @foreach ($roles as $role)
                                            <tr>
                                                <td class="ps-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="role-icon bg-light rounded-circle p-2 me-3">
                                                            <i class="fas fa-user-shield text-primary"></i>
                                                        </div>
                                                        <h6 class="fw-bold mb-0">{{ $role->name }}</h6>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="permission-tags">
                                                        @foreach ($role->permissions->pluck('name')->take(3) as $permission)
                                                            <span
                                                                class="badge bg-light text-dark me-1 mb-1">{{ $permission }}</span>
                                                        @endforeach

                                                        @if ($role->permissions->count() > 3)
                                                            <span class="badge bg-secondary" data-bs-toggle="tooltip"
                                                                title="{{ implode(', ', $role->permissions->pluck('name')->slice(3)->toArray()) }}">
                                                                +{{ $role->permissions->count() - 3 }} more
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">

                                                        @can('Permission Edit')
                                                            <a href="{{ route('role.edit', $role->id) }}"
                                                                class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip"
                                                                title="Edit Role">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                        @endcan

                                                        @can('Delete Role')
                                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                                onclick="confirmDelete({{ $role->id }}, '{{ $role->name }}')"
                                                                data-bs-toggle="tooltip" title="Delete Role">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endcan
                                                        <form id="delete-form-{{ $role->id }}"
                                                            action="{{ route('role.destroy', $role->id) }}" method="POST"
                                                            class="d-none">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="3" class="text-center py-4">
                                                <div class="py-3">
                                                    <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                                                    <p class="mb-0">No roles found</p>
                                                    <a href="{{ route('role.create') }}"
                                                        class="btn btn-sm btn-primary mt-2">Create First Role</a>
                                                </div>
                                            </td>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });

            // Auto-dismiss alerts after 5 seconds
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });

        function confirmDelete(roleId, roleName) {
            Swal.fire({
                title: 'Delete Role?',
                html: `Are you sure you want to delete the role <strong>${roleName}</strong>?<br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${roleId}`).submit();
                }
            });
        }
    </script>
@endsection
