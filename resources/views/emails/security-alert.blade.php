{{-- resources/views/emails/security-alert.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Alert - Ordnary</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #1f2937;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .email-wrapper {
            padding: 40px 20px;
            background-color: #f3f4f6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            padding: 32px 40px 24px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
        }

        .logo {
            height: 28px;
            width: auto;
        }

        .content-wrapper {
            padding: 40px;
        }

        h1 {
            font-size: 28px;
            font-weight: 600;
            color: #111827;
            margin: 0 0 12px 0;
            line-height: 1.3;
        }

        .subtitle {
            font-size: 15px;
            color: #6b7280;
            margin: 0 0 32px 0;
            line-height: 1.5;
        }

        p {
            font-size: 15px;
            margin: 0 0 16px 0;
            color: #374151;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            background: linear-gradient(135deg, #0866FF 0%, #0652CC 100%);
            color: #ffffff !important;
            text-decoration: none;
            padding: 16px 32px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            margin: 32px 0;
            box-shadow: 0 4px 12px rgba(8, 102, 255, 0.2);
        }

        .warning-box {
            background-color: #fef2f2;
            border-left: 3px solid #ef4444;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 24px 0;
        }

        .warning-text {
            color: #991b1b;
            font-size: 14px;
            margin: 0;
            line-height: 1.6;
        }

        .details-section {
            margin: 32px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .details-header {
            background-color: #f9fafb;
            padding: 16px 20px;
            font-weight: 600;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
            font-size: 16px;
        }

        .details-content {
            padding: 20px;
        }

        .details-row {
            display: flex;
            margin-bottom: 12px;
        }

        .details-row:last-child {
            margin-bottom: 0;
        }

        .details-label {
            width: 140px;
            font-weight: 600;
            color: #6b7280;
            flex-shrink: 0;
            font-size: 14px;
        }

        .details-value {
            flex: 1;
            color: #111827;
            font-size: 14px;
        }

        .action-section {
            margin: 32px 0;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
        }

        .action-title {
            font-weight: 600;
            font-size: 16px;
            color: #111827;
            margin: 0 0 16px 0;
        }

        .action-item {
            padding-left: 24px;
            position: relative;
            margin-bottom: 12px;
            font-size: 14px;
            color: #374151;
            line-height: 1.6;
        }

        .action-item:last-child {
            margin-bottom: 0;
        }

        .action-item:before {
            content: "•";
            position: absolute;
            left: 8px;
            color: #0866FF;
            font-weight: bold;
        }

        .footer {
            padding: 32px 40px;
            font-size: 13px;
            color: #9ca3af;
            text-align: center;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }

        .footer a {
            color: #0866FF;
            text-decoration: none;
        }

        @media (max-width: 640px) {
            .content-wrapper {
                padding: 32px 24px;
            }
            .header {
                padding: 24px 24px 20px;
            }
            .footer {
                padding: 24px;
            }
            .details-row {
                flex-direction: column;
            }
            .details-label {
                width: 100%;
                margin-bottom: 4px;
            }
        }
    </style>
</head>

<body>
    <div class="email-wrapper">
        <div class="container">
            <div class="header">
                <img src="https://cdn.ordnary.com/site_assets/ordnary-black.png" alt="Ordnary" class="logo">
            </div>

            <div class="content-wrapper">
                <h1>Account Suspended</h1>
                <p class="subtitle">Your Ordnary account has been suspended due to multiple failed login attempts.</p>

                <div class="warning-box">
                    <p class="warning-text"><strong>Security Protection Activated</strong><br>We detected 6 consecutive failed login attempts on your account. As a security measure, your account has been automatically suspended to prevent unauthorized access.</p>
                </div>

                <div class="details-section">
                    <div class="details-header">Suspension Details</div>
                    <div class="details-content">
                        <div class="details-row">
                            <div class="details-label">Time:</div>
                            <div class="details-value">{{ $activityTime }}</div>
                        </div>
                        <div class="details-row">
                            <div class="details-label">IP Address:</div>
                            <div class="details-value">{{ $ipAddress }}</div>
                        </div>
                        <div class="details-row">
                            <div class="details-label">Device:</div>
                            <div class="details-value">{{ $deviceInfo }}</div>
                        </div>
                    </div>
                </div>

                <div class="action-section">
                    <div class="action-title">What You Should Do</div>
                    <div class="action-item">If these login attempts were made by you, contact our support team to restore your account access.</div>
                    <div class="action-item">If you don't recognize these attempts, someone may be trying to access your account. Please contact our security team immediately.</div>
                    <div class="action-item">Once your account is restored, we recommend enabling Two-Factor Authentication for additional security.</div>
                </div>

                <center>
                    <a href="mailto:support@ordnary.com" class="button">Contact Support</a>
                </center>

                <p style="margin-top: 32px; font-size: 14px; color: #6b7280;">For immediate assistance, email us at <strong>support@ordnary.com</strong> or <strong>security@ordnary.com</strong></p>
            </div>

            <div class="footer">
                <p style="margin: 0;">Security concerns? Contact us at <a href="mailto:security@ordnary.com">security@ordnary.com</a></p>
                <p style="margin: 8px 0 0 0;">&copy; {{ date('Y') }} Ordnary. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>
