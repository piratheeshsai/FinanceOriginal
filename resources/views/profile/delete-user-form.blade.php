<div class="container">
    <div class="card border-danger mb-3">
        <div class="card-header bg-white border-danger d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ __('Delete Account') }}
            </h5>
        </div>
        <div class="card-body">
            <div class="alert alert-warning" role="alert">
                <p class="mb-0 text-white">
                    {{ __('Permanently delete your account.') }}
                </p>
            </div>

            <div class="text-muted mb-4">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
            </div>

            <div>
                <button
                    type="button"
                    class="btn btn-danger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteAccountModal"
                >
                    <i class="bi bi-trash me-2"></i>{{ __('Delete Account') }}
                </button>
            </div>

            <!-- Delete Account Confirmation Modal -->
            <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title" id="deleteAccountModalLabel">
                                <i class="bi bi-exclamation-triangle me-2"></i>{{ __('Confirm Account Deletion') }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                {{ __('Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.') }}
                            </p>

                            <div class="mt-4">
                                <label for="confirmPassword" class="form-label">{{ __('Confirm Password') }}</label>
                                <input
                                    type="password"
                                    class="form-control"
                                    id="confirmPassword"
                                    placeholder="{{ __('Enter your password') }}"
                                >
                                <div class="invalid-feedback">
                                    {{ __('Password is required to confirm account deletion.') }}
                                </div>
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
                            >
                                {{ __('Permanently Delete Account') }}
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
    const deleteButton = document.querySelector('.btn-danger[data-bs-target="#deleteAccountModal"]');
    const confirmDeleteButton = document.querySelector('#deleteAccountModal .modal-footer .btn-danger');
    const passwordInput = document.getElementById('confirmPassword');

    confirmDeleteButton.addEventListener('click', function() {
        if (!passwordInput.value) {
            passwordInput.classList.add('is-invalid');
            return;
        }

        // Here you would typically add your Livewire/JavaScript logic to handle account deletion
        console.log('Account deletion confirmed');
        // Example: wire:click or axios/fetch call to delete account
    });

    passwordInput.addEventListener('input', function() {
        if (this.value) {
            this.classList.remove('is-invalid');
        }
    });
});
</script>
@endpush
