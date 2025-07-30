<?php

namespace App\Policies;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationPolicy
{
    use HandlesAuthorization;

    /**
     * Bepaal of de gebruiker notificaties kan bekijken.
     */
    public function viewAny(User $user): bool
    {
        return true; // Elke ingelogde gebruiker kan zijn eigen notificaties bekijken
    }

    /**
     * Bepaal of de gebruiker een specifieke notificatie kan bekijken.
     */
    public function view(User $user, Notification $notification): bool
    {
        return $user->id === $notification->user_id || $user->is_admin;
    }

    /**
     * Bepaal of de gebruiker een notificatie kan bijwerken.
     */
    public function update(User $user, Notification $notification): bool
    {
        return $user->id === $notification->user_id || $user->is_admin;
    }

    /**
     * Bepaal of de gebruiker een notificatie kan verwijderen.
     */
    public function delete(User $user, Notification $notification): bool
    {
        return $user->id === $notification->user_id || $user->is_admin;
    }
}