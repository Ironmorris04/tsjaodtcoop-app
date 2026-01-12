<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Application Update</title>
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
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
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
            color: #e74c3c;
            font-size: 20px;
            margin-top: 0;
        }
        .email-body p {
            margin: 15px 0;
            color: #555;
        }
        .driver-info-box {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .driver-info-box .label {
            font-size: 12px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .driver-info-box .value {
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }
        .reason-box {
            background: #fff5f5;
            border-left: 4px solid #e74c3c;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .reason-box .label {
            font-size: 12px;
            text-transform: uppercase;
            color: #c0392b;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }
        .reason-box .value {
            font-size: 15px;
            color: #333;
            line-height: 1.6;
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
            <div class="icon">!</div>
            <h1>Driver Application Update</h1>
        </div>

        <div class="email-body">
            <h2>Driver Application Not Approved</h2>

            <p>Dear {{ $operatorName }},</p>

            <p>We are writing to inform you about the status of the driver application you submitted to the <strong>TSJAODTCooperative System</strong>.</p>

            <div class="driver-info-box">
                <div class="label">Driver Name</div>
                <div class="value">{{ $driverName }}</div>
            </div>

            <p>After careful review of the application and submitted documents, we regret to inform you that this driver application has not been approved at this time.</p>

            <div class="reason-box">
                <div class="label">Reason for Decision</div>
                <div class="value">{{ $rejectionReason }}</div>
            </div>

            <div class="divider"></div>

            <h2>What You Can Do</h2>

            <div class="info-box">
                <p><strong>You may submit a new driver application after addressing the issues mentioned above.</strong></p>
                <p>Please ensure all required documents are complete and valid before resubmitting.</p>
                <p>If you believe this decision was made in error, please contact our administration office for further assistance.</p>
            </div>

            <p>Thank you for your understanding.</p>

            <p style="margin-top: 30px;">Best regards,<br>
            <strong>TSJAODTCooperative Administration Team</strong></p>
        </div>

        <div class="email-footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} TSJAODTCooperative System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
