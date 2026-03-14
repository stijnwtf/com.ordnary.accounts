## Overzicht

Ordnary Single Sign-On (SSO) is een gecentreerd authenticatiesysteem dat gebruikers in staat stelt zich aan te melden bij meerdere applicaties met één set inloggegevens. Dit document beschrijft hoe je de Ordnary SSO kunt integreren in je applicatie.

## Hoe SSO Werkt

### 1. Authenticatie Flow

```
Gebruiker → Jouw App → Ordnary SSO → Authenticatie → Token → Jouw App → Ingelogd
```

### 2. Gedetailleerde Stappen

1. **Initiële Redirect**: Gebruiker wordt doorgestuurd naar Ordnary SSO met `client_id` en `redirect_uri`
2. **Authenticatie**: Gebruiker logt in bij Ordnary (inclusief 2FA indien ingeschakeld)
3. **Token Generatie**: Ordnary genereert een veilige token
4. **Callback**: Gebruiker wordt teruggestuurd naar jouw app met de token
5. **Token Validatie**: Jouw app valideert de token bij Ordnary
6. **Gebruiker Data**: Bij succesvolle validatie ontvang je gebruikersgegevens

## Client Registratie

### 1. Een Client Aanmaken

In de Ordnary SSO admin panel moet je een client registreren met:

- **Naam**: Naam van je applicatie
- **Client ID**: Unieke identifier (wordt automatisch gegenereerd)
- **Client Secret**: Geheime sleutel voor API calls
- **Redirect URIs**: Toegestane callback URLs
- **Webhook URL**: (Optioneel) Voor notificaties
- **Logout URL**: URL voor centraal uitloggen

### 2. Voorbeeld Client Configuration

```json
{
  "name": "Mijn Applicatie",
  "client_id": "abc123xyz789",
  "client_secret": "super_geheime_sleutel_hier",
  "redirect_uris": [
    "https://mijnapplicatie.nl/auth/callback",
    "https://staging.mijnapplicatie.nl/auth/callback"
  ],
  "logout_url": "https://mijnapplicatie.nl/auth/logout"
}
```

## Implementatie

### 1. Login URL Genereren

Stuur gebruikers naar deze URL om het SSO proces te starten:

```
https://accounts.ordnary.com/login?client_id={CLIENT_ID}&redirect_uri={REDIRECT_URI}
```

**Parameters:**
- `client_id`: Je geregistreerde client ID
- `redirect_uri`: URL waar gebruiker naartoe wordt gestuurd na authenticatie (moet geregistreerd zijn)

### 2. Callback Afhandelen

Nadat de gebruiker is geauthenticeerd, wordt deze teruggestuurd naar je `redirect_uri` met een token:

```
https://mijnapplicatie.nl/auth/callback?token=abc123xyz789token
```

### 3. Token Validatie

Valideer de ontvangen token door een POST request te maken naar:

```
POST https://accounts.ordnary.com/api/v1/validate-token
```

**Headers:**
```
Content-Type: application/json
```

**Body:**
```json
{
  "token": "ontvangen_token_hier",
  "client_id": "jouw_client_id",
  "client_secret": "jouw_client_secret"
}
```

**Succesvolle Response:**
```json
{
  "success": true,
  "user": {
    "id": 123,
    "name": "Jan Jansen",
    "email": "jan@example.com",
    "is_admin": false
  }
}
```

**Foutieve Response:**
```json
{
  "success": false,
  "message": "Invalid or expired token."
}
```

### 4. HTTP Status Codes

- `200`: Token is geldig
- `401`: Ongeldige client credentials of token
- `404`: Gebruiker niet gevonden
- `419`: Token is verlopen

## Security Overwegingen

### 1. Token Beveiliging

- Tokens zijn SHA256 gehashed in de database
- Tokens hebben een beperkte levensduur
- Tokens zijn uniek per client/gebruiker combinatie

### 2. Client Secret

- Bewaar je client secret veilig (environment variabelen)
- Deel de client secret nooit in frontend code
- Roteer de secret regelmatig

### 3. Redirect URI Validatie

- Alleen geregistreerde redirect URIs worden geaccepteerd
- Gebruik HTTPS voor alle redirect URIs
- Valideer altijd de `state` parameter indien gebruikt

### 4. HTTPS Vereist

- Alle communicatie moet via HTTPS verlopen
- Ordnary accepteert geen HTTP callbacks (behalve localhost voor development)

## Features

### 1. Two-Factor Authentication (2FA)

Ordnary ondersteunt 2FA met:
- Google Authenticator / Authy
- SMS (indien geconfigureerd)
- Recovery codes

### 2. Email Verificatie

- Gebruikers moeten hun email verifiëren
- Recovery email optie beschikbaar

### 3. Centraal Uitloggen

- Single logout functionaliteit
- Alle sessies worden beëindigd

### 4. Activity Logging

- Alle login activiteiten worden gelogd
- IP adressen en timestamps worden bijgehouden

## Laravel Implementatie Voorbeeld

### 1. Environment Configuratie

```env
ORDNARY_SSO_URL=https://accounts.ordnary.com
ORDNARY_CLIENT_ID=jouw_client_id_hier
ORDNARY_CLIENT_SECRET=jouw_client_secret_hier
ORDNARY_REDIRECT_URI=https://mijnapplicatie.nl/auth/callback
```

### 2. Routes

```php
// routes/web.php
Route::get('/auth/login', [SSOController::class, 'redirectToSSO'])->name('sso.login');
Route::get('/auth/callback', [SSOController::class, 'handleCallback'])->name('sso.callback');
Route::post('/auth/logout', [SSOController::class, 'logout'])->name('sso.logout');
```

### 3. Controller Implementatie

Zie de meegeleverde `SSOController.php` voor een complete implementatie.

### 4. Middleware voor Authenticatie

```php
// app/Http/Middleware/SSOAuth.php
public function handle($request, Closure $next)
{
    if (!session('user_authenticated')) {
        return redirect()->route('sso.login');
    }
    
    return $next($request);
}
```

## Testing

### 1. Local Development

Voor lokale ontwikkeling kun je localhost gebruiken:

```
redirect_uri=http://localhost:8000/auth/callback
```

### 2. Staging Environment

Registreer je staging URLs in de client configuratie:

```
redirect_uri=https://staging.mijnapplicatie.nl/auth/callback
```

## Error Handling

### 1. Veel Voorkomende Fouten

**Invalid redirect_uri:**
- Controleer of de URI exact overeenkomt met geregistreerde URIs
- Let op trailing slashes

**Token validation failed:**
- Controleer client_id en client_secret
- Controleer of token niet verlopen is
- Controleer netwerk connectiviteit

**Client not found:**
- Verifieer client_id spelling
- Controleer of client actief is

### 2. Logging

Log alle SSO interacties voor debugging:

```php
Log::info('SSO token validation', [
    'token' => $token,
    'client_id' => $clientId,
    'success' => $success
]);
```

## Productie Checklist

- [ ] Client geregistreerd in productie environment
- [ ] HTTPS geconfigureerd voor alle endpoints
- [ ] Client secret veilig opgeslagen
- [ ] Error handling geïmplementeerd
- [ ] Logging geconfigureerd
- [ ] Fallback mechanisme voor SSO downtime
- [ ] Session management geïmplementeerd
- [ ] Logout functionaliteit getest

## API Reference

### Validate Token Endpoint

**URL:** `POST /api/v1/validate-token`

**Parameters:**
- `token` (string, required): Token ontvangen van callback
- `client_id` (string, required): Jouw client ID
- `client_secret` (string, required): Jouw client secret

**Response Format:**
```json
{
  "success": boolean,
  "user": {
    "id": integer,
    "name": "string",
    "email": "string",
    "is_admin": boolean
  },
  "message": "string" // alleen bij errors
}
```

## Support en Contact

Voor vragen over de SSO integratie:

- **Support:** support@ordnary.com
- **Documentatie:** https://docs.ordnary.com/sso
- **Status:** https://status.ordnary.com

## Changelog

### v1.0.0
- Initiele release
- Token validatie API
- 2FA ondersteuning
- Centraal uitloggen

### v1.1.0
- Recovery codes toegevoegd
- Enhanced logging
- Performance verbeteringen

---

## License

Copyright (c) 2026 7txr

All rights reserved.

This repository is provided for viewing and educational purposes only.
You may NOT copy, modify, distribute, or use any part of this code without explicit permission.
