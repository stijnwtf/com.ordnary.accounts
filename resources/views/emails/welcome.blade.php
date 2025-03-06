{{-- resources/views/emails/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Numblio</title>
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
        .success-box {
            background-color: #f0fdf4;
            border: 1px solid #dcfce7;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
        }
        .success-title {
            font-weight: 500;
            color: #166534;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
        }
        .success-icon {
            margin-right: 8px;
            color: #22c55e;
            width: 16px;
            text-align: center;
        }
        .success-text {
            color: #166534;
            font-size: 14px;
            margin: 0;
        }
        .features-section {
            margin: 32px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        .feature {
            padding: 16px;
            display: flex;
            align-items: flex-start;
            border-bottom: 1px solid #e5e7eb;
        }
        .feature:last-child {
            border-bottom: none;
        }
        .feature-icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            color: #2563eb;
            text-align: center;
        }
        .feature-content {
            flex: 1;
        }
        .feature-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }
        .feature-description {
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
                <span class="header-text">Welcome Message</span>
            </div>

            <h2>Welcome to Numblio, {{ $user->name }}!</h2>
            <p class="subtitle">Thank you for creating your Numblio account. You now have access to all Numblio services with a single account.</p>

            <div class="success-box">
                <div class="success-title">
                    <i class="fas fa-check-circle success-icon"></i>
                    Account Successfully Created
                </div>
                <p class="success-text">Your account is ready to use. You can now log in to all Numblio services with your email and password.</p>
            </div>

            <p>Click the button below to go to your account:</p>

            <a href="{{ url('/my-account') }}" class="button">Go to My Account</a>

            <div class="features-section">
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-lock"></i>
                    </div>
                    <div class="feature-content">
                        <div class="feature-title">Single Sign-On</div>
                        <p class="feature-description">Easily log in to all Numblio services with one account. No more remembering multiple passwords.</p>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <div class="feature-content">
                        <div class="feature-title">Seamless Integration</div>
                        <p class="feature-description">Switch effortlessly between different Numblio services without logging in again.</p>
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <div class="feature-content">
                        <div class="feature-title">Secure and Reliable</div>
                        <p class="feature-description">Your data is safe with us. We use the latest security technologies to protect your account.</p>
                    </div>
                </div>
            </div>

            <div class="help-section">
                <div class="help-title">Need Help?</div>
                <p class="help-text">If you have any questions or need assistance with your account, please contact our support team at <a href="mailto:support@numblio.com" style="color: #2563eb;">support@numblio.com</a>.</p>
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