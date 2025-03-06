{{-- resources/views/emails/security-alert.blade.php --}}
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beveiligingswaarschuwing - Numblio</title>
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
        .warning-box {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 8px;
            padding: 16px;
            margin: 24px 0;
        }
        .warning-title {
            font-weight: 500;
            color: #991b1b;
            margin-bottom: 4px;
            display: flex;
            align-items: center;
        }
        .warning-icon {
            margin-right: 8px;
            color: #ef4444;
        }
        .warning-text {
            color: #991b1b;
            font-size: 14px;
            margin: 0;
        }
        .details-section {
            margin: 32px 0;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        .details-header {
            background-color: #f3f4f6;
            padding: 12px 16px;
            font-weight: 600;
            color: #374151;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-content {
            padding: 16px;
        }
        .details-row {
            display: flex;
            margin-bottom: 8px;
        }
        .details-row:last-child {
            margin-bottom: 0;
        }
        .details-label {
            width: 140px;
            font-weight: 500;
            color: #4b5563;
            flex-shrink: 0;
        }
        .details-value {
            flex: 1;
            color: #111827;
        }
        .action-section {
            margin: 32px 0;
        }
        .action-title {
            font-weight: 600;
            font-size: 18px;
            color: #374151;
            margin-bottom: 12px;
        }
        .action-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-left: 24px;
            position: relative;
        }
        .action-item:before {
            content: "•";
            position: absolute;
            left: 8px;
            color: #2563eb;
            font-weight: bold;
        }
        .action-text {
            font-size: 14px;
            color: #4b5563;
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
            .details-row {
                flex-direction: column;
            }
            .details-label {
                width: 100%;
                margin-bottom: 4px;
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
                    <span class="header-text">Beveiligingswaarschuwing</span>
                </div>

                <h2>Beveiligingswaarschuwing</h2>
                <p class="subtitle">We hebben ongebruikelijke activiteit gedetecteerd op uw Numblio-account. Controleer de details hieronder en neem indien nodig actie.</p>

                <div class="warning-box">
                    <div class="warning-title">
                        <span class="warning-icon">⚠️</span>
                        {{ $alertTitle }}
                    </div>
                    <p class="warning-text">{{ $alertMessage }}</p>
                </div>

                <div class="details-section">
                    <div class="details-header">Activiteitsdetails</div>
                    <div class="details-content">
                        <div class="details-row">
                            <div class="details-label">Tijdstip:</div>
                            <div class="details-value">{{ $activityTime }}</div>
                        </div>
                        <div class="details-row">
                            <div class="details-label">Locatie:</div>
                            <div class="details-value">{{ $activityLocation }}</div>
                        </div>
                        <div class="details-row">
                            <div class="details-label">IP-adres:</div>
                            <div class="details-value">{{ $ipAddress }}</div>
                        </div>
                        <div class="details-row">
                            <div class="details-label">Apparaat:</div>
                            <div class="details-value">{{ $deviceInfo }}</div>
                        </div>
                    </div>
                </div>

                <div class="action-section">
                    <div class="action-title">Aanbevolen acties</div>
                    <div class="action-item">
                        <div class="action-text">Als u deze activiteit herkent, hoeft u niets te doen.</div>
                    </div>
                    <div class="action-item">
                        <div class="action-text">Als u deze activiteit niet herkent, raden we u aan om onmiddellijk uw wachtwoord te wijzigen en alle actieve sessies te beëindigen.</div>
                    </div>
                    <div class="action-item">
                        <div class="action-text">Controleer uw accountinstellingen op ongeautoriseerde wijzigingen.</div>
                    </div>
                </div>

                <a href="{{ url('/my-account') }}" class="button">Ga naar Mijn Account</a>

                <div class="help-section">
                    <div class="help-title">Hulp nodig?</div>
                    <p class="help-text">Als u deze activiteit niet herkent of vragen heeft over de beveiliging van uw account, neem dan onmiddellijk contact op met onze beveiligingsafdeling via <a href="mailto:security@numblio.com" style="color: #2563eb;">security@numblio.com</a>.</p>
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