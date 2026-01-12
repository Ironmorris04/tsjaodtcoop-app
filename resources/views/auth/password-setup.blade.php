<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Set Up Your Password - Transport Coop System</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('images/tsjaodt-logo.png') }}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            overflow: hidden;
        }

        .setup-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100vw;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            padding: 20px;
        }

        .setup-page::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -200px;
            right: -200px;
        }

        .setup-page::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
        }

        .setup-container {
            max-width: 500px;
            width: 90%;
            max-height: calc(100vh - 40px);
            position: relative;
            z-index: 1;
            margin: auto;
            display: flex;
            flex-direction: column;
        }

        .setup-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.5s ease;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            max-height: 100%;
        }

        .setup-card-header {
            padding: 2.5rem 2rem 1rem 2rem;
            flex-shrink: 0;
        }

        .setup-card-body {
            padding: 0 2rem 2.5rem 2rem;
            overflow-y: auto;
            flex: 1;
        }

        /* Custom scrollbar for the form */
        .setup-card-body::-webkit-scrollbar {
            width: 8px;
        }

        .setup-card-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 0 16px 16px 0;
        }

        .setup-card-body::-webkit-scrollbar-thumb {
            background: #667eea;
            border-radius: 4px;
        }

        .setup-card-body::-webkit-scrollbar-thumb:hover {
            background: #764ba2;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .setup-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            flex-shrink: 0;
        }

        .setup-icon i {
            font-size: 40px;
            color: white;
        }

        .setup-card h2 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 0.75rem;
            font-size: 24px;
            text-align: center;
        }

        .setup-card p {
            color: #6c757d;
            font-size: 15px;
            line-height: 1.5;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .user-id-display {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 1.5rem;
            border-radius: 8px;
        }

        .user-id-display .label {
            font-size: 11px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .user-id-display .value {
            font-size: 20px;
            font-weight: 700;
            color: #667eea;
            font-family: 'Courier New', monospace;
            word-break: break-all;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
            font-size: 14px;
        }

        .form-group label .required {
            color: #dc3545;
        }

        .password-input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
            transition: color 0.3s;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .invalid-feedback {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        /* Password Strength Meter */
        .password-strength-meter {
            margin-top: 10px;
            display: none;
        }

        .password-strength-meter.active {
            display: block;
        }

        .strength-meter-bar {
            height: 8px;
            background: #e9ecef;
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 8px;
        }

        .strength-meter-fill {
            height: 100%;
            width: 0%;
            transition: all 0.3s ease;
            border-radius: 4px;
        }

        .strength-meter-fill.weak {
            width: 33%;
            background: #dc3545;
        }

        .strength-meter-fill.medium {
            width: 66%;
            background: #ffc107;
        }

        .strength-meter-fill.strong {
            width: 100%;
            background: #28a745;
        }

        .strength-meter-text {
            font-size: 13px;
            font-weight: 600;
            text-align: center;
        }

        .strength-meter-text.weak {
            color: #dc3545;
        }

        .strength-meter-text.medium {
            color: #ffc107;
        }

        .strength-meter-text.strong {
            color: #28a745;
        }

        .password-requirements {
            background: #f8f9fa;
            padding: 12px 15px;
            border-radius: 6px;
            margin-top: 10px;
            font-size: 13px;
            color: #6c757d;
        }

        .password-requirements ul {
            margin: 5px 0 0 0;
            padding-left: 20px;
        }

        .password-requirements li {
            margin: 3px 0;
            transition: color 0.3s;
        }

        .password-requirements li.met {
            color: #28a745;
        }

        .password-requirements li.met::before {
            content: '✓ ';
            font-weight: bold;
        }

        .btn-setup {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 14px 0;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-setup:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-setup:active {
            transform: translateY(0);
        }

        .btn-setup:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 14px;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .setup-page {
                padding: 15px;
            }

            .setup-container {
                width: 95%;
                max-height: calc(100vh - 30px);
            }

            .setup-card-header {
                padding: 2rem 1.5rem 1rem 1.5rem;
            }

            .setup-card-body {
                padding: 0 1.5rem 2rem 1.5rem;
            }

            .setup-card h2 {
                font-size: 20px;
            }

            .setup-icon {
                width: 70px;
                height: 70px;
            }

            .setup-icon i {
                font-size: 35px;
            }

            .user-id-display .value {
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .setup-card {
                border-radius: 12px;
            }

            .setup-card-header {
                padding: 1.5rem 1rem 1rem 1rem;
            }

            .setup-card-body {
                padding: 0 1rem 1.5rem 1rem;
            }

            .setup-card h2 {
                font-size: 18px;
            }

            .setup-card p {
                font-size: 14px;
            }

            .setup-icon {
                width: 60px;
                height: 60px;
                margin-bottom: 1rem;
            }

            .setup-icon i {
                font-size: 30px;
            }

            .user-id-display {
                padding: 12px;
            }

            .user-id-display .value {
                font-size: 16px;
            }

            .form-control {
                padding: 10px 40px 10px 12px;
                font-size: 14px;
            }

            .btn-setup {
                padding: 12px 0;
                font-size: 15px;
            }
        }

        /* Very small screens */
        @media (max-height: 600px) {
            .setup-page {
                padding: 10px;
            }

            .setup-container {
                max-height: calc(100vh - 20px);
            }

            .setup-card-header {
                padding: 1.5rem 1.5rem 0.75rem 1.5rem;
            }

            .setup-card-body {
                padding: 0 1.5rem 1.5rem 1.5rem;
            }

            .setup-icon {
                width: 60px;
                height: 60px;
                margin-bottom: 1rem;
            }

            .setup-icon i {
                font-size: 30px;
            }

            .form-group {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body class="setup-page">
    <div class="setup-container">
        <div class="setup-card">
            <div class="setup-card-header">
                <div class="setup-icon">
                    <i class="fas fa-key"></i>
                </div>

                <h2>Set Up Your Password</h2>
                <p>Create a secure password for your account to complete the setup process.</p>

                <div class="user-id-display">
                    <div class="label">Your User ID</div>
                    <div class="value">{{ $user->user_id }}</div>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-circle"></i>
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="setup-card-body">
                <form method="POST" action="{{ route('password.setup.store') }}" id="passwordSetupForm">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="password">
                        Password <span class="required">*</span>
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               required
                               autofocus>
                        <i class="fas fa-eye toggle-password" id="togglePassword"></i>
                    </div>
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror

                    <!-- Password Strength Meter -->
                    <div class="password-strength-meter" id="strengthMeter">
                        <div class="strength-meter-bar">
                            <div class="strength-meter-fill" id="strengthBar"></div>
                        </div>
                        <div class="strength-meter-text" id="strengthText"></div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">
                        Confirm Password <span class="required">*</span>
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               required>
                        <i class="fas fa-eye toggle-password" id="togglePasswordConfirm"></i>
                    </div>
                </div>

                <div class="password-requirements">
                    <strong><i class="fas fa-shield-alt"></i> Password Requirements:</strong>
                    <ul>
                        <li id="req-length">At least 8 characters long</li>
                        <li id="req-uppercase">At least one uppercase letter</li>
                        <li id="req-lowercase">At least one lowercase letter</li>
                        <li id="req-number">At least one number</li>
                        <li id="req-special">At least one special character</li>
                    </ul>
                </div>

                    <button type="submit" class="btn-setup">
                        <i class="fas fa-check-circle"></i> Set Password & Continue
                    </button>
                </form>

                <p style="margin-top: 1.5rem; font-size: 13px; color: #6c757d; text-align: center;">
                    <i class="fas fa-info-circle"></i>
                    After setting your password, you'll be redirected to the login page.
                </p>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <script>
        // Toggle Password Visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this;

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        document.getElementById('togglePasswordConfirm').addEventListener('click', function() {
            const passwordConfirmInput = document.getElementById('password_confirmation');
            const icon = this;

            if (passwordConfirmInput.type === 'password') {
                passwordConfirmInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordConfirmInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Password Strength Checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthMeter = document.getElementById('strengthMeter');
            const strengthBar = document.getElementById('strengthBar');
            const strengthText = document.getElementById('strengthText');

            // Show strength meter when user starts typing
            if (password.length > 0) {
                strengthMeter.classList.add('active');
            } else {
                strengthMeter.classList.remove('active');
                return;
            }

            // Calculate password strength
            let strength = 0;
            const requirements = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };

            // Update requirement list
            document.getElementById('req-length').classList.toggle('met', requirements.length);
            document.getElementById('req-uppercase').classList.toggle('met', requirements.uppercase);
            document.getElementById('req-lowercase').classList.toggle('met', requirements.lowercase);
            document.getElementById('req-number').classList.toggle('met', requirements.number);
            document.getElementById('req-special').classList.toggle('met', requirements.special);

            // Calculate strength score
            if (requirements.length) strength += 20;
            if (requirements.uppercase) strength += 20;
            if (requirements.lowercase) strength += 20;
            if (requirements.number) strength += 20;
            if (requirements.special) strength += 20;

            // Update strength meter
            strengthBar.className = 'strength-meter-fill';
            strengthText.className = 'strength-meter-text';

            if (strength <= 40) {
                strengthBar.classList.add('weak');
                strengthText.classList.add('weak');
                strengthText.textContent = 'Weak Password';
            } else if (strength <= 80) {
                strengthBar.classList.add('medium');
                strengthText.classList.add('medium');
                strengthText.textContent = 'Medium Password';
            } else {
                strengthBar.classList.add('strong');
                strengthText.classList.add('strong');
                strengthText.textContent = 'Strong Password';
            }
        });

        // Form validation
        document.getElementById('passwordSetupForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;

            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Passwords do not match. Please try again.');
                return false;
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                return false;
            }
        });
    </script>
</body>
</html>
