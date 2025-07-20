<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
        'created_by',
        'due_date',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    /**
     * Get the user who is assigned to this todo.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the user who created this todo.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include overdue todos.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->where('due_date', '<', now())
                    ->where('status', '!=', 'completed');
    }

    /**
     * Scope a query to only include completed todos.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include pending todos.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', '!=', 'completed');
    }
}
