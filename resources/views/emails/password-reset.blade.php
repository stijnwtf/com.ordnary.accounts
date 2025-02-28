{{-- resources/views/emails/password-reset.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.5;
            color: #374151;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 24px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .logo {
            height: 32px;
            width: auto;
            margin-bottom: 12px;
        }
        h2 {
            font-size: 24px;
            font-weight: 700;
            color: #111827;
            margin-top: 0;
            margin-bottom: 16px;
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
        .warning {
            background-color: #fffbeb;
            border: 1px solid #fef3c7;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
        }
        .warning-title {
            font-weight: 500;
            color: #92400e;
            margin-bottom: 4px;
        }
        .warning-text {
            color: #b45309;
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
        }
        .footer {
            margin-top: 40px;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
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
        @media (max-width: 640px) {
            .container {
                padding: 24px 16px;
            }
        }
    </style>
</head>
<body>
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f9fafb" style="padding: 24px;">
    <tr>
        <td align="center">
            <div class="container">
                <img src="{{ asset('img/dark.png') }}" alt="Numblio Logo" class="logo">

                <h2>Reset Your Password</h2>

                <p>Log in again to your Numblio account and easily access all your Numblio services. One account for everything you need.</p>

                <div class="warning">
                    <div class="warning-title">Password Reset Request</div>
                    <p class="warning-text">You are receiving this email because we received a password reset request for your account. This link will expire in 60 minutes.</p>
                </div>

                <p>Please click the button below to reset your password:</p>

                <a href="{{ $resetUrl }}" class="button">Reset Password</a>

                <p class="message">If you did not request a password reset, no further action is required.</p>

                <p>If you're having trouble with the button above, copy and paste the URL below into your web browser:</p>

                <div class="url-display">
                    {{ $resetUrl }}
                </div>

                <div class="footer">
                    <p>&copy; 2025 Numblio. All rights reserved.</p>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Privacy Policy</a>
                        <a href="#" class="footer-link">Terms of Service</a>
                        <a href="#" class="footer-link">Contact</a>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
