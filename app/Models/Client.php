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
}
