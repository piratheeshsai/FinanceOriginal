<div class="container-fluid">
    <form wire:submit="updatePassword">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">{{ __('Update Password') }}</h5>
                <small class="text-white">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </small>
            </div>

            <div class="card-body">
                <!-- Current Password -->
                <div class="mb-3">
                    <label for="current_password" class="form-label">{{ __('Current Password') }}</label>
                    <input
                        id="current_password"
                        type="password"
                        class="form-control @error('current_password') is-invalid @enderror"
                        wire:model="state.current_password"
                        autocomplete="current-password"
                    />
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- New Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">{{ __('New Password') }}</label>
                    <input
                        id="password"
                        type="password"
                        class="form-control @error('password') is-invalid @enderror"
                        wire:model="state.password"
                        autocomplete="new-password"
                    />
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        wire:model="state.password_confirmation"
                        autocomplete="new-password"
                    />
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    @if (session('status'))
                        <div class="alert alert-success mb-0" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <button
                        type="submit"
                        class="btn btn-primary"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>{{ __('Save') }}</span>
                        <span wire:loading class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
