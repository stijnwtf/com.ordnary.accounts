{{-- resources/views/emails/verify-email.blade.php --}}
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifieer uw e-mailadres - Numblio</title>
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
        .steps-section {
            margin: 32px 0;
        }
        .step {
            display: flex;
            margin-bottom: 16px;
        }
        .step-number {
            width: 24px;
            height: 24px;
            background-color: #2563eb;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .step-content {
            flex: 1;
        }
        .step-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 4px;
        }
        .step-description {
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
                    <span class="header-text">E-mailverificatie</span>
                </div>

                <h2>Verifieer uw e-mailadres</h2>
                <p class="subtitle">Bedankt voor het registreren bij Numblio. Verifieer uw e-mailadres om uw account te activeren en toegang te krijgen tot alle functies.</p>

                <div class="info-box">
                    <div class="info-title">
                        <span class="info-icon">ℹ️</span>
                        Waarom moet ik mijn e-mail verifiëren?
                    </div>
                    <p class="info-text">E-mailverificatie helpt ons om te bevestigen dat u de eigenaar bent van dit e-mailadres en beschermt uw account tegen ongeautoriseerde toegang.</p>
                </div>

                <p>Klik op de onderstaande knop om uw e-mailadres te verifiëren:</p>

                <a href="{{ $verificationUrl }}" class="button">E-mailadres verifiëren</a>

                <p class="message">Deze verificatielink is 24 uur geldig. Als de link is verlopen, kunt u inloggen op uw account om een nieuwe verificatielink aan te vragen.</p>

                <p>Als u problemen heeft met de bovenstaande knop, kopieer en plak dan de onderstaande URL in uw webbrowser:</p>

                <div class="url-display">
                    {{ $verificationUrl }}
                </div>

                <div class="steps-section">
                    <div class="step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <div class="step-title">Verifieer uw e-mailadres</div>
                            <p class="step-description">Klik op de verificatielink hierboven om uw e-mailadres te bevestigen.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <div class="step-title">Log in op uw account</div>
                            <p class="step-description">Na verificatie kunt u inloggen met uw e-mailadres en wachtwoord.</p>
                        </div>
                    </div>
                    <div class="step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <div class="step-title">Geniet van alle Numblio-diensten</div>
                            <p class="step-description">Krijg toegang tot alle Numblio-diensten met één account.</p>
                        </div>
                    </div>
                </div>

                <div class="help-section">
                    <div class="help-title">Hulp nodig?</div>
                    <p class="help-text">Als u deze e-mail niet heeft aangevraagd of hulp nodig heeft, neem dan contact op met onze klantenservice via <a href="mailto:support@numblio.com" style="color: #2563eb;">support@numblio.com</a>.</p>
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