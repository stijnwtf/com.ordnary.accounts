{{-- resources/views/emails/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Ordnary | Your Account Details</title>
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

        .success-box {
            background-color: #f0fdf4;
            border-left: 3px solid #22c55e;
            border-radius: 8px;
            padding: 16px 20px;
            margin: 24px 0;
        }

        .success-text {
            color: #166534;
            font-size: 14px;
            margin: 0;
            line-height: 1.6;
        }

        .features-section {
            margin: 32px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .feature {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }

        .feature:last-child {
            border-bottom: none;
        }

        .feature-title {
            font-weight: 600;
            color: #111827;
            margin: 0 0 8px 0;
            font-size: 16px;
        }

        .feature-description {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
            line-height: 1.5;
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
                <h1>Welcome, {{ $user->name }}!</h1>
                <p class="subtitle">Thank you for creating your Ordnary account. You now have access to all Ordnary services with a single account.</p>

                <div class="success-box">
                    <p class="success-text">Your account is ready to use. You can now log in to all Ordnary services with your email and password.</p>
                </div>

                <p>Click the button below to get started:</p>

                <center>
                    <a href="{{ url('/app') }}" class="button">Go to My Account</a>
                </center>

                <div class="features-section">
                    <div class="feature">
                        <div class="feature-title">Single Sign-On</div>
                        <p class="feature-description">Easily log in to all Ordnary services with one account. No more remembering multiple passwords.</p>
                    </div>
                    <div class="feature">
                        <div class="feature-title">Seamless Integration</div>
                        <p class="feature-description">Switch effortlessly between different Ordnary services without logging in again.</p>
                    </div>
                    <div class="feature">
                        <div class="feature-title">Secure and Reliable</div>
                        <p class="feature-description">Your data is safe with us. We use the latest security technologies to protect your account.</p>
                    </div>
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
