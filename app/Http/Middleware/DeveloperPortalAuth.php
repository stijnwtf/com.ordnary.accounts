<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class DeveloperPortalAuth
{
    /**
     * Handle an incoming request from developers.numblio.com
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-API-Key') ?: $request->bearerToken();
        
        // Get the expected API key from config
        $expectedApiKey = config('services.developer_portal.api_key');
        
        if (empty($expectedApiKey)) {
            Log::error('Developer portal API key not configured');
            return response()->json([
                'success' => false,
                'message' => 'Developer portal not properly configured'
            ], 500);
        }
        
        if (empty($apiKey) || !hash_equals($expectedApiKey, $apiKey)) {
            Log::warning('Unauthorized developer portal API request', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'provided_key_length' => $apiKey ? strlen($apiKey) : 0
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Invalid API key.'
            ], 401);
        }
        
        Log::info('Authorized developer portal API request', [
            'ip' => $request->ip(),
            'endpoint' => $request->getPathInfo(),
            'method' => $request->getMethod()
        ]);
        
        return $next($request);
    }
}
