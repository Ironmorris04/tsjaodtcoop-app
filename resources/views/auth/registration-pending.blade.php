<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Pending - TSJAODTCooperative System</title>

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
            width: 100%;
            overflow-x: hidden;
        }

        .pending-page {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .pending-page::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -200px;
            right: -200px;
        }

        .pending-page::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -150px;
            left: -150px;
        }

        .pending-container {
            max-width: 650px;
            width: 100%;
            position: relative;
            z-index: 1;
            margin: auto;
        }

        .pending-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-30px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 0 20px rgba(102, 126, 234, 0);
            }
        }

        .success-icon i {
            font-size: 40px;
            color: white;
        }

        .pending-card h2 {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 0.75rem;
            font-size: 1.5rem;
        }

        .pending-card p {
            color: #6c757d;
            font-size: 0.9375rem;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
            text-align: left;
        }

        .info-box h4 {
            color: #667eea;
            font-size: 0.9375rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .info-box ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .info-box li {
            padding: 0.5rem 0;
            color: #495057;
            display: flex;
            align-items: start;
            gap: 10px;
            font-size: 0.875rem;
        }

        .info-box li i {
            color: #28a745;
            margin-top: 3px;
            font-size: 0.75rem;
            flex-shrink: 0;
        }

        .email-highlight {
            background: #e3f2fd;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            color: #1976d2;
            margin: 0.75rem auto;
            display: inline-block;
            font-size: 0.875rem;
            word-break: break-all;
            max-width: 100%;
        }

        .email-highlight i {
            margin-right: 6px;
        }

        .btn-return {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 0.5rem;
        }

        .btn-return:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-download {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
            padding: 0.75rem 1.75rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9375rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin-top: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .btn-download:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-download i {
            font-size: 1.125rem;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            margin-top: 1rem;
        }

        .divider {
            height: 1px;
            background: linear-gradient(to right, transparent, #dee2e6, transparent);
            margin: 1rem 0;
        }

        /* Mobile Phones (up to 640px) */
        @media (max-width: 640px) {
            .pending-page {
                padding: 0.75rem;
            }

            .pending-container {
                width: 100%;
            }

            .pending-card {
                padding: 1.25rem;
                border-radius: 12px;
            }

            .pending-card h2 {
                font-size: 1.25rem;
                margin-bottom: 0.5rem;
            }

            .pending-card p {
                font-size: 0.875rem;
                margin-bottom: 0.75rem;
            }

            .success-icon {
                width: 60px;
                height: 60px;
                margin-bottom: 1rem;
            }

            .success-icon i {
                font-size: 30px;
            }

            .info-box {
                padding: 0.875rem;
                margin: 0.875rem 0;
            }

            .info-box h4 {
                font-size: 0.875rem;
                margin-bottom: 0.5rem;
            }

            .info-box li {
                padding: 0.375rem 0;
                font-size: 0.8125rem;
                gap: 8px;
            }

            .email-highlight {
                font-size: 0.8125rem;
                padding: 0.625rem 0.875rem;
            }

            .btn-download,
            .btn-return {
                font-size: 0.875rem;
                padding: 0.625rem 1.5rem;
                width: 100%;
                justify-content: center;
            }

            .divider {
                margin: 0.875rem 0;
            }
        }

        /* Tablets (641px to 1024px) */
        @media (min-width: 641px) and (max-width: 1024px) {
            .pending-page {
                padding: 1.25rem;
            }

            .pending-container {
                max-width: 600px;
            }

            .pending-card {
                padding: 1.75rem;
            }

            .pending-card h2 {
                font-size: 1.375rem;
            }

            .success-icon {
                width: 70px;
                height: 70px;
            }

            .success-icon i {
                font-size: 35px;
            }

            .info-box li {
                font-size: 0.875rem;
            }

            .btn-download,
            .btn-return {
                padding: 0.75rem 1.75rem;
            }
        }

        /* Desktop (1025px and above) */
        @media (min-width: 1025px) {
            .pending-page {
                padding: 2rem;
            }

            .pending-container {
                max-width: 700px;
            }

            .pending-card {
                padding: 2.5rem;
            }

            .pending-card h2 {
                font-size: 1.75rem;
            }

            .success-icon {
                width: 90px;
                height: 90px;
            }

            .success-icon i {
                font-size: 45px;
            }

            .info-box {
                padding: 1.5rem;
            }

            .btn-download,
            .btn-return {
                padding: 0.875rem 2rem;
            }
        }

        /* Landscape mode optimization for small devices */
        @media (max-height: 600px) and (orientation: landscape) {
            .pending-page {
                padding: 0.5rem;
            }

            .pending-card {
                padding: 1rem;
            }

            .success-icon {
                width: 50px;
                height: 50px;
                margin-bottom: 0.75rem;
            }

            .success-icon i {
                font-size: 25px;
            }

            .pending-card h2 {
                font-size: 1.125rem;
                margin-bottom: 0.5rem;
            }

            .info-box {
                padding: 0.75rem;
                margin: 0.75rem 0;
            }

            .info-box li {
                padding: 0.25rem 0;
            }

            .btn-download,
            .btn-return {
                padding: 0.5rem 1.25rem;
                font-size: 0.8125rem;
            }
        }
    </style>
</head>
<body class="pending-page">
    <div class="pending-container">
        <div class="pending-card">
            <div class="success-icon">
                <i class="fas fa-clock"></i>
            </div>

            <h2>Registration Submitted Successfully!</h2>

            <p>Thank you for submitting your registration to the TSJAODTCooperative System.</p>

            @if(session('email'))
            <div class="email-highlight">
                <i class="fas fa-envelope"></i>
                {{ session('email') }}
            </div>
            @endif

            <div class="info-box">
                <h4>
                    <i class="fas fa-info-circle"></i>
                    What happens next?
                </h4>
                <ul>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Our admin team will review your application and submitted documents.</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>You will receive an email notification once your account has been reviewed.</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>Upon approval, you will be able to log in and access the system.</span>
                    </li>
                    <li>
                        <i class="fas fa-check-circle"></i>
                        <span>The review process typically takes 1-3 business days.</span>
                    </li>
                </ul>
            </div>

            <div class="divider"></div>

            <p style="font-size: 14px; color: #6c757d; margin-bottom: 0.5rem;">
                <i class="fas fa-question-circle"></i>
                If you have any questions, please contact the cooperative administrator.
            </p>

            <div class="action-buttons">
                @if(session('operator_id'))
                    <a href="{{ route('download.membership.form', ['operator_id' => session('operator_id')]) }}" class="btn-download">
                        <i class="fas fa-file-download"></i> Download Pre-filled Membership Form (PDF)
                    </a>
                @else
                    <a href="{{ route('download.membership.form') }}" class="btn-download">
                        <i class="fas fa-file-download"></i> Download Membership Form (PDF)
                    </a>
                @endif

                <a href="{{ route('landing') }}" class="btn-return">
                    <i class="fas fa-home"></i> Return to Home
                </a>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>
