{{-- resources/views/emails/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welkom bij Numblio</title>
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
                    <span class="header-text">Welkomstbericht</span>
                </div>

                <h2>Welkom bij Numblio, {{ $user->name }}!</h2>
                <p class="subtitle">Bedankt voor het aanmaken van uw Numblio-account. U heeft nu toegang tot alle Numblio-diensten met één account.</p>

                <div class="success-box">
                    <div class="success-title">
                        <span class="success-icon">✓</span>
                        Account succesvol aangemaakt
                    </div>
                    <p class="success-text">Uw account is klaar voor gebruik. U kunt nu inloggen bij alle Numblio-diensten met uw e-mailadres en wachtwoord.</p>
                </div>

                <p>Klik op de onderstaande knop om naar uw account te gaan:</p>

                <a href="{{ url('/my-account') }}" class="button">Naar Mijn Account</a>

                <div class="features-section">
                    <div class="feature">
                        <div class="feature-icon">🔐</div>
                        <div class="feature-content">
                            <div class="feature-title">Single Sign-On</div>
                            <p class="feature-description">Log eenvoudig in bij alle Numblio-diensten met één account. Geen meerdere wachtwoorden meer onthouden.</p>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">🔄</div>
                        <div class="feature-content">
                            <div class="feature-title">Naadloze integratie</div>
                            <p class="feature-description">Wissel moeiteloos tussen verschillende Numblio-diensten zonder opnieuw in te loggen.</p>
                        </div>
                    </div>
                    <div class="feature">
                        <div class="feature-icon">🛡️</div>
                        <div class="feature-content">
                            <div class="feature-title">Veilig en betrouwbaar</div>
                            <p class="feature-description">Uw gegevens zijn veilig bij ons. We gebruiken de nieuwste beveiligingstechnologieën om uw account te beschermen.</p>
                        </div>
                    </div>
                </div>

                <div class="help-section">
                    <div class="help-title">Hulp nodig?</div>
                    <p class="help-text">Als u vragen heeft of hulp nodig heeft met uw account, neem dan contact op met onze klantenservice via <a href="mailto:support@numblio.com" style="color: #2563eb;">support@numblio.com</a>.</p>
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