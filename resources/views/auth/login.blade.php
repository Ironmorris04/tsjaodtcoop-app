<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Transport Coop System</title>

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
            max-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem 20px;
            position: relative;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -200px;
            right: -200px;
        }

        body::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
        }

        .login-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 600px;
        }

        .floating-form {
            background: white;
            border-radius: 20px;
            padding: 2rem 4rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
            max-height: 95vh;
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
            margin-bottom: 1.25rem;
        }

        .form-header .logo {
            font-size: 2.2rem;
            color: #667eea;
            margin-bottom: 0.3rem;
        }

        .form-header h2 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 0.3rem;
            font-size: 1.5rem;
        }

        .form-header p {
            color: #718096;
            font-size: 0.85rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            color: #4a5568;
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 1.1rem;
        }

        .form-control {
            width: 100%;
            padding: 0.85rem 3.2rem 0.85rem 2.2rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-control.is-invalid {
            border-color: #f56565;
        }

        .password-wrapper {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #a0aec0;
            cursor: pointer;
            font-size: 1rem;
            padding: 0.5rem;
            transition: color 0.3s;
            z-index: 10;
        }

        .toggle-password:hover {
            color: #667eea;
        }

        .invalid-feedback {
            color: #f56565;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 0.5rem;
            cursor: pointer;
        }

        .remember-me label {
            margin: 0;
            cursor: pointer;
            font-size: 0.9rem;
            color: #4a5568;
        }

        .forgot-link {
            color: #667eea;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #764ba2;
        }

        .btn {
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.5);
        }

        .btn-secondary {
            background: #f7fafc;
            color: #4a5568;
            border: 2px solid #e2e8f0;
            margin-top: 0.6rem;
        }

        .btn-secondary:hover {
            background: #edf2f7;
        }

        .divider {
            text-align: center;
            margin: 1rem 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            width: 100%;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 1rem;
            color: #a0aec0;
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .back-to-home {
            text-align: center;
            margin-top: 0.75rem;
        }

        .back-to-home a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }

        .back-to-home a:hover {
            color: #764ba2;
        }

        .hidden {
            display: none;
        }

        .alert {
            padding: 0.85rem;
            border-radius: 10px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
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

        @media (max-width: 480px) {
            .floating-form {
                padding: 2rem 1.5rem;
            }

            .form-header h2 {
                font-size: 1.5rem;
            }

            .remember-forgot {
                flex-direction: column;
                gap: 0.75rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Login Form -->
        <div class="floating-form" id="loginForm">
            <div class="form-header">
                <div class="logo">
                    <i class="fas fa-bus"></i>
                </div>
                <h2>Welcome Back</h2>
                <p>Sign in to access your account</p>
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

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="user_id">User ID</label>
                    <div class="input-wrapper">
                        <i class="fas fa-id-card"></i>
                        <input type="text"
                               class="form-control @error('user_id') is-invalid @enderror @error('email') is-invalid @enderror"
                               id="user_id"
                               name="user_id"
                               value="{{ old('user_id') }}"
                               placeholder="e.g., O001-2025"
                               required
                               autofocus
                               autocomplete="off">
                    </div>
                    @error('user_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   id="password" 
                                   name="password" 
                                   placeholder="Enter your password"
                                   required>
                            <button type="button" class="toggle-password" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>
                    @error('password')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="remember-forgot">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="#" class="forgot-link" onclick="showForgotForm(event)">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-sign-in-alt"></i>
                    Sign In
                </button>

                <a href="{{ route('landing') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Home
                </a>

                <div class="divider">
                    <span>New to our cooperative?</span>
                </div>

                <div class="back-to-home">
                    <a href="{{ route('register') }}">
                        <i class="fas fa-user-plus"></i> Create an Account
                    </a>
                </div>
            </form>
        </div>

        <!-- Forgot Password Form -->
        <div class="floating-form hidden" id="forgotForm">
            <div class="form-header">
                <div class="logo">
                    <i class="fas fa-key"></i>
                </div>
                <h2>Forgot Password?</h2>
                <p>Enter your email to receive a reset link</p>
            </div>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="forgot-email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email" 
                               class="form-control" 
                               id="forgot-email" 
                               name="email" 
                               placeholder="Enter your registered email"
                               required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Send Reset Link
                </button>

                <button type="button" class="btn btn-secondary" onclick="showLoginForm()">
                    <i class="fas fa-arrow-left"></i>
                    Back to Login
                </button>
            </form>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
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

        // Show forgot password form
        function showForgotForm(event) {
            event.preventDefault();
            document.getElementById('loginForm').classList.add('hidden');
            document.getElementById('forgotForm').classList.remove('hidden');
        }

        // Show login form
        function showLoginForm() {
            document.getElementById('forgotForm').classList.add('hidden');
            document.getElementById('loginForm').classList.remove('hidden');
        }
    </script>
</body>
</html>