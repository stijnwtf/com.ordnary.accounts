{{-- resources/views/emails/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Numblio</title>
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
        .icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            margin-right: 8px;
            vertical-align: middle;
        }
        .feature-svg {
            width: 24px;
            height: 24px;
            margin-right: 12px;
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
                    <svg class="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#22c55e">
                        <path d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"/>
                    </svg>
                    Account Successfully Created
                </div>
                <p class="success-text">Your account is ready to use. You can now log in to all Numblio services with your email and password.</p>
            </div>

            <p>Click the button below to go to your account:</p>

            <a href="{{ url('/my-account') }}" class="button">Go to My Account</a>

            <div class="features-section">
                <div class="feature">
                    <svg class="feature-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="#2563eb">
                        <path d="M144 144v48H304V144c0-44.2-35.8-80-80-80s-80 35.8-80 80zM80 192V144C80 64.5 144.5 0 224 0s144 64.5 144 144v48h16c35.3 0 64 28.7 64 64V448c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V256c0-35.3 28.7-64 64-64H80z"/>
                    </svg>
                    <div class="feature-content">
                        <div class="feature-title">Single Sign-On</div>
                        <p class="feature-description">Easily log in to all Numblio services with one account. No more remembering multiple passwords.</p>
                    </div>
                </div>
                <div class="feature">
                    <svg class="feature-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#2563eb">
                        <path d="M142.9 142.9c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5c0 0 0 0 0 0H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5c7.7-21.8 20.2-42.3 37.8-59.8zM16 312v7.6 .7V440c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l41.6-41.6c87.6 86.5 228.7 86.2 315.8-1c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.2 62.2-162.7 62.5-225.3 1L185 329c6.9-6.9 8.9-17.2 5.2-26.2s-12.5-14.8-22.2-14.8H48.4h-.7H40c-13.3 0-24 10.7-24 24z"/>
                    </svg>
                    <div class="feature-content">
                        <div class="feature-title">Seamless Integration</div>
                        <p class="feature-description">Switch effortlessly between different Numblio services without logging in again.</p>
                    </div>
                </div>
                <div class="feature">
                    <svg class="feature-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="#2563eb">
                        <path d="M256 0c4.6 0 9.2 1 13.4 2.9L457.7 82.8c22 9.3 38.4 31 38.3 57.2c-.5 99.2-41.3 280.7-213.6 363.2c-16.7 8-36.1 8-52.8 0C57.3 420.7 16.5 239.2 16 140c-.1-26.2 16.3-47.9 38.3-57.2L242.7 2.9C246.8 1 251.4 0 256 0z"/>
                    </svg>
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