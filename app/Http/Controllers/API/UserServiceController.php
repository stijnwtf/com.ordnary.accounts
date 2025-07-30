<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class UserServiceController extends Controller
{
    /**
     * Get all connected services for the authenticated user.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $services = $user->services()
            ->where('status', 'active')
            ->with('client:id,name,client_id')
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'service_id' => $service->client_id,
                    'service_name' => $service->client->name,
                    'first_login' => $service->first_login->format('Y-m-d H:i:s'),
                    'last_used' => $service->last_used->format('Y-m-d H:i:s'),
                ];
            });

        Log::info('UserServiceController@index: Retrieved user services', [
            'user_id' => $user->id,
            'services_count' => $services->count()
        ]);

        return response()->json([
            'success' => true,
            'data' => $services
        ]);
    }

    /**
     * Unlink (revoke) a service from the authenticated user.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unlinkService(Request $request): JsonResponse
    {
        // Debug: Log all incoming data
        Log::info('UserServiceController@unlinkService: Received request', [
            'all_data' => $request->all(),
            'headers' => $request->headers->all(),
            'user' => Auth::user() ? Auth::user()->id : 'not authenticated'
        ]);

        $request->validate([
            'service_id' => 'required|string'
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $serviceId = $request->input('service_id');

        // Find the service connection
        $service = $user->services()
            ->where('client_id', $serviceId)
            ->where('status', 'active')
            ->first();

        if (!$service) {
            Log::warning('UserServiceController@unlinkService: Service not found or already revoked', [
                'user_id' => $user->id,
                'service_id' => $serviceId
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Service not found or already revoked'
            ], 404);
        }

        // Get client details for webhook
        $client = Client::where('client_id', $serviceId)->first();

        // Update status to revoked
        $service->status = 'revoked';
        $service->save();

        // Delete any active user sessions for this client
        $user->userSessions()
            ->where('client_id', $serviceId)
            ->delete();

        Log::info('UserServiceController@unlinkService: Service unlinked', [
            'user_id' => $user->id,
            'service_id' => $serviceId
        ]);

        // Send webhook notification to the service if webhook_url is set
        if ($client && !empty($client->webhook_url)) {
            try {
                $response = Http::post($client->webhook_url, [
                    'event' => 'service_unlinked',
                    'user_id' => $user->id,
                    'service_id' => $serviceId,
                    'timestamp' => now()->toIso8601String()
                ]);

                Log::info('UserServiceController@unlinkService: Webhook notification sent', [
                    'user_id' => $user->id,
                    'service_id' => $serviceId,
                    'webhook_url' => $client->webhook_url,
                    'response_status' => $response->status()
                ]);
            } catch (\Exception $e) {
                Log::error('UserServiceController@unlinkService: Webhook notification failed', [
                    'user_id' => $user->id,
                    'service_id' => $serviceId,
                    'webhook_url' => $client->webhook_url,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Service successfully unlinked'
        ]);
    }
}
