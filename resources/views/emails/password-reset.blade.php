{{-- resources/views/emails/password-reset.blade.php --}}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reset Your Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            background-color: #3b82f6;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 4px;
            margin: 20px 0;
        }
        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
<img src="{{ asset('img/dark.png') }}" alt="Numblio Logo" class="logo">

<h2>Reset Your Password</h2>

<p>Hello,</p>

<p>You are receiving this email because we received a password reset request for your account.</p>

<p>Please click the button below to reset your password. This link will expire in 60 minutes.</p>

<a href="{{ $resetUrl }}" class="button">Reset Password</a>

<p>If you did not request a password reset, no further action is required.</p>

<p>If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:</p>

<p>{{ $resetUrl }}</p>

<div class="footer">
    <p>&copy; 2025 Numblio. All rights reserved.</p>
    <p>If you have any questions, please contact our support team.</p>
</div>
</body>
</html>
