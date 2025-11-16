<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rural Development Investment</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/apple-touch-icon.png') }}?v=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32x32.png') }}?v=1.0">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16x16.png') }}?v=1.0">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=1.0">
    <link rel="manifest" href="{{ asset('img/site.webmanifest') }}?v=1.0">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <link id="pagestyle" href="{{ asset('css/login.css') }}" rel="stylesheet">
    <!-- Scripts -->
    <script>
        // Loading animation
        window.addEventListener('load', () => {
            setTimeout(() => {
                const loader = document.getElementById('loaderContainer');
                loader.style.opacity = '0';
                setTimeout(() => loader.style.display = 'none', 500);
            }, 1800);
        });
    </script>
</head>

<body>
    <!-- Loader Animation -->
    <div class="loader-container" id="loaderContainer">
        <div class="pulse-ring">
            <div class="logo-holder">
                <img src="{{ asset('img/logo2.png') }}" alt="JMS Lanka Logo">
            </div>
        </div>
    </div>

    <!-- Login Container -->
    <div class="login-container" id="loginContainer">
        <!-- Login Banner -->
        <div class="login-banner">
            <div class="banner-blob blob-1"></div>
            <div class="banner-blob blob-2"></div>
            <div class="banner-content">
                <h1>Welcome Back!</h1>
                <p>Securely access your financial dashboard to analyze investments, monitor real-time portfolio
                    performance, and make strategic financial decisions with confidence.</p>
            </div>
        </div>

        <!-- Login Form -->
        <div class="login-form-container">
            <div class="login-header">
                <div class="logo">
                    <img src="{{ asset('img/logo2.png') }}" alt="JMS Lanka Logo" class="img-fluid">
                </div>
                <h2 class="login-title">Sign In</h2>
                <p class="login-subtitle">Enter your credentials to access your account</p>
            </div>

            <form method="POST" action="{{ route('login') }}" id="loginForm">
                @csrf

                <!-- Alert for Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <div class="alert-icon">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="alert-content">
                            <strong>Authentication Failed</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Alert for Status Messages -->
                @if (session('status'))
                    <div class="alert alert-info">
                        <div class="alert-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="alert-content">
                            {{ session('status') }}
                        </div>
                    </div>
                @endif

                <div class="form-floating">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                    <label for="email">Email Address</label>
                    @error('email')
                        <span class="field-error">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="form-floating password-field">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                    <button type="button" class="password-toggle" onclick="togglePassword()">
                        <i class="fas fa-eye-slash" id="passwordToggleIcon"></i>
                    </button>
                    @error('password')
                        <span class="field-error">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </span>
                    @enderror
                </div>

                <div class="login-options">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-password">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-login w-100">
                    Sign In <i class="fas fa-arrow-right ms-2"></i>
                </button>

                <div class="login-footer">
                    <p class="mb-0">© {{ date('Y') }} Lushanth PVT. All rights reserved.</p>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        let isSubmitting = false; // To track if the form is already being submitted

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (isSubmitting) {
                e.preventDefault(); // Prevent multiple submissions
                return;
            }

            isSubmitting = true; // Mark the form as being submitted

            const loader = document.getElementById('loaderContainer');
            loader.style.display = 'flex';
            loader.style.opacity = '1';
        });



        // Loading animation
        window.addEventListener('load', () => {
            setTimeout(() => {
                const loader = document.getElementById('loaderContainer');
                loader.style.opacity = '0';
                setTimeout(() => loader.style.display = 'none', 500);
            }, 1800);
        });

        // Password toggle
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordToggleIcon = document.getElementById('passwordToggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordToggleIcon.classList.remove('fa-eye-slash');
                passwordToggleIcon.classList.add('fa-eye');
            } else {
                passwordInput.type = 'password';
                passwordToggleIcon.classList.remove('fa-eye');
                passwordToggleIcon.classList.add('fa-eye-slash');
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
