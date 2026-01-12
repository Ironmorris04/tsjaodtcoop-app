<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Created - TSJAODTCooperative System</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #667eea;
            font-size: 20px;
            margin-top: 0;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .info-box strong {
            color: #667eea;
            display: block;
            margin-bottom: 5px;
        }
        .setup-button {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 20px 0;
            text-align: center;
        }
        .setup-button:hover {
            opacity: 0.9;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 5px;
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>🎉 Account Created Successfully!</h1>
        </div>

        <div class="email-body">
            <h2>Welcome, {{ $operatorName }}!</h2>

            <p>Your operator account has been successfully created by the administrator. You can now access the TSJAODTCooperative System.</p>

            <div class="info-box">
                <strong>Your User ID:</strong>
                <span style="font-size: 18px; font-family: monospace; color: #333;">{{ $userId }}</span>
            </div>

            <div class="note">
                <strong>⚠️ Important:</strong> You need to set up your password before you can log in to your account.
            </div>

            <p><strong>Next Steps:</strong></p>
            <ol>
                <li>Click the button below to set up your password</li>
                <li>Create a strong password (minimum 8 characters)</li>
                <li>Log in using your User ID and the password you created</li>
            </ol>

            <div class="button-container">
                <a href="{{ $setupUrl }}" class="setup-button">Set Up Password</a>
            </div>

            <div class="note">
                <strong>🔒 Security Note:</strong> This link will expire in 24 hours for security reasons. If you don't set up your password within this time, please contact the administrator.
            </div>

            <p style="margin-top: 30px;">If you have any questions or need assistance, please contact our support team.</p>
        </div>

        <div class="email-footer">
            <p>© {{ date('Y') }} TSJAODTCooperative System. All rights reserved.</p>
            <p style="margin-top: 10px; font-size: 12px;">
                If you didn't expect this email, please ignore it or contact the administrator.
            </p>
        </div>
    </div>
</body>
</html>
