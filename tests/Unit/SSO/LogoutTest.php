<?php

namespace Tests\Unit\SSO;

use App\Models\Client;
use App\Models\User;
use App\Models\UserService;
use App\Models\UserSession;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use DatabaseTransactions;
    
    private $user;
    private $clients = [];
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test user
        $this->user = User::factory()->create();
        
        // Create test clients with logout URLs
        for ($i = 1; $i <= 3; $i++) {
            $client = Client::create([
                'name' => "Test Service $i",
                'client_id' => "test-service-$i-" . uniqid(),
                'client_secret' => "secret-$i-" . uniqid(),
                'redirect_uris' => json_encode(['https://example.com/callback']),
                'webhook_url' => "https://example$i.com/webhook",
                'logout_url' => "https://example$i.com/logout",
            ]);
            
            // Create user service connection
            UserService::create([
                'user_id' => $this->user->id,
                'client_id' => $client->client_id,
                'first_login' => now()->subDays(7),
                'last_used' => now()->subDays(1),
                'status' => 'active',
            ]);
            
            // Create user session
            UserSession::create([
                'user_id' => $this->user->id,
                'client_id' => $client->client_id,
                'token' => hash('sha256', "token-$i"),
                'expires_at' => now()->addHours(24)
            ]);
            
            $this->clients[] = $client;
        }
    }
    
    /**
     * Test that logout redirects to process logout when there are active services.
     */
    public function test_logout_with_active_services(): void
    {
        // Mock the HTTP client to prevent actual webhook requests
        Http::fake();
        
        // Act as the authenticated user and perform logout
        $response = $this->actingAs($this->user)
                          ->get('/logout');
        
        // Assert redirect to logout process
        $response->assertRedirect(route('logout.process'));
        
        // Assert session contains logout redirects
        $this->assertNotNull(session('logout_redirects'));
        $this->assertCount(3, session('logout_redirects'));
        
        // Assert user is logged out
        $this->assertGuest();
    }
    
    /**
     * Test the process logout route.
     */
    public function test_process_logout(): void
    {
        // Setup logout redirects in session
        $logoutUrls = [
            'https://example1.com/logout',
            'https://example2.com/logout',
            'https://example3.com/logout',
        ];
        
        session(['logout_redirects' => $logoutUrls]);
        
        // Call the process logout route
        $response = $this->get('/logout/process');
        
        // Assert it redirects to the first logout URL
        $response->assertRedirect(
            'https://example1.com/logout?return_to=' . urlencode(route('logout.process'))
        );
        
        // Assert the session has been updated
        $this->assertCount(2, session('logout_redirects'));
        $this->assertEquals('https://example2.com/logout', session('logout_redirects')[0]);
    }
    
    /**
     * Test process logout when no more redirects.
     */
    public function test_process_logout_complete(): void
    {
        // Empty logout redirects in session
        session(['logout_redirects' => []]);
        
        // Call the process logout route
        $response = $this->get('/logout/process');
        
        // Assert it redirects to the homepage
        $response->assertRedirect('/');
    }
}
