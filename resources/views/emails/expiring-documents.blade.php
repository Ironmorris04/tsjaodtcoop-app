<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expiring Documents Notification</title>
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
            max-width: 650px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .email-body {
            padding: 30px 20px;
        }
        .email-body h2 {
            color: #fd7e14;
            font-size: 20px;
            margin-top: 0;
        }
        .document-box {
            background: #fff3cd;
            border-left: 6px solid #fd7e14;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .document-box.expired {
            background: #f8d7da;
            border-color: #dc3545;
        }
        .document-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        .document-box strong {
            color: #212529;
        }
        .email-footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Expiring Documents Notification</h1>
        </div>

        <div class="email-body">
            <h2>Hello {{ $recipientName }},</h2>

            <p>The following documents are expiring soon or have already expired. Please take immediate action to renew or update them:</p>

            @foreach ($documents as $doc)
                @php
                    $expiryDate = isset($doc->expiry_date) ? \Carbon\Carbon::parse($doc->expiry_date)->format('F d, Y') : 'N/A';
                    $daysRemaining = isset($doc->expiry_date) ? \Carbon\Carbon::now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($doc->expiry_date)->startOfDay(), false) : null;
                    $status = $daysRemaining !== null
                        ? ($daysRemaining > 0 ? 'Expires in ' . $daysRemaining . ' days' : 'Expired ' . abs($daysRemaining) . ' days ago')
                        : 'Expiry date unknown';
                    $boxClass = ($daysRemaining !== null && $daysRemaining < 0) ? 'expired' : '';
                @endphp

                <div class="document-box {{ $boxClass }}">
                    <p><strong>Owner:</strong> {{ $doc->owner_name ?? 'Unknown' }}</p>
                    <p><strong>Document Type:</strong> {{ $doc->formatted_type ?? ($doc->type ?? 'Unknown') }}</p>
                    <p><strong>Document Number:</strong> {{ $doc->document_number ?? ($doc->number ?? 'N/A') }}</p>
                    <p><strong>Expiry Date:</strong> {{ $expiryDate }}</p>
                    <p><strong>Status:</strong> {{ $status }}</p>
                </div>
            @endforeach

            <p>Please ensure that all documents are updated before their expiry to avoid disruptions in operations.</p>

            <p>Thank you,<br>
            <strong>TSJAODTCooperative Administration Team</strong></p>
        </div>

        <div class="email-footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} TSJAODTCooperative System. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
