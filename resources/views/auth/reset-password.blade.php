<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Transport Coop System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(0.5rem, 2vw, 1.5rem) clamp(0.75rem, 3vw, 1.25rem);
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
        }

        body::before {
            content: '';
            position: absolute;
            width: min(500px, 80vw);
            height: min(500px, 80vw);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: clamp(-250px, -30vh, -200px);
            right: clamp(-250px, -30vw, -200px);
            pointer-events: none;
        }

        body::after {
            content: '';
            position: absolute;
            width: min(400px, 65vw);
            height: min(400px, 65vw);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: clamp(-200px, -25vh, -150px);
            left: clamp(-200px, -25vw, -150px);
            pointer-events: none;
        }

        .reset-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: min(550px, 90vw);
            margin: auto;
        }

        .floating-form {
            background: white;
            border-radius: clamp(12px, 2.5vw, 20px);
            padding: clamp(1.5rem, 4vw, 2.5rem) clamp(1.25rem, 5vw, 3rem);
            box-shadow: 0 clamp(10px, 3vh, 20px) clamp(30px, 8vh, 60px) rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
            width: 100%;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header {
            text-align: center;
            margin-bottom: clamp(1rem, 3vw, 1.5rem);
        }

        .form-header .logo {
            font-size: clamp(2rem, 5vw, 2.5rem);
            color: #667eea;
            margin-bottom: clamp(0.25rem, 1vw, 0.5rem);
        }

        .form-header h2 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: clamp(0.25rem, 1vw, 0.5rem);
            font-size: clamp(1.25rem, 3.5vw, 1.6rem);
            line-height: 1.3;
        }

        .form-header p {
            color: #718096;
            font-size: clamp(0.813rem, 2vw, 0.9rem);
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: clamp(1rem, 2.5vw, 1.25rem);
        }

        .form-group label {
            display: block;
            color: #4a5568;
            font-weight: 500;
            margin-bottom: clamp(0.375rem, 1vw, 0.5rem);
            font-size: clamp(0.875rem, 2vw, 0.95rem);
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: clamp(0.75rem, 2vw, 1rem);
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: clamp(1rem, 2.2vw, 1.1rem);
        }

        .form-control {
            width: 100%;
            padding: clamp(0.75rem, 2vw, 0.85rem) clamp(2.5rem, 6vw, 3.2rem) clamp(0.75rem, 2vw, 0.85rem) clamp(2.5rem, 5vw, 2.8rem);
            border: 2px solid #e2e8f0;
            border-radius: clamp(8px, 1.5vw, 10px);
            font-size: clamp(0.875rem, 2vw, 1rem);
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
            line-height: 1.5;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 clamp(2px, 0.5vw, 3px) rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid {
            border-color: #f56565;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: clamp(0.75rem, 2vw, 1rem);
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            font-size: clamp(0.9rem, 2vw, 1rem);
            padding: clamp(0.375rem, 1vw, 0.5rem);
            transition: color 0.3s;
            z-index: 10;
            min-width: 44px;
            min-height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .invalid-feedback {
            color: #f56565;
            font-size: clamp(0.75rem, 1.8vw, 0.85rem);
            margin-top: 0.25rem;
            display: block;
        }

        .btn {
            width: 100%;
            padding: clamp(0.75rem, 2vw, 0.9rem);
            border: none;
            border-radius: clamp(8px, 1.5vw, 10px);
            font-size: clamp(0.875rem, 2vw, 1rem);
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: clamp(0.375rem, 1vw, 0.5rem);
            text-decoration: none;
            min-height: 44px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 clamp(3px, 0.8vw, 4px) clamp(12px, 3vw, 15px) rgba(102, 126, 234, 0.4);
            margin-top: clamp(0.75rem, 2vw, 1rem);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 clamp(4px, 1vw, 6px) clamp(15px, 4vw, 20px) rgba(102, 126, 234, 0.5);
        }

        .btn-secondary {
            background: #f7fafc;
            color: #4a5568;
            border: 2px solid #e2e8f0;
            margin-top: clamp(0.625rem, 1.5vw, 0.8rem);
        }

        .btn-secondary:hover {
            background: #edf2f7;
        }

        .alert {
            padding: clamp(0.75rem, 2vw, 0.9rem);
            border-radius: clamp(8px, 1.5vw, 10px);
            margin-bottom: clamp(1rem, 2.5vw, 1.25rem);
            display: flex;
            align-items: center;
            gap: clamp(0.5rem, 1.5vw, 0.75rem);
            font-size: clamp(0.813rem, 2vw, 0.9rem);
        }

        .alert-success {
            background: #c6f6d5;
            color: #2f855a;
            border: 1px solid #9ae6b4;
        }

        .alert-error {
            background: #fed7d7;
            color: #c53030;
            border: 1px solid #fc8181;
        }

        .password-requirements {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: clamp(6px, 1.2vw, 8px);
            padding: clamp(0.625rem, 1.5vw, 0.75rem);
            margin-top: clamp(0.75rem, 2vw, 1rem);
            font-size: clamp(0.75rem, 1.8vw, 0.85rem);
        }

        .password-requirements h6 {
            color: #4a5568;
            font-weight: 600;
            margin-bottom: clamp(0.375rem, 1vw, 0.5rem);
            font-size: clamp(0.813rem, 2vw, 0.9rem);
        }

        .password-requirements ul {
            margin: 0;
            padding-left: clamp(1rem, 2.5vw, 1.25rem);
            color: #718096;
        }

        .password-requirements li {
            margin-bottom: clamp(0.188rem, 0.5vw, 0.25rem);
            line-height: 1.4;
        }

        /* Mobile Portrait: 320px - 480px */
        @media (max-width: 480px) {
            body {
                padding: 0.75rem 0.5rem;
            }

            .floating-form {
                padding: 1.5rem 1.25rem;
                border-radius: 12px;
            }

            .form-header h2 {
                font-size: 1.25rem;
            }

            .form-header .logo {
                font-size: 2rem;
            }

            .form-control {
                padding: 0.75rem 2.5rem 0.75rem 2.5rem;
                font-size: 0.875rem;
            }

            .password-requirements {
                font-size: 0.75rem;
            }

            .btn {
                font-size: 0.875rem;
                padding: 0.75rem;
            }
        }

        /* Mobile Landscape & Small Tablets: 481px - 767px */
        @media (min-width: 481px) and (max-width: 767px) {
            .reset-container {
                max-width: 85vw;
            }

            .floating-form {
                padding: 2rem 2rem;
            }

            .form-control {
                font-size: 0.938rem;
            }
        }

        /* Tablets: 768px - 1024px */
        @media (min-width: 768px) and (max-width: 1024px) {
            .reset-container {
                max-width: min(500px, 75vw);
            }

            .floating-form {
                padding: 2.25rem 2.5rem;
            }

            body {
                padding: 2rem 1.5rem;
            }
        }

        /* Desktop: 1025px+ */
        @media (min-width: 1025px) {
            .reset-container {
                max-width: 550px;
            }

            .floating-form {
                padding: 2.5rem 3rem;
            }
        }

        /* Extra small devices (very small phones) */
        @media (max-width: 360px) {
            body {
                padding: 0.5rem 0.375rem;
            }

            .floating-form {
                padding: 1.25rem 1rem;
            }

            .form-header h2 {
                font-size: 1.125rem;
            }

            .form-header .logo {
                font-size: 1.75rem;
            }

            .password-requirements {
                font-size: 0.688rem;
            }
        }

        /* Large Desktop: 1440px+ */
        @media (min-width: 1440px) {
            .floating-form {
                box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
            }
        }

        /* Very small height devices (landscape phones) */
        @media (max-height: 600px) {
            body {
                padding: 0.5rem;
                align-items: flex-start;
            }

            .floating-form {
                padding: 1rem 1.5rem;
                margin: 0.5rem 0;
            }

            .form-header {
                margin-bottom: 0.75rem;
            }

            .form-group {
                margin-bottom: 0.75rem;
            }

            .password-requirements {
                margin-top: 0.5rem;
                padding: 0.5rem;
            }

            .btn {
                padding: 0.625rem;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="floating-form">
            <div class="form-header">
                <div class="logo">
                    <i class="fas fa-lock-open"></i>
                </div>
                <h2>Reset Password</h2>
                <p>Create a new password for your account</p>
            </div>

            @if (session('status'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <span>{{ session('status') }}</span>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email', $request->email) }}"
                               placeholder="Enter your email"
                               required
                               autofocus
                               autocomplete="username"
                               style="padding-right: 1rem;">
                    </div>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">New Password</label>
                    <div class="password-wrapper">
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="password"
                                   name="password"
                                   placeholder="Enter new password"
                                   required
                                   autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('password', 'toggleIcon1')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">Confirm New Password</label>
                    <div class="password-wrapper">
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password"
                                   class="form-control @error('password_confirmation') is-invalid @enderror"
                                   id="password_confirmation"
                                   name="password_confirmation"
                                   placeholder="Confirm new password"
                                   required
                                   autocomplete="new-password">
                            <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation', 'toggleIcon2')">
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                    </div>
                    @error('password_confirmation')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="password-requirements">
                    <h6><i class="fas fa-info-circle"></i> Password Requirements:</h6>
                    <ul>
                        <li>At least 8 characters long</li>
                        <li>Contains uppercase and lowercase letters</li>
                        <li>Includes at least one number</li>
                        <li>Has at least one special character</li>
                    </ul>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-check-circle"></i>
                    Reset Password
                </button>

                <a href="{{ route('login') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Login
                </a>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(iconId);

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
