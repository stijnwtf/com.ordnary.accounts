<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * De attributen die mass-assignable zijn.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
    ];

    /**
     * De relatie met de gebruiker die de actie heeft uitgevoerd.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Voeg een nieuwe activiteit toe aan het logboek.
     *
     * @param string $action De uitgevoerde actie (bijv. 'create', 'update', 'delete')
     * @param string $description Een beschrijving van de actie
     * @param Model|null $model Het model waarop de actie is uitgevoerd (optioneel)
     * @param User|null $user De gebruiker die de actie heeft uitgevoerd (optioneel)
     * @return ActivityLog
     */
    public static function log($action, $description, $model = null, $user = null)
    {
        $user = $user ?? auth()->user();
        
        $data = [
            'user_id' => $user ? $user->id : null,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];
        
        if ($model) {
            $data['model_type'] = get_class($model);
            $data['model_id'] = $model->id;
        }
        
        return self::create($data);
    }
} 