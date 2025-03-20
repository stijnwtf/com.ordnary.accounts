{{-- resources/views/emails/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address - Numblio</title>
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
        .steps-section {
            margin: 32px 0;
        }
        .step {
            display: flex;
            margin-bottom: 16px;
        }
        .step-number {
            width: 24px;
            height: 24px;
            background-color: #2563eb;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .step-content {
            flex: 1;
        }
        .step-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }
        .step-description {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
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
                <div class="header-divider"></div>
                <span class="header-text">Email Verification</span>
            </div>

            <h2>Verify Your Email Address</h2>
            <p class="subtitle">Thank you for registering with Numblio. Please verify your email address to activate your account and gain access to all features.</p>

            <div class="info-box">
                <div class="info-title">
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#3b82f6">
                        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM216 336h24V272H216c-13.3 0-24-10.7-24-24s10.7-24 24-24h48c13.3 0 24 10.7 24 24v88h8c13.3 0 24 10.7 24 24s-10.7 24-24 24H216c-13.3 0-24-10.7-24-24s10.7-24 24-24zm40-208a32 32 0 1 1 0 64 32 32 0 1 1 0-64z"/>
                    </svg>
                    Why do I need to verify my email?
                </div>
                <p class="info-text">Email verification helps us confirm that you are the owner of this email address and protects your account from unauthorized access.</p>
            </div>

            <p>Please click the button below to verify your email address:</p>

            <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>

            <p class="message">This verification link is valid for 24 hours. If the link has expired, you can log in to your account to request a new verification link.</p>

            <p>If you're having trouble with the button above, copy and paste the URL below into your web browser:</p>

            <div class="url-display">
                {{ $verificationUrl }}
            </div>

            <div class="steps-section">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <div class="step-title">Verify your email address</div>
                        <p class="step-description">Click the verification link above to confirm your email address.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <div class="step-title">Log in to your account</div>
                        <p class="step-description">After verification, you can log in with your email and password.</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <div class="step-title">Enjoy all Numblio services</div>
                        <p class="step-description">Access all Numblio services with a single account.</p>
                    </div>
                </div>
            </div>

            <div class="help-section">
                <div class="help-title">Need Help?</div>
                <p class="help-text">If you did not request this email or need assistance, please contact our support team at <a href="mailto:support@numblio.com" style="color: #2563eb;">support@numblio.com</a>.</p>
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