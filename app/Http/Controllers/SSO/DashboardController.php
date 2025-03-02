<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Toon het admin dashboard met statistieken en overzichten
     */
    public function index()
    {
        // Basisstatistieken
        $stats = [
            'total_users' => User::count(),
            'total_clients' => Client::count(),
            'total_active_sessions' => UserSession::where('expires_at', '>', now())->count(),
            'total_expired_sessions' => UserSession::where('expires_at', '<=', now())->count(),
            'admin_users' => User::where('is_admin', true)->count(),
        ];
        
        // Recente gebruikers
        $recentUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Recente clients
        $recentClients = Client::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Recente actieve sessies
        $recentSessions = UserSession::with(['user', 'client'])
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Gebruikersgroei per maand (laatste 6 maanden)
        $userGrowth = User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
            
        // Sessies per client (top 5)
        $sessionsPerClient = UserSession::select('client_id', DB::raw('count(*) as total'))
            ->with('client')
            ->groupBy('client_id')
            ->orderBy('total', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'stats', 
            'recentUsers', 
            'recentClients', 
            'recentSessions',
            'userGrowth',
            'sessionsPerClient'
        ));
    }
} 