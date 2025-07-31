<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSuspended
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isSuspended()) {
            $user = Auth::user();
            
            // Store user ID in session for the banned page (before logout)
            $request->session()->put('suspended_user_id', $user->id);
            $request->session()->put('suspended_user_timeout', now()->addHours(24));
            
            // Force save the session before logout
            $request->session()->save();
            
            // Log out the suspended user
            Auth::logout();
            
            // Regenerate session but keep our suspended user data
            $suspendedUserId = $request->session()->get('suspended_user_id');
            $suspendedTimeout = $request->session()->get('suspended_user_timeout');
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Restore the suspended user data
            $request->session()->put('suspended_user_id', $suspendedUserId);
            $request->session()->put('suspended_user_timeout', $suspendedTimeout);
            $request->session()->save();
            
            // Redirect to the banned page
            return redirect()->route('banned');
        }

        return $next($request);
    }
}
