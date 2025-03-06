{{-- resources/views/emails/password-reset.blade.php --}}
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wachtwoord Resetten - Numblio</title>
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
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border: 1px solid #e5e7eb;
        }
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 12px;
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
            margin-top: 40px;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
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
                <div class="header">
                    <img src="{{ asset('img/dark.png') }}" alt="Numblio Logo" class="logo">
                    <div class="header-divider"></div>
                    <span class="header-text">Wachtwoord Herstel</span>
                </div>

                <h2>Herstel uw wachtwoord</h2>
                <p class="subtitle">Volg onderstaande stappen om uw Numblio-account wachtwoord te resetten en weer toegang te krijgen tot al uw Numblio-diensten.</p>

                <div class="info-box">
                    <div class="info-title">
                        <span class="info-icon">ℹ️</span>
                        Wachtwoord reset aanvraag
                    </div>
                    <p class="info-text">U ontvangt deze e-mail omdat we een verzoek hebben ontvangen om het wachtwoord voor uw account te resetten. Deze link is 60 minuten geldig.</p>
                </div>

                <p>Klik op de onderstaande knop om uw wachtwoord te resetten:</p>

                <a href="{{ $resetUrl }}" class="button">Wachtwoord Resetten</a>

                <p class="message">Als u geen wachtwoord reset heeft aangevraagd, hoeft u geen verdere actie te ondernemen.</p>

                <p>Als u problemen heeft met de bovenstaande knop, kopieer en plak dan de onderstaande URL in uw webbrowser:</p>

                <div class="url-display">
                    {{ $resetUrl }}
                </div>

                <div class="help-section">
                    <div class="help-title">Hulp nodig?</div>
                    <p class="help-text">Als u vragen heeft of hulp nodig heeft bij het resetten van uw wachtwoord, neem dan contact op met onze klantenservice via <a href="mailto:support@numblio.com" style="color: #2563eb;">support@numblio.com</a>.</p>
                </div>

                <div class="footer">
                    <p>&copy; {{ date('Y') }} Numblio. Alle rechten voorbehouden.</p>
                    <div class="footer-links">
                        <a href="#" class="footer-link">Privacybeleid</a>
                        <a href="#" class="footer-link">Gebruiksvoorwaarden</a>
                        <a href="#" class="footer-link">Contact</a>
                    </div>
                </div>
            </div>
        </td>
    </tr>
</table>
</body>
</html>
