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
        'owner_email',
        'owner_name',
        'owner_user_id',
        'description',
    ];

    /**
     * Get the redirect URIs as an array.
     *
     * @return array
     */
    public function getRedirectUrisArrayAttribute()
    {
        // Try to decode as JSON first
        $decoded = json_decode($this->redirect_uris, true);
        
        if (is_array($decoded)) {
            return $decoded;
        }
        
        // If not JSON, treat as a single URI string
        // Handle cases where multiple URIs might be separated by newlines
        $uris = preg_split('/\r\n|\r|\n/', trim($this->redirect_uris));
        $uris = array_filter(array_map('trim', $uris)); // Remove empty entries and trim whitespace
        
        return array_values($uris); // Re-index array
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

    /**
     * Get the owner of this client.
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /**
     * Check if a user is the owner of this client.
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->owner_user_id === $user->id || $this->owner_email === $user->email;
    }

    /**
     * Scope to filter clients by owner.
     */
    public function scopeOwnedBy($query, User $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('owner_user_id', $user->id)
              ->orWhere('owner_email', $user->email);
        });
    }
}
