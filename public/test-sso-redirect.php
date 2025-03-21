<?php
// This script simulates a client-side redirect to the SSO login page with a malformed URL
// It will automatically redirect to the SSO login page with the client_id parameter containing the redirect_uri
?>
<!DOCTYPE html>
<html>
<head>
    <title>SSO Redirect Test</title>
    <script>
        // Redirect to login page with a malformed URL (using ? instead of &)
        window.location.href = '/login?client_id=nHo7VcwaRAblS7Iic0s491GVyQQ0gL14?redirect_uri=https://classconnect.nl/sso/callback';
    </script>
</head>
<body>
    <p>Redirecting to SSO login page with malformed URL...</p>
</body>
</html> 