<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password - Transport Coop System</title>

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

        .forgot-container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
        }

        .floating-form {
            background: white;
            border-radius: 20px;
            padding: 2.5rem 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.5s ease-out;
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
            margin-bottom: 1.5rem;
        }

        .form-header .logo {
            font-size: 2.5rem;
            color: #667eea;
            margin-bottom: 0.5rem;
        }

        .form-header h2 {
            color: #2d3748;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.6rem;
        }

        .form-header p {
            color: #718096;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .form-group {
            margin-bottom: 1.5rem;
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
            padding: 0.9rem 1rem 0.9rem 2.8rem;
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

        .invalid-feedback {
            color: #f56565;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: block;
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
            text-decoration: none;
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
            margin-top: 0.8rem;
        }

        .btn-secondary:hover {
            background: #edf2f7;
        }

        .alert {
            padding: 0.9rem;
            border-radius: 10px;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 0.9rem;
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

        .info-box {
            background: #ebf4ff;
            border: 1px solid #bee3f8;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #2c5282;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .info-box i {
            margin-right: 0.5rem;
            color: #4299e1;
        }

        @media (max-width: 480px) {
            .floating-form {
                padding: 2rem 1.5rem;
            }

            .form-header h2 {
                font-size: 1.4rem;
            }

            .form-header .logo {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="forgot-container">
        <div class="floating-form">
            <div class="form-header">
                <div class="logo">
                    <i class="fas fa-key"></i>
                </div>
                <h2>Forgot Password?</h2>
                <p>No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
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

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-envelope"></i>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="Enter your registered email"
                               required
                               autofocus>
                    </div>
                    @error('email')
                    <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Email Password Reset Link
                </button>

                <a href="{{ route('login') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i>
                    Back to Login
                </a>
            </form>
        </div>
    </div>
</body>
</html>
