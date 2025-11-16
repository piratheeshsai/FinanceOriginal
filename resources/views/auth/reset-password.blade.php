<x-guest-layout>
    <style>
       .auth-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        padding: 2rem;
    }

    /* Auth Card */
    .auth-card {
        background: white;
        padding: 2.5rem;
        border-radius: 1rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 400px;
        transition: transform 0.3s ease;
    }

    /* Auth Logo */
    .auth-logo {
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .auth-logo img {
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    /* Typography */
    .auth-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #1a202c;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .auth-description {
        color: #4a5568;
        font-size: 0.875rem;
        text-align: center;
        margin-bottom: 2rem;
        line-height: 1.5;
    }

    /* Form Elements */
    .input-group {
        margin-bottom: 1.5rem;
    }

    .input-label {
        display: block;
        color: #4a5568;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .input-field {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: border-color 0.3s ease;
    }

    .input-field:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* Auth Button */
    .auth-button {
        width: 100%;
        padding: 0.75rem 1rem;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 0.5rem;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .auth-button:hover {
        background: #5a67d8;
    }

    /* Links */
    .auth-links {
        text-align: center;
        margin-top: 1.5rem;
    }

    .auth-link {
        color: #667eea;
        font-size: 0.875rem;
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .auth-link:hover {
        color: #5a67d8;
    }

    /* Status Messages */
    .auth-status-message {
        padding: 1rem;
        background: #48bb78;
        color: white;
        border-radius: 0.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
        font-size: 0.875rem;
    }

    /* Error Messages */
    .auth-errors {
        color: #e53e3e;
        font-size: 0.875rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #fff5f5;
        border-radius: 0.5rem;
        border: 1px solid #fed7d7;
    }

    </style>

    <div class="auth-container">
        <div class="auth-card">
            <!-- Logo Section -->
            <div class="auth-logo" style="min-height: 80px; width: 100%; max-width: 100%; display: flex; align-items: center; justify-content: center; overflow: visible;">
                @php
                    $company = App\Models\Company::first();
                    $logoPath = $company && $company->logo
                        ? Storage::url('logos/' . $company->logo)
                        : asset('../assets/img/logo-ct.png');
                @endphp
                <img src="{{ $logoPath }}"
                     alt="{{ $company->name ?? 'Company Logo' }}"
                     style="height: 70px; width: auto; max-width: none; object-fit: contain;"
                     onerror="this.onerror=null;this.src='{{ asset('../assets/img/logo-ct.png') }}';">
            </div>

            <h2 class="auth-title">Reset Password</h2>

            @if (session('status'))
                <div class="auth-status-message">
                    {{ session('status') }}
                </div>
            @endif

            <x-validation-errors class="auth-errors" />

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Input -->
                <div class="input-group">
                    <label for="email" class="input-label">Email</label>
                    <input id="email" type="email" name="email"
                           value="{{ old('email', $request->email) }}"
                           required autofocus autocomplete="username"
                           class="input-field" placeholder="Enter your email">
                </div>

                <!-- Password Input -->
                <div class="input-group">
                    <label for="password" class="input-label">New Password</label>
                    <input id="password" type="password" name="password"
                           required autocomplete="new-password"
                           class="input-field" placeholder="Enter new password">
                </div>

                <!-- Confirm Password Input -->
                <div class="input-group">
                    <label for="password_confirmation" class="input-label">Confirm Password</label>
                    <input id="password_confirmation" type="password"
                           name="password_confirmation" required autocomplete="new-password"
                           class="input-field" placeholder="Confirm your password">
                </div>

                <!-- Submit Button -->
                <button type="submit" class="auth-button">
                    {{ __('Reset Password') }}
                </button>

                <!-- Back to Login -->
                <div class="auth-links">
                    <a href="{{ route('login') }}" class="auth-link">
                        ← Return to Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
