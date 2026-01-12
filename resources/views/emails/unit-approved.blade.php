<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unit Approved</title>
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
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
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
            color: #007bff;
            font-size: 20px;
            margin-top: 0;
        }
        .email-body p {
            margin: 15px 0;
            color: #555;
        }
        .unit-id-box {
            background: #f8f9fa;
            border-left: 4px solid #007bff;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .unit-id-box .label {
            font-size: 12px;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .unit-id-box .value {
            font-size: 24px;
            font-weight: 700;
            color: #007bff;
            font-family: 'Courier New', monospace;
        }
        .info-box {
            background: #cfe2ff;
            border: 1px solid #b6d4fe;
            border-radius: 6px;
            padding: 15px;
            margin: 20px 0;
        }
        .info-box p {
            margin: 8px 0;
            color: #084298;
            font-size: 14px;
        }
        .info-box strong {
            color: #052c65;
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
            <h1>Unit Approved!</h1>
        </div>

        <div class="email-body">
            <h2>Transport Unit Application Approved</h2>

            <p>Dear {{ $operatorName }},</p>

            <p>We are pleased to inform you that your transport unit application for vehicle with plate number <strong>{{ $plateNo }}</strong> has been approved by the administration.</p>

            <p>The unit is now registered in the TSJAODTCooperative System.</p>

            <div class="info-box">
                <p><strong>Unit Details:</strong></p>
                <p>• Plate Number: <strong>{{ $plateNo }}</strong></p>
                <p>• Status: <strong>Approved</strong></p>
            </div>

            <div class="divider"></div>

            <p>You can now view this unit's information in your operator dashboard. The unit is ready to be assigned to drivers for operations.</p>

            <p style="margin-top: 30px;">Thank you for your cooperation!</p>

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
