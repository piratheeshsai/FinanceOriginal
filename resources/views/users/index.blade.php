@extends('layouts.app')

@section('breadcrumb')
    Users Management
@endsection

@section('page-title')
    <div class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0">Users</h6>
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
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                        <h5 class="mb-0">Users List</h5>
                        @can('user Create')
                        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm d-flex align-items-center">
                            <i class="fa-solid fa-user-plus me-2"></i>
                            Create User
                        </a>
                        @endcan
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">User</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Joined Date</th>

                                        <th class="text-center" width="120">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($users->count())
                                        @foreach ($users as $user)
                                            <tr>
                                                <td class="ps-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-wrapper me-3">
                                                            @if (optional($user->details)->profile_photo &&
                                                                    file_exists(public_path('storage/profile_photos/' . $user->details->profile_photo)))
                                                                <img src="{{ asset('storage/profile_photos/' . $user->details->profile_photo) }}"
                                                                    alt="{{ $user->name }}" class="rounded-circle"
                                                                    width="40" height="40">
                                                            @else
                                                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-info text-white"
                                                                    style="width: 40px; height: 40px; font-size: 14px;">
                                                                    {{ strtoupper(substr($user->name, 0, 1)) }}{{ strtoupper(substr(strrchr($user->name, ' ') ?: '', 1, 1)) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="fw-bold mb-0">{{ $user->name }}</h6>
                                                            <span class="text-muted small">{{ $user->email }}</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light text-dark">
                                                        {{ $user->getRoleNames()->first() }}
                                                        <!-- Get the first role name -->
                                                    </span>
                                                </td>
                                                <td>
                                                    @if ($user->status === 'active')
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="text-muted small">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-center gap-2">

                                                        @can('user update')

                                                        <a href="{{ route('users.edit', $user->id) }}"
                                                            class="btn btn-md btn-outline-primary" data-bs-toggle="tooltip"
                                                            title="Edit User">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        @endcan

                                                        @can('User delete')
                                                        <button type="button" class="btn btn-mb btn-outline-danger"
                                                            onclick="confirmUserDelete({{ $user->id }}, '{{ $user->name }}')"
                                                            data-bs-toggle="tooltip" title="Delete User">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        @endcan


                                                        @can('User status Access')
                                                        @if ($user->status === 'active')
                                                            <!-- Deactivate Button -->
                                                            <button type="button" class="btn btn-sm btn-outline-warning"
                                                                onclick="confirmUserStatusChange({{ $user->id }}, '{{ $user->name }}', 'deactivate')"
                                                                data-bs-toggle="tooltip" title="Deactivate User">
                                                                <i class="fas fa-user-slash"></i> Deactivate
                                                            </button>

                                                            <form id="deactivate-user-form-{{ $user->id }}"
                                                                action="{{ route('users.deactivate', $user->id) }}"
                                                                method="POST" class="d-none">
                                                                @csrf
                                                            </form>
                                                        @else
                                                            <!-- Activate Button -->
                                                            <button type="button" class="btn btn-sm btn-outline-success"
                                                                onclick="confirmUserStatusChange({{ $user->id }}, '{{ $user->name }}', 'activate')"
                                                                data-bs-toggle="tooltip" title="Activate User">
                                                                <i class="fas fa-user-check"></i> Activate
                                                            </button>

                                                            <form id="activate-user-form-{{ $user->id }}"
                                                                action="{{ route('users.activate', $user->id) }}"
                                                                method="POST" class="d-none">
                                                                @csrf
                                                            </form>
                                                        @endif
                                                        @endcan



                                                        <form id="delete-user-form-{{ $user->id }}"
                                                            action="{{ route('users.destroy', $user->id) }}" method="POST"
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
                                            <td colspan="5" class="text-center py-4">
                                                <div class="py-3">
                                                    <i class="fas fa-users-slash fa-2x text-muted mb-2"></i>
                                                    <p class="mb-0">No users found</p>
                                                    <a href="{{ route('users.create') }}"
                                                        class="btn btn-sm btn-primary mt-2">Create First User</a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @if ($users->hasPages())
                        <div class="card-footer bg-white border-0">
                            <div class="d-flex justify-content-center">
                                <!-- Use Bootstrap 5 pagination view if configured; otherwise default -->
                                {{ $users->links() }}
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmUserStatusChange(userId, userName, action) {
            let actionText = action === 'deactivate' ? 'deactivate' : 'activate';
            let formId = action === 'deactivate' ? `deactivate-user-form-${userId}` : `activate-user-form-${userId}`;

            if (confirm(`Are you sure you want to ${actionText} ${userName}?`)) {
                document.getElementById(formId).submit();
            }
        }


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

        function confirmUserDelete(userId, userName) {
            Swal.fire({
                title: 'Delete User?',
                html: `Are you sure you want to delete <strong>${userName}</strong>?<br>This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-user-form-${userId}`).submit();
                }
            });
        }
    </script>
@endsection
