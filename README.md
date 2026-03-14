<div align="center">

# Ordnary Accounts

**Self-hosted Single Sign-On server for the Ordnary ecosystem**

[![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white)](https://www.php.net/)
[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com/)
[![TailwindCSS](https://img.shields.io/badge/TailwindCSS-3.x-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)](https://tailwindcss.com/)
[![Vite](https://img.shields.io/badge/Vite-5.x-646CFF?style=flat-square&logo=vite&logoColor=white)](https://vitejs.dev/)
[![License](https://img.shields.io/badge/License-Proprietary-red?style=flat-square)](LICENSE)

One identity. Every Ordnary service.

[accounts.ordnary.com](https://accounts.ordnary.com) · [docs.ordnary.com/sso](https://docs.ordnary.com/sso) · [status.ordnary.com](https://status.ordnary.com)

</div>

---

## Overview

`com.ordnary.accounts` is the centralised authentication server for the Ordnary platform. It implements a custom token-based SSO flow, allowing users to authenticate once and access any connected Ordnary service without re-entering credentials.

**Key capabilities:**

- Token-based SSO across all registered client applications
- Two-Factor Authentication (TOTP via Google Authenticator / Authy)
- Email verification and account recovery
- Centralised single logout — one action terminates all sessions across all clients
- Client registration with per-client redirect URI whitelisting
- Activity logging: all login events, IPs, and timestamps are persisted
- Token validation REST API consumed by downstream services (`com.ordnary.developers`, etc.)

---

## How SSO Works

```
┌──────────┐      1. redirect to SSO       ┌─────────────────────┐
│  User    │ ─────────────────────────────► │  accounts.ordnary   │
│          │      (client_id, redirect_uri) │  .com               │
│          │                                │                     │
│          │      2. authenticates (+ 2FA)  │  ┌───────────────┐  │
│          │ ◄───────────────────────────── │  │  Auth Engine  │  │
│          │      token in callback URL     │  └───────────────┘  │
└────┬─────┘                                └─────────────────────┘
     │
     │  3. arrives at client callback with ?token=...
     ▼
┌──────────────┐    POST /api/v1/validate-token     ┌──────────────────────┐
│  Your App    │ ──────────────────────────────────► │  accounts.ordnary   │
│  (callback)  │    { token, client_id, secret }    │  .com                │
│              │ ◄────────────────────────────────── │                     │
│              │    { success: true, user: {...} }   └──────────────────────┘
│  ✓ Logged in │
└──────────────┘
```

**HTTP status codes from the validation endpoint:**

| Code | Meaning |
|------|---------|
| `200` | Token is valid — user object returned |
| `401` | Invalid client credentials or token |
| `404` | User not found |
| `419` | Token expired |

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Framework | Laravel 11 (PHP 8.x) |
| Templating | Blade — server-side rendered UI with component architecture |
| Frontend build | Vite — HMR in development, optimised bundles for production |
| Styling | TailwindCSS 3 + PostCSS |
| Database | MySQL / PostgreSQL — users, tokens, clients, activity logs |
| Cache / Sessions | Redis |
| 2FA | TOTP (Google Authenticator / Authy) + recovery codes |
| Testing | PHPUnit |
| Dependencies | Composer (PHP) + NPM (JS assets) |

---

## Project Structure

```
com.ordnary.accounts/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # Auth, SSO flow, token validation, account management
│   │   └── Middleware/         # Auth guards, session validation
│   ├── Models/                 # User, Token, Client, ActivityLog
│   └── Services/               # SSO token lifecycle, 2FA, email verification
│
├── bootstrap/                  # Laravel application bootstrap
├── config/                     # Environment configuration
├── database/
│   ├── migrations/             # Schema: users, tokens, clients, logs
│   └── seeders/                # Development seed data
│
├── docs/                       # Integration documentation for client developers
├── resources/
│   ├── views/                  # Blade templates (login, 2FA, account pages)
│   └── css/ & js/              # Frontend source compiled by Vite
│
├── routes/
│   ├── web.php                 # Login, callback, logout, account UI
│   └── api.php                 # Token validation endpoint
│
├── storage/                    # Logs, compiled views
├── tests/                      # PHPUnit unit and feature tests
├── public/                     # Webroot — compiled assets
├── reset_password_tinker.php   # Artisan Tinker utility for password resets
├── update_password.php         # CLI utility for direct password updates
├── vite.config.js
├── tailwind.config.js
└── composer.json
```

---

## Local Development

### Prerequisites

- PHP **8.x** with extensions: `pdo_mysql` or `pdo_pgsql`, `redis`, `mbstring`, `openssl`, `tokenizer`, `xml`
- Composer
- Node.js **18+** + NPM
- MySQL or PostgreSQL
- Redis

### Setup

**1. Clone the repository**

```bash
git clone https://github.com/7txr/com.ordnary.accounts.git
cd com.ordnary.accounts
```

**2. Install dependencies**

```bash
composer install
npm install
```

**3. Configure environment**

```bash
cp .env.example .env
php artisan key:generate
```

Key `.env` values to configure:

```env
APP_NAME="Ordnary Accounts"
APP_ENV=local
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ordnary_accounts
DB_USERNAME=your_user
DB_PASSWORD=your_password

# Redis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

SESSION_DRIVER=redis
CACHE_STORE=redis

# Mail (for email verification & recovery)
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=no-reply@ordnary.com
```

**4. Run migrations**

```bash
php artisan migrate
```

**5. Start development servers**

```bash
# Terminal 1 — Laravel
php artisan serve

# Terminal 2 — Vite (HMR)
npm run dev
```

The SSO server is now running at `http://localhost:8000`.

---

## Registering a Client Application

To connect a service to Ordnary SSO, register a client via the admin panel with the following details:

| Field | Description |
|-------|-------------|
| `name` | Human-readable application name |
| `client_id` | Auto-generated unique identifier |
| `client_secret` | Secret used to validate token requests — store securely |
| `redirect_uris` | Whitelisted callback URLs (exact match, HTTPS required in production) |
| `logout_url` | URL called on centralised logout to terminate the client session |

**Example client configuration:**

```json
{
  "name": "Ordnary Developers",
  "client_id": "abc123xyz789",
  "client_secret": "keep_this_secret",
  "redirect_uris": [
    "https://developers.ordnary.com/auth/callback",
    "http://localhost:8001/auth/callback"
  ],
  "logout_url": "https://developers.ordnary.com/auth/logout"
}
```

---

## Integrating SSO into a Laravel Application

### 1. Environment

```env
ORDNARY_SSO_URL=https://accounts.ordnary.com
ORDNARY_CLIENT_ID=your_client_id
ORDNARY_CLIENT_SECRET=your_client_secret
ORDNARY_REDIRECT_URI=https://yourapp.com/auth/callback
```

### 2. Routes

```php
// routes/web.php
Route::get('/auth/login',    [SSOController::class, 'redirect'])->name('sso.login');
Route::get('/auth/callback', [SSOController::class, 'callback'])->name('sso.callback');
Route::post('/auth/logout',  [SSOController::class, 'logout'])->name('sso.logout');
```

### 3. Redirect to SSO

```php
public function redirect(): RedirectResponse
{
    $url = config('ordnary.sso_url') . '/login?' . http_build_query([
        'client_id'    => config('ordnary.client_id'),
        'redirect_uri' => config('ordnary.redirect_uri'),
    ]);

    return redirect($url);
}
```

### 4. Handle the callback and validate the token

```php
public function callback(Request $request): RedirectResponse
{
    $response = Http::post(config('ordnary.sso_url') . '/api/v1/validate-token', [
        'token'         => $request->query('token'),
        'client_id'     => config('ordnary.client_id'),
        'client_secret' => config('ordnary.client_secret'),
    ]);

    $data = $response->json();

    if (! $data['success']) {
        return redirect()->route('sso.login')->withErrors('Authentication failed.');
    }

    session([
        'user_authenticated' => true,
        'user'               => $data['user'],
    ]);

    return redirect()->intended('/dashboard');
}
```

### 5. Protect routes with middleware

```php
// app/Http/Middleware/SSOAuth.php
public function handle(Request $request, Closure $next): Response
{
    if (! session('user_authenticated')) {
        return redirect()->route('sso.login');
    }

    return $next($request);
}
```

---

## Token Validation API

### `POST /api/v1/validate-token`

Validates a token issued during the SSO flow. Called server-to-server — never expose your `client_secret` in frontend code.

**Request body:**

```json
{
  "token":         "token_received_in_callback",
  "client_id":     "your_client_id",
  "client_secret": "your_client_secret"
}
```

**Success response `200`:**

```json
{
  "success": true,
  "user": {
    "id":       123,
    "name":     "Stijn",
    "email":    "stijn@ordnary.com",
    "is_admin": false
  }
}
```

**Error response `401` / `419`:**

```json
{
  "success": false,
  "message": "Invalid or expired token."
}
```

**Security properties of issued tokens:**
- SHA-256 hashed at rest — raw token is never stored
- Scoped to a single `client_id` — tokens cannot be reused across clients
- Short-lived — expire automatically; `419` is returned on timeout
- Single-use — invalidated after successful validation

---

## Security

| Concern | Implementation |
|---------|---------------|
| Token storage | SHA-256 hashed in database — raw value never persisted |
| Client secrets | Must be stored in environment variables, never in source code |
| Redirect URI validation | Exact string match against registered whitelist — no wildcards |
| Transport | HTTPS enforced in production; HTTP only permitted on `localhost` |
| 2FA | TOTP (RFC 6238) with backup recovery codes |
| Activity logging | All login events logged with IP, timestamp, user agent |
| Centralised logout | Single logout terminates all active sessions across all clients |

---

## Building for Production

```bash
# Compile and optimise frontend assets
npm run build

# Optimise Laravel for production
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## Testing

```bash
# Run the full test suite
php artisan test

# Or directly via PHPUnit
./vendor/bin/phpunit
```

Tests are split into:
- `tests/Unit/` — token lifecycle, hashing, 2FA logic
- `tests/Feature/` — full HTTP flows: login, callback, logout, token validation

---

## License

Copyright © 2026 Stijn / 7txr. All rights reserved.

This repository is provided for viewing and educational purposes only. Unauthorised copying, modification, or distribution is prohibited. See [LICENSE](LICENSE) for details.

---

<div align="center">
  <sub>Part of the <a href="https://github.com/ordnary-com">Ordnary</a> platform · Built by <a href="https://github.com/7txr">7txr</a></sub>
</div>
