<?php

namespace App\Http\Controllers\SSO;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    /**
     * Display a listing of all active sessions.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $query = UserSession::with(['user', 'client']);

        // Filter op gebruiker indien opgegeven
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Filter op client indien opgegeven
        if ($request->has('client_id') && $request->client_id) {
            $query->where('client_id', $request->client_id);
        }

        // Sorteer op vervaldatum (standaard nieuwste eerst)
        $query->orderBy('expires_at', $request->sort_order ?? 'desc');

        $sessions = $query->paginate(15);
        $users = User::orderBy('name')->get();
        $clients = Client::orderBy('name')->get();

        return view('admin.sessions.index', compact('sessions', 'users', 'clients'));
    }

    /**
     * Display the specified session.
     *
     * @param  \App\Models\UserSession  $session
     * @return \Illuminate\Contracts\View\View
     */
    public function show(UserSession $session)
    {
        $session->load(['user', 'client']);
        return view('admin.sessions.show', compact('session'));
    }

    /**
     * Remove the specified session from storage.
     *
     * @param  \App\Models\UserSession  $session
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(UserSession $session)
    {
        $session->delete();

        return redirect()->route('sessions.index')
            ->with('success', 'Sessie succesvol beëindigd.');
    }

    /**
     * Remove multiple sessions from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function bulkDestroy(Request $request)
    {
        $validated = $request->validate([
            'session_ids' => 'required|array',
            'session_ids.*' => 'exists:user_sessions,id',
        ]);

        UserSession::whereIn('id', $validated['session_ids'])->delete();

        return redirect()->route('sessions.index')
            ->with('success', count($validated['session_ids']) . ' sessies succesvol beëindigd.');
    }

    /**
     * Remove all expired sessions from storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyExpired()
    {
        $count = UserSession::where('expires_at', '<', now())->delete();

        return redirect()->route('sessions.index')
            ->with('success', $count . ' verlopen sessies succesvol opgeruimd.');
    }
} 