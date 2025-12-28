{{-- resources/views/emails/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address - Ordnary</title>
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

        .info-box {
            background-color: #eff6ff;
            border-left: 3px solid #0866FF;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 24px 0;
        }

        .info-text {
            color: #1e40af;
            font-size: 14px;
            margin: 0;
            line-height: 1.6;
        }

        .url-display {
            padding: 16px;
            background-color: #f9fafb;
            border-radius: 8px;
            font-size: 13px;
            word-break: break-all;
            margin: 16px 0;
            border: 1px solid #e5e7eb;
            color: #6b7280;
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
                <h1>Verify Your Email Address</h1>
                <p class="subtitle">Thank you for registering with Ordnary. Please verify your email address to activate your account.</p>

                <div class="info-box">
                    <p class="info-text">Email verification helps us confirm that you are the owner of this email address and protects your account from unauthorized access. This verification link is valid for 24 hours.</p>
                </div>

                <p>Click the button below to verify your email address:</p>

                <center>
                    <a href="{{ $verificationUrl }}" class="button">Verify Email Address</a>
                </center>

                <p style="color: #6b7280; font-size: 14px;">If the link has expired, you can log in to your account to request a new verification link.</p>

                <p style="margin-top: 32px; font-size: 14px; color: #6b7280;">If the button doesn't work, copy and paste this URL into your browser:</p>

                <div class="url-display">
                    {{ $verificationUrl }}
                </div>
            </div>

            <div class="footer">
                <p style="margin: 0;">Need help? Contact us at <a href="mailto:support@ordnary.com">support@ordnary.com</a></p>
                <p style="margin: 8px 0 0 0;">&copy; {{ date('Y') }} Ordnary. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>

</html>
