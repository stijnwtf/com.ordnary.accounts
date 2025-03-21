<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verify Recovery Email</title>
</head>
<body>
    <h2>Verify Your Recovery Email</h2>
    <p>You have added a recovery email to your Numblio account. To verify this email address, please click the link below:</p>
    <p><a href="{{ url('/account/security/verify-recovery-email/' . $token) }}">Verify Recovery Email</a></p>
    <p>If you did not add this recovery email, please ignore this message.</p>
    <p>This link will expire in 24 hours.</p>
    <p>Best regards,<br>The Numblio Team</p>
</body>
</html> 