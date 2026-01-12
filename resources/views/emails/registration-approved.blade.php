<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Approved</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .email-header .icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            font-size: 30px;
        }
        .email-body {
            padding: 40px 30px;
        }
        .email-body h2 {
            color: #667eea;
            font-size: 20px;
            margin-top: 0;
        }
        .email-body p {
            margin: 15px 0;
            color: #555;
        }
        .user-id-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .user-id-box .label {
            font-size: 12px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .user-id-box .value {
            font-size: 24px;
            font-weight: 700;
            color: #667eea;
            font-family: 'Courier New', monospace;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            text-decoration: none;
            padding: 15px 40px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        .info-box {
            background: #e7f3ff;
            border: 1px solid #b3d9ff;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box p {
            margin: 8px 0;
            color: #004085;
            font-size: 14px;
        }
        .info-box strong {
            color: #002752;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 13px;
        }
        .divider {
            height: 1px;
            background: #e9ecef;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="icon">✓</div>
            <h1>Congratulations!</h1>
        </div>

        <div class="email-body">
            <h2>Registration Approved</h2>

            <p>Dear {{ $operatorName }},</p>

            <p>We are pleased to inform you that your registration with the <strong>TSJAODTCooperative System</strong> has been approved upon further review of your application and submitted documents.</p>

            <div class="user-id-box">
                <div class="label">Your User ID</div>
                <div class="value">{{ $userId }}</div>
            </div>

            <p>This is your unique User ID which you will use to log in to the system. Please keep this information secure.</p>

            <div class="divider"></div>

            <h2>Next Step: Set Up Your Password</h2>

            <p>To complete your account setup, please click the button below to create your password:</p>

            <div style="text-align: center;">
                <a href="{{ $setupUrl }}" class="cta-button">Set Up Your Password</a>
            </div>

            <div class="info-box">
                <p><strong>Important Information:</strong></p>
                <p>• This link will expire in 7 days for security purposes.</p>
                <p>• After setting up your password, you can log in using your User ID: <strong>{{ $userId }}</strong></p>
                <p>• If you did not request this registration, please contact us immediately.</p>
            </div>

            <div class="divider"></div>

            <p>If the button above doesn't work, copy and paste this link into your browser:</p>
            <p style="word-break: break-all; color: #667eea; font-size: 13px;">{{ $setupUrl }}</p>

            <p style="margin-top: 30px;">Welcome to the TSJAODTCooperative System!</p>

            <p>Best regards,<br>
            <strong>TSJAODTCooperative Administration Team</strong></p>
        </div>

        <div class="email-footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} TSJAODTCooperative System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
