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
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                    'notifications' => [],
                    'unreadCount' => 0,
                ], 401)->header('Content-Type', 'application/json');
            }
            
            // Haal de 5 meest recente notificaties op, ongeacht of ze gelezen zijn
            $notifications = $user->notifications()
                ->latest()
                ->take(5)
                ->get();
                
            // Haal het aantal ongelezen notificaties op
            $unreadCount = $user->notifications()->unread()->count();
            
            // Log voor debugging
            \Log::info('Notifications fetched', [
                'user_id' => $user->id,
                'notifications_count' => $notifications->count(),
                'unread_count' => $unreadCount,
                'time' => now()->toDateTimeString(),
                'request_url' => request()->fullUrl(),
                'request_ip' => request()->ip(),
            ]);
            
            // Voeg een debug veld toe aan de response
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unreadCount' => $unreadCount,
                'debug' => [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'notifications_count' => $notifications->count(),
                    'unread_count' => $unreadCount,
                    'time' => now()->toDateTimeString(),
                    'request_url' => request()->fullUrl(),
                ]
            ])->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            \Log::error('Error fetching notifications', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_url' => request()->fullUrl(),
                'request_ip' => request()->ip(),
            ]);
            
            return response()->json([
                'error' => $e->getMessage(),
                'notifications' => [],
                'unreadCount' => 0,
                'debug' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'request_url' => request()->fullUrl(),
                ]
            ], 500)->header('Content-Type', 'application/json');
        }
    }
    
    /**
     * Stuur een test notificatie naar de ingelogde gebruiker.
     */
    public function sendTestNotification()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->back()->with('error', 'Gebruiker niet ingelogd.');
        }
        
        try {
            // Maak een nieuwe notificatie aan
            $notification = Notification::create([
                'user_id' => $user->id,
                'title' => 'Test Notificatie',
                'message' => 'Dit is een test notificatie om het systeem te testen. Verzonden op: ' . now()->format('d-m-Y H:i:s'),
                'type' => 'info',
                'icon' => 'fa-bell',
                'link' => route('notifications.index'),
            ]);
            
            // Log voor debugging
            \Log::info('Test notification sent', [
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'notification' => $notification->toArray(),
                'time' => now()->toDateTimeString()
            ]);
            
            // Haal alle notificaties op voor deze gebruiker
            $allNotifications = $user->notifications()->get();
            \Log::info('All notifications for user', [
                'user_id' => $user->id,
                'count' => $allNotifications->count(),
                'notifications' => $allNotifications->toArray()
            ]);
            
            // Redirect naar de notificaties pagina
            return redirect()->route('notifications.index')->with('success', 'Test notificatie is verzonden. Notificatie ID: ' . $notification->id);
        } catch (\Exception $e) {
            \Log::error('Error sending test notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('notifications.index')->with('error', 'Fout bij versturen van test notificatie: ' . $e->getMessage());
        }
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
    
    /**
     * Maak een test notificatie aan via een API endpoint.
     */
    public function createTestNotification()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'error' => 'User not authenticated',
                ], 401)->header('Content-Type', 'application/json');
            }
            
            // Maak een nieuwe notificatie aan
            $notification = Notification::create([
                'user_id' => $user->id,
                'title' => 'API Test Notificatie',
                'message' => 'Dit is een test notificatie gemaakt via de API. Tijd: ' . now()->format('d-m-Y H:i:s'),
                'type' => 'info',
                'icon' => 'fa-bell',
                'link' => '/admin/notifications',
            ]);
            
            // Log voor debugging
            \Log::info('API test notification created', [
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'notification' => $notification->toArray(),
                'time' => now()->toDateTimeString()
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Test notificatie is aangemaakt',
                'notification' => $notification,
            ])->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            \Log::error('Error creating test notification via API', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_url' => request()->fullUrl(),
                'request_ip' => request()->ip(),
            ]);
            
            return response()->json([
                'error' => $e->getMessage(),
            ], 500)->header('Content-Type', 'application/json');
        }
    }
} 