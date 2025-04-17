<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // If the user is authenticated and has admin role
        if ($request->user() && $request->user()->is_admin) {
            // Check if they're trying to access the admin panel
            $adminUrl = route('filament.admin.pages.dashboard');
            
            // Only redirect if they're not already trying to access the admin panel
            if ($request->url() !== $adminUrl && !str_starts_with($request->url(), route('filament.admin', [], false))) {
                return redirect()->route('filament.admin.pages.dashboard');
            }
        }

        return $next($request);
    }
} 