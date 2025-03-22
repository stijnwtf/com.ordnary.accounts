<?php

namespace Tests\Unit\API;

use App\Models\Client;
use App\Models\User;
use App\Models\UserService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use DatabaseTransactions;
    
    private $user;
    private $client;
    private $userService;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create test client
        $this->client = Client::create([
            'name' => 'Test Service',
            'client_id' => 'test-service-' . uniqid(),
            'client_secret' => 'secret-' . uniqid(),
            'redirect_uris' => json_encode(['https://example.com/callback']),
            'webhook_url' => 'https://example.com/webhook',
            'logout_url' => 'https://example.com/logout',
        ]);
        
        // Create user service connection
        $this->userService = UserService::create([
            'user_id' => $this->user->id,
            'client_id' => $this->client->client_id,
            'first_login' => now()->subDays(7),
            'last_used' => now()->subDays(1),
            'status' => 'active',
        ]);
    }
    
    /**
     * Test retrieving user services list.
     */
    public function test_get_user_services(): void
    {
        // Act as the authenticated user
        $response = $this->actingAs($this->user)
                         ->get('/api/user/services');
        
        // Assert successful response
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                 ])
                 ->assertJsonStructure([
                     'success',
                     'data' => [
                         '*' => [
                             'id',
                             'service_id',
                             'service_name',
                             'first_login',
                             'last_used',
                         ]
                     ]
                 ]);
                 
        // Assert the specific service is in the response
        $response->assertJsonFragment([
            'service_id' => $this->client->client_id,
            'service_name' => $this->client->name,
        ]);
    }
    
    /**
     * Test unlink service.
     */
    public function test_unlink_service(): void
    {
        // Act as the authenticated user
        $response = $this->actingAs($this->user)
                         ->post('/api/user/unlink-service', [
                             'service_id' => $this->client->client_id
                         ]);
        
        // Assert successful response
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Service successfully unlinked'
                 ]);
                 
        // Assert service is now revoked
        $this->assertDatabaseHas('user_services', [
            'user_id' => $this->user->id,
            'client_id' => $this->client->client_id,
            'status' => 'revoked',
        ]);
    }
    
    /**
     * Test attempting to unlink a non-existent service.
     */
    public function test_unlink_nonexistent_service(): void
    {
        // Act as the authenticated user
        $response = $this->actingAs($this->user)
                         ->post('/api/user/unlink-service', [
                             'service_id' => 'nonexistent-service'
                         ]);
        
        // Assert not found response
        $response->assertStatus(404)
                 ->assertJson([
                     'success' => false,
                     'message' => 'Service not found or already revoked'
                 ]);
    }
    
    /**
     * Test unauthenticated access to services endpoint.
     */
    public function test_unauthenticated_access(): void
    {
        // Attempt to access the endpoint without authentication
        $response = $this->get('/api/user/services');
        
        // Assert unauthorized response
        $response->assertStatus(302); // Redirect to login page
        $response->assertRedirect('/login');
    }
}
