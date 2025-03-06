{{-- resources/views/emails/security-alert.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Alert - Numblio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.5;
            color: #374151;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .email-wrapper {
            padding: 24px;
            background-color: #f9fafb;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }
        .content-wrapper {
            padding: 40px 24px;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }
        .logo {
            height: 32px;
            width: auto;
            margin-right: 12px;
        }
        .header-divider {
            height: 24px;
            width: 1px;
            background-color: #d1d5db;
            margin: 0 12px;
        }
        .header-text {
            font-size: 14px;
            color: #6b7280;
        }
        h2 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-top: 0;
            margin-bottom: 8px;
        }
        .subtitle {
            font-size: 16px;
            color: #4b5563;
            margin-bottom: 24px;
        }
        p {
            font-size: 16px;
            margin-bottom: 16px;
            color: #4b5563;
        }
        .message {
            font-size: 14px;
            color: #6b7280;
        }
        .button {
            display: inline-block;
            background-color: #2563eb;
            color: white !important;
            text-decoration: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 500;
            margin: 24px 0;
            transition: background-color 0.2s;
        }
        .button:hover {
            background-color: #1d4ed8;
        }
        .warning-box {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
        }
        .warning-title {
            font-weight: 500;
            color: #991b1b;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
        }
        .warning-icon {
            margin-right: 8px;
            color: #ef4444;
            width: 16px;
            text-align: center;
        }
        .warning-text {
            color: #991b1b;
            font-size: 14px;
            margin: 0;
        }
        .details-section {
            margin: 32px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        .details-header {
            background-color: #f3f4f6;
            padding: 12px 16px;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-content {
            padding: 16px;
        }
        .details-row {
            display: flex;
            margin-bottom: 8px;
        }
        .details-row:last-child {
            margin-bottom: 0;
        }
        .details-label {
            width: 140px;
            font-weight: 500;
            color: #4b5563;
            flex-shrink: 0;
        }
        .details-value {
            flex: 1;
            color: #111827;
        }
        .action-section {
            margin: 32px 0;
        }
        .action-title {
            font-weight: 600;
            font-size: 18px;
            color: #374151;
            margin-bottom: 12px;
        }
        .action-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-left: 24px;
            position: relative;
        }
        .action-item:before {
            content: "";
            position: absolute;
            left: 8px;
            top: 8px;
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #2563eb;
        }
        .action-text {
            font-size: 14px;
            color: #4b5563;
        }
        .help-section {
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }
        .help-title {
            font-weight: 600;
            font-size: 16px;
            color: #374151;
            margin-bottom: 8px;
        }
        .help-text {
            font-size: 14px;
            color: #6b7280;
        }
        .footer-wrapper {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }
        .footer {
            padding: 24px;
            font-size: 14px;
            color: #6b7280;
            text-align: center;
        }
        .footer-links {
            margin-top: 8px;
        }
        .footer-link {
            color: #4b5563;
            text-decoration: none;
            margin-right: 16px;
        }
        .footer-link:hover {
            color: #1f2937;
        }
        .bottom-spacer {
            height: 24px;
            background-color: #f9fafb;
        }
        @media (max-width: 640px) {
            .content-wrapper {
                padding: 24px 16px;
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
        <div class="content-wrapper">
            <div class="header">
                <img src="{{ asset('img/dark.png') }}" alt="Numblio Logo" class="logo">
                <div class="header-divider"></div>
                <span class="header-text">Security Alert</span>
            </div>

            <h2>Security Alert</h2>
            <p class="subtitle">We have detected unusual activity on your Numblio account. Please review the details below and take action if necessary.</p>

            <div class="warning-box">
                <div class="warning-title">
                    <i class="fas fa-exclamation-triangle warning-icon"></i>
                    {{ $alertTitle }}
                </div>
                <p class="warning-text">{{ $alertMessage }}</p>
            </div>

            <div class="details-section">
                <div class="details-header">Activity Details</div>
                <div class="details-content">
                    <div class="details-row">
                        <div class="details-label">Time:</div>
                        <div class="details-value">{{ $activityTime }}</div>
                    </div>
                    <div class="details-row">
                        <div class="details-label">Location:</div>
                        <div class="details-value">{{ $activityLocation }}</div>
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
                <div class="action-title">Recommended Actions</div>
                <div class="action-item">
                    <div class="action-text">If you recognize this activity, no action is needed.</div>
                </div>
                <div class="action-item">
                    <div class="action-text">If you don't recognize this activity, we recommend changing your password immediately and terminating all active sessions.</div>
                </div>
                <div class="action-item">
                    <div class="action-text">Check your account settings for any unauthorized changes.</div>
                </div>
            </div>

            <a href="{{ url('/my-account') }}" class="button">Go to My Account</a>

            <div class="help-section">
                <div class="help-title">Need Help?</div>
                <p class="help-text">If you don't recognize this activity or have questions about your account security, please contact our security team immediately at <a href="mailto:security@numblio.com" style="color: #2563eb;">security@numblio.com</a>.</p>
            </div>
        </div>

        <div class="footer-wrapper">
            <div class="footer">
                <p>&copy; {{ date('Y') }} Numblio. All rights reserved.</p>
                <div class="footer-links">
                    <a href="#" class="footer-link">Privacy Policy</a>
                    <a href="#" class="footer-link">Terms of Service</a>
                    <a href="#" class="footer-link">Contact</a>
                </div>
            </div>
        </div>
    </div>
    <div class="bottom-spacer"></div>
</div>
</body>
</html> 