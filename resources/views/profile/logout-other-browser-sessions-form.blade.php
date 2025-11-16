<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-muted">
                <i class="bi bi-display me-2"></i>{{ __('Browser Sessions') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-light" role="alert">
                <p class="mb-0 text-muted">
                    {{ __('Manage and log out your active sessions on other browsers and devices. If necessary, you may log out of all of your other browser sessions across all of your devices.') }}
                </p>
            </div>

            @if (count($this->sessions) > 0)
                <div class="list-group mb-4">
                    @foreach ($this->sessions as $session)
                        <div class="list-group-item list-group-item-action d-flex align-items-center">
                            <div class="me-3">
                                @if ($session->agent->isDesktop())
                                    <i class="bi bi-laptop fs-3 text-muted"></i>
                                @else
                                    <i class="bi bi-phone fs-3 text-muted"></i>
                                @endif
                            </div>

                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-1">
                                        {{ $session->agent->platform() ? $session->agent->platform() : __('Unknown') }}
                                        -
                                        {{ $session->agent->browser() ? $session->agent->browser() : __('Unknown') }}
                                    </h6>
                                    @if ($session->is_current_device)
                                        <span class="badge bg-success">{{ __('Current Device') }}</span>
                                    @endif
                                </div>
                                <small class="text-muted">
                                    {{ $session->ip_address }} •
                                    @if ($session->is_current_device)
                                        <span class="text-success">{{ __('This device') }}</span>
                                    @else
                                        {{ __('Last active') }} {{ $session->last_active }}
                                    @endif
                                </small>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    {{ __('No active sessions found.') }}
                </div>
            @endif

            <div class="d-flex align-items-center">
                <button
                    type="button"
                    class="btn btn-outline-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#logoutSessionsModal"
                >
                    <i class="bi bi-box-arrow-right me-2"></i>{{ __('Log Out Other Browser Sessions') }}
                </button>
            </div>

            <!-- Logout Sessions Modal -->
            <div class="modal fade" id="logoutSessionsModal" tabindex="-1" aria-labelledby="logoutSessionsModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="logoutSessionsModalLabel">
                                <i class="bi bi-box-arrow-right me-2"></i>{{ __('Log Out Other Browser Sessions') }}
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p class="text-muted">
                                {{ __('Please enter your password to confirm you would like to log out of your other browser sessions across all of your devices.') }}
                            </p>

                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">{{ __('Confirm Password') }}</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="confirmPassword"
                                    placeholder="{{ __('Enter your password') }}"
                                    wire:model="password"
                                >
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal"
                            >
                                {{ __('Cancel') }}
                            </button>
                            <button
                                type="button"
                                class="btn btn-danger"
                                wire:click="logoutOtherBrowserSessions"
                            >
                                {{ __('Log Out Other Sessions') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const logoutModal = document.getElementById('logoutSessionsModal');
    const passwordInput = logoutModal.querySelector('#confirmPassword');

    logoutModal.addEventListener('shown.bs.modal', function () {
        passwordInput.focus();
    });
});
</script>
@endpush
