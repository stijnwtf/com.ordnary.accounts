<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'client_id',
        'client_secret',
        'redirect_uris',
        'webhook_url',
        'logout_url',
    ];

    /**
     * Get the redirect URIs as an array.
     *
     * @return array
     */
    public function getRedirectUrisArrayAttribute()
    {
        return json_decode($this->redirect_uris) ?: [];
    }

    /**
     * Get the user sessions for this client.
     */
    public function userSessions()
    {
        return $this->hasMany(UserSession::class, 'client_id', 'client_id');
    }

    /**
     * Get the user services linked to this client.
     */
    public function userServices()
    {
        return $this->hasMany(UserService::class, 'client_id', 'client_id');
    }

    /**
     * Get the users who have connected to this client/service.
     */
    public function users()
    {
        return $this->hasManyThrough(
            User::class,
            UserService::class,
            'client_id', // Foreign key on user_services table
            'id', // Foreign key on users table
            'client_id', // Local key on clients table
            'user_id' // Local key on user_services table
        );
    }
}
