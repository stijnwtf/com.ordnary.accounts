<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DeveloperPortalController extends Controller
{
    /**
     * Create a new client from developers.ordnary.com
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function createClient(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255|unique:clients,name',
                'redirect_uris' => 'required|array|min:1',
                'redirect_uris.*' => 'required|url|max:500',
                'webhook_url' => 'nullable|url|max:500',
                'logout_url' => 'nullable|url|max:500',
                'developer_email' => 'required|email|max:255',
                'developer_name' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Generate unique client credentials
            $clientId = 'client_' . Str::random(32);
            $clientSecret = Str::random(64);

            // Ensure client_id is unique
            while (Client::where('client_id', $clientId)->exists()) {
                $clientId = 'client_' . Str::random(32);
            }

            // Check if developer email belongs to an existing user
            $existingUser = \App\Models\User::where('email', $request->input('developer_email'))->first();
            $ownerUserId = $existingUser ? $existingUser->id : null;

            // Create the client with owner information
            $client = Client::create([
                'name' => $request->input('name'),
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uris' => json_encode($request->input('redirect_uris')),
                'webhook_url' => $request->input('webhook_url'),
                'logout_url' => $request->input('logout_url'),
                'owner_email' => $request->input('developer_email'),
                'owner_name' => $request->input('developer_name'),
                'owner_user_id' => $ownerUserId,
                'description' => $request->input('description'),
            ]);

            // Log the activity
            ActivityLog::log(
                'client_created',
                "Client '{$client->name}' was created via developer portal for {$request->input('developer_email')}",
                $client
            );

            Log::info('Client created via developer portal API', [
                'client_id' => $client->client_id,
                'client_name' => $client->name,
                'developer_email' => $request->input('developer_email'),
                'request_ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'client_id' => $client->client_id,
                    'client_secret' => $client->client_secret, // Only shown at creation
                    'redirect_uris' => $request->input('redirect_uris'),
                    'webhook_url' => $client->webhook_url,
                    'logout_url' => $client->logout_url,
                    'created_at' => $client->created_at->toIso8601String(),
                ],
                'message' => 'Client created successfully. Store the client_secret securely - it will not be shown again.'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Developer portal client creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to create client',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get client data by client_id for developers.ordnary.com
     * Requires user authentication to see only own clients
     * 
     * @param string $clientId
     * @param Request $request
     * @return JsonResponse
     */
    public function getClient(string $clientId, Request $request): JsonResponse
    {
        try {
            // Check for user authentication headers
            $userEmail = $request->header('X-User-Email');
            $userId = $request->header('X-User-ID');

            if (!$userEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'User authentication required. Please provide X-User-Email header.'
                ], 401);
            }

            $client = Client::where('client_id', $clientId)
                ->where('owner_email', $userEmail)
                ->first();

            // If user_id is provided, also filter by that for extra security
            if ($userId && $client) {
                $client = Client::where('client_id', $clientId)
                    ->where('owner_email', $userEmail)
                    ->where('owner_user_id', $userId)
                    ->first();
            }

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found or not owned by authenticated user'
                ], 404);
            }

            // Get session statistics
            $totalSessions = $client->userSessions()->count();
            $activeSessions = $client->userSessions()->where('expires_at', '>', now())->count();
            $uniqueUsers = $client->userSessions()->distinct('user_id')->count('user_id');
            $recentSessions = $client->userSessions()
                ->where('created_at', '>=', now()->subDays(30))
                ->count();

            Log::info('Client data retrieved via developer portal API', [
                'client_id' => $clientId,
                'client_name' => $client->name
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'client_id' => $client->client_id,
                    // client_secret is intentionally excluded for security
                    'redirect_uris' => json_decode($client->redirect_uris, true),
                    'webhook_url' => $client->webhook_url,
                    'logout_url' => $client->logout_url,
                    'created_at' => $client->created_at->toIso8601String(),
                    'updated_at' => $client->updated_at->toIso8601String(),
                    'statistics' => [
                        'total_sessions' => $totalSessions,
                        'active_sessions' => $activeSessions,
                        'unique_users' => $uniqueUsers,
                        'recent_sessions_30_days' => $recentSessions,
                        'last_used' => $client->userSessions()->latest()->first()?->created_at?->toIso8601String(),
                    ]
                ],
                'message' => 'Client data retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Developer portal client retrieval failed', [
                'client_id' => $clientId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve client data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get multiple clients data for developers.ordnary.com
     * Requires user authentication to see only own clients
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getClients(Request $request): JsonResponse
    {
        try {
            // Check for user authentication headers
            $userEmail = $request->header('X-User-Email');
            $userId = $request->header('X-User-ID');

            if (!$userEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'User authentication required. Please provide X-User-Email header.'
                ], 401);
            }

            // Optionally filter by client_ids if provided
            $clientIds = $request->input('client_ids', []);

            $query = Client::where('owner_email', $userEmail);

            // If user_id is provided, also filter by that for extra security
            if ($userId) {
                $query->where('owner_user_id', $userId);
            }

            if (!empty($clientIds) && is_array($clientIds)) {
                $query->whereIn('client_id', $clientIds);
            }

            $clients = $query->orderBy('created_at', 'desc')->get();

            $clientData = $clients->map(function ($client) {
                $totalSessions = $client->userSessions()->count();
                $activeSessions = $client->userSessions()->where('expires_at', '>', now())->count();
                $uniqueUsers = $client->userSessions()->distinct('user_id')->count('user_id');

                return [
                    'id' => $client->id,
                    'name' => $client->name,
                    'client_id' => $client->client_id,
                    // client_secret is intentionally excluded for security
                    'redirect_uris' => json_decode($client->redirect_uris, true),
                    'webhook_url' => $client->webhook_url,
                    'logout_url' => $client->logout_url,
                    'created_at' => $client->created_at->toIso8601String(),
                    'updated_at' => $client->updated_at->toIso8601String(),
                    'statistics' => [
                        'total_sessions' => $totalSessions,
                        'active_sessions' => $activeSessions,
                        'unique_users' => $uniqueUsers,
                    ]
                ];
            });

            Log::info('Multiple clients data retrieved via developer portal API', [
                'client_count' => $clients->count(),
                'requested_client_ids' => $clientIds
            ]);

            return response()->json([
                'success' => true,
                'data' => $clientData,
                'message' => 'Clients data retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Developer portal clients retrieval failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve clients data',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update client settings from developers.ordnary.com
     * Requires user authentication to update only own clients
     * 
     * @param Request $request
     * @param string $clientId
     * @return JsonResponse
     */
    public function updateClient(Request $request, string $clientId): JsonResponse
    {
        try {
            // Check for user authentication headers
            $userEmail = $request->header('X-User-Email');
            $userId = $request->header('X-User-ID');

            if (!$userEmail) {
                return response()->json([
                    'success' => false,
                    'message' => 'User authentication required. Please provide X-User-Email header.'
                ], 401);
            }

            $client = Client::where('client_id', $clientId)
                ->where('owner_email', $userEmail)
                ->first();

            // If user_id is provided, also filter by that for extra security
            if ($userId && $client) {
                $client = Client::where('client_id', $clientId)
                    ->where('owner_email', $userEmail)
                    ->where('owner_user_id', $userId)
                    ->first();
            }

            if (!$client) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client not found or not owned by authenticated user'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|required|string|max:255|unique:clients,name,' . $client->id,
                'redirect_uris' => 'sometimes|required|array|min:1',
                'redirect_uris.*' => 'required|url|max:500',
                'webhook_url' => 'nullable|url|max:500',
                'logout_url' => 'nullable|url|max:500',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [];

            if ($request->has('name')) {
                $updateData['name'] = $request->input('name');
            }

            if ($request->has('redirect_uris')) {
                $updateData['redirect_uris'] = json_encode($request->input('redirect_uris'));
            }

            if ($request->has('webhook_url')) {
                $updateData['webhook_url'] = $request->input('webhook_url');
            }

            if ($request->has('logout_url')) {
                $updateData['logout_url'] = $request->input('logout_url');
            }

            if (empty($updateData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid fields provided for update'
                ], 422);
            }

            $client->update($updateData);

            ActivityLog::log(
                'client_updated',
                "Client '{$client->name}' was updated via developer portal",
                $client
            );

            Log::info('Client updated via developer portal API', [
                'client_id' => $client->client_id,
                'client_name' => $client->name,
                'updated_fields' => array_keys($updateData)
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $client->id,
                    'name' => $client->name,
                    'client_id' => $client->client_id,
                    'redirect_uris' => json_decode($client->redirect_uris, true),
                    'webhook_url' => $client->webhook_url,
                    'logout_url' => $client->logout_url,
                    'updated_at' => $client->updated_at->toIso8601String(),
                ],
                'message' => 'Client updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Developer portal client update failed', [
                'client_id' => $clientId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update client',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
