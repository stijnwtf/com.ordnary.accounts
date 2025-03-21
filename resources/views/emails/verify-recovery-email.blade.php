<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Recovery Email | Numblio</title>
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
        .info-box {
            background-color: #eff6ff;
            border: 1px solid #dbeafe;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
        }
        .info-title {
            font-weight: 500;
            color: #1e40af;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
        }
        .info-icon {
            margin-right: 8px;
            color: #3b82f6;
            width: 16px;
            text-align: center;
        }
        .info-text {
            color: #1e40af;
            font-size: 14px;
            margin: 0;
        }
        .url-display {
            padding: 12px;
            background-color: #f3f4f6;
            border-radius: 6px;
            font-size: 14px;
            word-break: break-all;
            margin: 16px 0;
            border: 1px solid #e5e7eb;
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
        .footer {
            padding: 24px;
            font-size: 14px;
            color: #6b7280;
            text-align: center;
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
        }
        .icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 8px;
            vertical-align: middle;
        }
        @media (max-width: 640px) {
            .content-wrapper {
                padding: 24px 16px;
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
            </div>

            <h2>Verify Your Recovery Email</h2>
            <p class="subtitle">Please verify your recovery email address to ensure you can regain access to your account if needed.</p>

            <div class="info-box">
                <div class="info-title">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#3b82f6">
                        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/>
                    </svg>
                    Email Verification Required
                </div>
                <p class="info-text">You have added a recovery email to your Numblio account. Please verify this email address by clicking the button below. This link will expire in 24 hours.</p>
            </div>

            <p>Click the button below to verify your recovery email:</p>

            <a href="{{ url('/account/security/verify-recovery-email/' . $token) }}" class="button">Verify Recovery Email</a>

            <p class="message">If you did not add this recovery email, no further action is required.</p>

            <p>If you're having trouble with the button above, copy and paste the URL below into your web browser:</p>

            <div class="url-display">
                {{ url('/account/security/verify-recovery-email/' . $token) }}
            </div>

            <div class="help-section">
                <div class="help-title">Need Help?</div>
                <p class="help-text">If you have any questions or need assistance with your account, please contact our support team at <a href="mailto:support@numblio.com" style="color: #2563eb;">support@numblio.com</a>.</p>
            </div>
        </div>
    </div>
</div>
</body>
</html> 