<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'numblio_id',
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
        'is_admin',
        'lang',
        'recovery_email',
        'recovery_email_verified',
        'recovery_email_verified_at',
        'two_factor_enabled',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'recovery_email_verified_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_recovery_codes' => 'array',
            'deleted_at' => 'datetime',
        ];
    }

    /**
     * Relatie met gebruikerssessies.
     */
    public function userSessions()
    {
        return $this->hasMany(UserSession::class);
    }

    /**
     * Relatie met notificaties.
     */
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Haal het aantal ongelezen notificaties op.
     */
    public function unreadNotificationsCount()
    {
        return $this->notifications()->whereNull('read_at')->count();
    }

    public function loginActivities()
    {
        return $this->hasMany(LoginActivity::class);
    }

    public function getRecentLoginActivities($limit = 5)
    {
        return $this->loginActivities()
            ->latest()
            ->take($limit)
            ->get();
    }

    public function verifyRecoveryEmail()
    {
        $this->recovery_email_verified = true;
        $this->recovery_email_verified_at = now();
        $this->save();
    }

    public function generateTwoFactorSecret()
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();

        $this->two_factor_secret = $secret;
        $this->save();

        return $secret;
    }

    public function generateRecoveryCodes()
    {
        $codes = collect();
        for ($i = 0; $i < 8; $i++) {
            $codes->push(Str::random(10));
        }

        $this->two_factor_recovery_codes = $codes;
        $this->save();

        return $codes;
    }

    public function enableTwoFactor()
    {
        $this->two_factor_enabled = true;
        $this->two_factor_confirmed_at = now();
        $this->save();
    }

    public function disableTwoFactor()
    {
        $this->two_factor_enabled = false;
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->two_factor_confirmed_at = null;
        $this->save();
    }

    public function confirmTwoFactor()
    {
        $this->two_factor_confirmed_at = now();
        $this->save();
    }

    public function hasTwoFactorEnabled()
    {
        return $this->two_factor_enabled && $this->two_factor_secret !== null;
    }

    public function hasTwoFactorSecret()
    {
        return $this->two_factor_secret !== null;
    }

    public function hasRecoveryCodes()
    {
        return $this->two_factor_recovery_codes !== null;
    }

    /**
     * Get the services connected to this user's account.
     */
    public function services()
    {
        return $this->hasMany(UserService::class);
    }

    /**
     * Get active services connected to this user's account.
     */
    public function activeServices()
    {
        return $this->services()->where('status', 'active');
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );

        try {
            Mail::to($this->email)->send(new \App\Mail\VerifyEmail($this, $verificationUrl));
            Log::info("sendEmailVerificationNotification: Verification email sent", ["email" => $this->email]);
        } catch (\Exception $e) {
            Log::error("sendEmailVerificationNotification: Failed to send verification email", [
                "email" => $this->email,
                "exception" => $e->getMessage(),
                "trace" => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Determine if the user can access the Filament admin panel.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin;
    }

    /**
     * Get the user's name for display.
     */
    public function getFilamentName(): string
    {
        if (!empty($this->username)) {
            return $this->username;
        }

        if (!empty($this->first_name) || !empty($this->last_name)) {
            return trim($this->first_name . ' ' . $this->last_name);
        }

        return $this->email ?? 'User';
    }

    public function getUserName(): string
    {
        return auth()->user()?->name ?? 'Guest';
    }

}
