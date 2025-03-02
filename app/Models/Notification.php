<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Notification extends Model
{
    use HasFactory;

    /**
     * De attributen die mass-assignable zijn.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'icon',
        'link',
        'read_at',
    ];

    /**
     * De attributen die als datum behandeld moeten worden.
     *
     * @var array<int, string>
     */
    protected $dates = [
        'read_at',
        'created_at',
        'updated_at',
    ];

    /**
     * De attributen die gecast moeten worden.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * De relatie met de gebruiker die de notificatie ontvangt.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Bepaal of de notificatie gelezen is.
     *
     * @return bool
     */
    public function isRead()
    {
        return $this->read_at !== null;
    }

    /**
     * Markeer de notificatie als gelezen.
     *
     * @return $this
     */
    public function markAsRead()
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }

        return $this;
    }

    /**
     * Markeer de notificatie als ongelezen.
     *
     * @return $this
     */
    public function markAsUnread()
    {
        if ($this->read_at !== null) {
            $this->update(['read_at' => null]);
        }

        return $this;
    }

    /**
     * Scope voor ongelezen notificaties.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope voor gelezen notificaties.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRead($query)
    {
        return $query->whereNotNull('read_at');
    }

    /**
     * Maak een nieuwe notificatie aan.
     *
     * @param User $user De gebruiker die de notificatie ontvangt
     * @param string $title De titel van de notificatie
     * @param string $message De inhoud van de notificatie
     * @param string $type Het type notificatie (info, success, warning, error)
     * @param string|null $icon Het FontAwesome icon voor de notificatie
     * @param string|null $link Een optionele link voor de notificatie
     * @return Notification
     */
    public static function send($user, $title, $message, $type = 'info', $icon = null, $link = null)
    {
        // Bepaal het icon op basis van het type als er geen icon is opgegeven
        if ($icon === null) {
            $icon = self::getIconForType($type);
        }

        return self::create([
            'user_id' => $user->id,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'icon' => $icon,
            'link' => $link,
        ]);
    }

    /**
     * Haal het standaard icon op voor een bepaald type notificatie.
     *
     * @param string $type
     * @return string
     */
    protected static function getIconForType($type)
    {
        switch ($type) {
            case 'success':
                return 'fa-check-circle';
            case 'warning':
                return 'fa-exclamation-triangle';
            case 'error':
                return 'fa-times-circle';
            case 'info':
            default:
                return 'fa-info-circle';
        }
    }

    /**
     * Get the read_at attribute.
     *
     * @param  string|null  $value
     * @return \Illuminate\Support\Carbon|null
     */
    public function getReadAtAttribute($value)
    {
        return $value ? Carbon::parse($value) : null;
    }
} 