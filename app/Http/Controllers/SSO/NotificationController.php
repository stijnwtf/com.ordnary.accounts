<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class NotificationController extends Controller
{
    /**
     * Toon een lijst van alle notificaties voor de ingelogde gebruiker.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Filter op type
        $query = $user->notifications();
        
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }
        
        // Filter op gelezen/ongelezen
        if ($request->has('read') && $request->read !== null) {
            if ($request->read === 'true' || $request->read === '1') {
                $query->read();
            } else {
                $query->unread();
            }
        }
        
        // Sorteer op datum (nieuwste eerst)
        $query->latest();
        
        // Pagineer de resultaten
        $notifications = $query->paginate(10)->withQueryString();
        
        // Haal unieke types op voor het filter
        $types = Notification::distinct()->pluck('type');
        
        // Haal ongelezen notificaties op voor de badge
        $unreadCount = $user->notifications()->unread()->count();
        
        return view('admin.notifications.index', compact('notifications', 'types', 'unreadCount'));
    }
    
    /**
     * Toon een specifieke notificatie.
     */
    public function show(Notification $notification)
    {
        // Controleer of de notificatie van de ingelogde gebruiker is
        Gate::authorize('view', $notification);
        
        // Markeer de notificatie als gelezen
        $notification->markAsRead();
        
        return view('admin.notifications.show', compact('notification'));
    }
    
    /**
     * Markeer een notificatie als gelezen.
     */
    public function markAsRead(Notification $notification)
    {
        // Controleer of de notificatie van de ingelogde gebruiker is
        Gate::authorize('update', $notification);
        
        $notification->markAsRead();
        
        return redirect()->back()->with('success', 'Notificatie is gemarkeerd als gelezen.');
    }
    
    /**
     * Markeer een notificatie als ongelezen.
     */
    public function markAsUnread(Notification $notification)
    {
        // Controleer of de notificatie van de ingelogde gebruiker is
        Gate::authorize('update', $notification);
        
        $notification->markAsUnread();
        
        return redirect()->back()->with('success', 'Notificatie is gemarkeerd als ongelezen.');
    }
    
    /**
     * Markeer alle notificaties als gelezen.
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        
        $user->notifications()->unread()->update(['read_at' => now()]);
        
        return redirect()->back()->with('success', 'Alle notificaties zijn gemarkeerd als gelezen.');
    }
    
    /**
     * Verwijder een notificatie.
     */
    public function destroy(Notification $notification)
    {
        // Controleer of de notificatie van de ingelogde gebruiker is
        Gate::authorize('delete', $notification);
        
        $notification->delete();
        
        return redirect()->route('notifications.index')->with('success', 'Notificatie is verwijderd.');
    }
    
    /**
     * Verwijder alle notificaties.
     */
    public function destroyAll()
    {
        $user = Auth::user();
        
        $user->notifications()->delete();
        
        return redirect()->route('notifications.index')->with('success', 'Alle notificaties zijn verwijderd.');
    }
    
    /**
     * Haal ongelezen notificaties op voor de dropdown in de header.
     */
    public function getUnreadNotifications()
    {
        $user = Auth::user();
        
        $notifications = $user->notifications()
            ->unread()
            ->latest()
            ->take(5)
            ->get();
            
        $unreadCount = $user->notifications()->unread()->count();
        
        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }
    
    /**
     * Stuur een test notificatie naar de ingelogde gebruiker.
     */
    public function sendTestNotification()
    {
        $user = Auth::user();
        
        Notification::send(
            $user,
            'Test Notificatie',
            'Dit is een test notificatie om het systeem te testen.',
            'info',
            'fa-bell',
            route('notifications.index')
        );
        
        return redirect()->back()->with('success', 'Test notificatie is verzonden.');
    }
    
    /**
     * Stuur een notificatie naar een specifieke gebruiker of alle gebruikers.
     */
    public function sendNotification(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error',
            'link' => 'nullable|url',
        ]);
        
        if ($request->user_id) {
            // Stuur naar een specifieke gebruiker
            $user = User::findOrFail($request->user_id);
            
            Notification::send(
                $user,
                $validated['title'],
                $validated['message'],
                $validated['type'],
                null,
                $validated['link'] ?? null
            );
            
            $message = 'Notificatie is verzonden naar ' . $user->name . '.';
        } else {
            // Stuur naar alle gebruikers
            $users = User::all();
            
            foreach ($users as $user) {
                Notification::send(
                    $user,
                    $validated['title'],
                    $validated['message'],
                    $validated['type'],
                    null,
                    $validated['link'] ?? null
                );
            }
            
            $message = 'Notificatie is verzonden naar alle gebruikers.';
        }
        
        return redirect()->back()->with('success', $message);
    }
} 