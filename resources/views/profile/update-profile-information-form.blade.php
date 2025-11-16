<div class="container-fluid">
    <form wire:submit="updateProfileInformation">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0 text-white">{{ __('Profile Information') }}</h5>
                <small class="text-white">{{ __('Update your account\'s profile information and email address.') }}</small>
            </div>

            <div class="card-body">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div x-data="{photoName: null, photoPreview: null}" class="mb-4">
                        <!-- Profile Photo File Input -->
                        <input type="file" id="photo" class="form-control d-none"
                                wire:model.live="photo"
                                x-ref="photo"
                                x-on:change="
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                " />

                        <label for="photo" class="form-label">{{ __('Profile Photo') }}</label>

                        <div class="d-flex align-items-center">
                            <!-- Current Profile Photo -->
                            <div class="me-3" x-show="! photoPreview">
                                <img src="{{ $this->user->profile_photo_url }}"
                                     alt="{{ $this->user->name }}"
                                     class="rounded-circle"
                                     style="width: 120px; height: 120px; object-fit: cover;">
                            </div>

                            <!-- New Profile Photo Preview -->
                            <div class="me-3" x-show="photoPreview" style="display: none;">
                                <div class="rounded-circle"
                                     style="width: 120px; height: 120px; background-size: cover; background-position: center;"
                                     x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                </div>
                            </div>

                            <div>
                                <button type="button"
                                        class="btn btn-secondary me-2"
                                        x-on:click.prevent="$refs.photo.click()">
                                    {{ __('Select A New Photo') }}
                                </button>

                                @if ($this->user->profile_photo_path)
                                    <button type="button"
                                            class="btn btn-outline-danger"
                                            wire:click="deleteProfilePhoto">
                                        {{ __('Remove Photo') }}
                                    </button>
                                @endif
                            </div>
                        </div>

                        @error('photo')
                            <div class="text-danger mt-2">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">{{ __('Name') }}</label>
                    <input id="name"
                           type="text"
                           class="form-control @error('name') is-invalid @enderror"
                           wire:model="state.name"
                           required
                           autocomplete="name" />
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">{{ __('Email') }}</label>
                    <input id="email"
                           type="email"
                           class="form-control @error('email') is-invalid @enderror"
                           wire:model="state.email"
                           required
                           autocomplete="username" />
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                        <div class="alert alert-warning mt-2 d-flex align-items-center" role="alert">
                            <div>
                                {{ __('Your email address is unverified.') }}
                                <button type="button"
                                        class="btn btn-link p-0 m-0 align-baseline"
                                        wire:click.prevent="sendEmailVerification">
                                    {{ __('Click here to re-send the verification email.') }}
                                </button>
                            </div>
                        </div>

                        @if ($this->verificationLinkSent)
                            <div class="alert alert-success mt-2">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    @if (session()->has('status'))
                        <div class="alert alert-success mb-0" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <button type="submit"
                            class="btn btn-primary"
                            wire:loading.attr="disabled"
                            wire:target="photo">
                        <span wire:loading.remove wire:target="photo">{{ __('Save') }}</span>
                        <span wire:loading wire:target="photo" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
