<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class UserApprovalApiIntegrationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure roles exist
        if (Role::count() === 0) {
            $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
        }
    }

    /** @test */
    public function api_routes_exist()
    {
        $routes = collect(\Route::getRoutes())->map(function ($route) {
            return $route->uri();
        })->filter(function ($uri) {
            return str_contains($uri, 'user-approval');
        });

        $this->assertGreaterThan(0, $routes->count(), 'User approval API routes should exist');
    }

    /** @test */
    public function api_requires_authentication()
    {
        $response = $this->getJson('/api/user-approval/stats');
        $response->assertStatus(401);
    }

    /** @test */
    public function admin_can_access_api()
    {
        // Create admin user
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'administrator')->first();
        
        if ($adminRole) {
            $admin->roles()->attach($adminRole->id);
        }

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/user-approval/stats');
        
        // Should not be 401 or 403
        $this->assertNotEquals(401, $response->status());
        $this->assertNotEquals(403, $response->status());
    }

    /** @test */
    public function regular_user_cannot_access_api()
    {
        // Create regular user
        $user = User::factory()->create();
        $userRole = Role::where('name', 'user')->first();
        
        if ($userRole) {
            $user->roles()->attach($userRole->id);
        }

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user-approval/stats');
        $response->assertStatus(403);
    }

    /** @test */
    public function user_model_has_approval_methods()
    {
        $user = new User();
        
        $this->assertTrue(method_exists($user, 'isApproved'));
        $this->assertTrue(method_exists($user, 'isPending'));
        $this->assertTrue(method_exists($user, 'isRejected'));
        $this->assertTrue(method_exists($user, 'approve'));
        $this->assertTrue(method_exists($user, 'reject'));
    }

    /** @test */
    public function role_system_works()
    {
        $user = User::factory()->create();
        $adminRole = Role::where('name', 'administrator')->first();
        
        if ($adminRole) {
            $user->roles()->attach($adminRole->id);
            $user->refresh();
            
            $this->assertTrue($user->hasRole('administrator'));
        }
    }
}
