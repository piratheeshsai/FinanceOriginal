@extends('layouts.app')

@section('breadcrumb')
    Manage Profile
@endsection

@section('page-title')
    Profile Settings
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card shadow-sm border-0">
                {{-- Profile Information Section --}}
                @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-muted">
                            <i class="bi bi-person-circle me-2"></i>{{ __('Profile Information') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @livewire('profile.update-profile-information-form')
                    </div>
                @endif

                {{-- Password Update Section --}}
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-muted">
                            <i class="bi bi-shield-lock me-2"></i>{{ __('Update Password') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @livewire('profile.update-password-form')
                    </div>
                @endif

                {{-- Browser Sessions Section --}}
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-muted">
                        <i class="bi bi-device-desktop me-2"></i>{{ __('Browser Sessions') }}
                    </h5>
                </div>
                <div class="card-body">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>

                {{-- Account Deletion Section --}}
                @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
                    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-danger">
                            <i class="bi bi-trash me-2"></i>{{ __('Delete Account') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @livewire('profile.delete-user-form')
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border-radius: 0.75rem;
    }
    .card-header {
        padding: 1rem 1.5rem;
    }
    .card-body {
        padding: 1.5rem;
    }
</style>
@endpush
