<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

class UserApprovalApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles
        $this->artisan('db:seed', ['--class' => 'RoleSeeder']);
    }

    /** @test */
    public function it_can_get_approval_stats()
    {
        // Create admin user
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'administrator')->first();
        $admin->roles()->attach($adminRole);
        
        // Create test users with different approval statuses
        User::factory()->create(['approval_status' => 'approved']);
        User::factory()->create(['approval_status' => 'pending']);
        User::factory()->create(['approval_status' => 'rejected']);

        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/user-approval/stats');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'total_users',
                    'approved_users',
                    'pending_users',
                    'rejected_users',
                    'approval_rate'
                ]);
    }

    /** @test */
    public function it_can_approve_user()
    {
        // Create admin user
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'administrator')->first();
        $admin->roles()->attach($adminRole);
        
        // Create pending user
        $user = User::factory()->create(['approval_status' => 'pending']);

        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/user-approval/users/{$user->id}/approve", [
            'reason' => 'User meets all requirements'
        ]);

        $response->assertStatus(200);
        
        $user->refresh();
        $this->assertEquals('approved', $user->approval_status);
        $this->assertEquals($admin->id, $user->approved_by);
        $this->assertNotNull($user->approved_at);
    }

    /** @test */
    public function it_can_reject_user()
    {
        // Create admin user
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'administrator')->first();
        $admin->roles()->attach($adminRole);
        
        // Create pending user
        $user = User::factory()->create(['approval_status' => 'pending']);

        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/user-approval/users/{$user->id}/reject", [
            'reason' => 'Incomplete documentation'
        ]);

        $response->assertStatus(200);
        
        $user->refresh();
        $this->assertEquals('rejected', $user->approval_status);
        $this->assertEquals('Incomplete documentation', $user->rejection_reason);
    }

    /** @test */
    public function it_can_bulk_approve_users()
    {
        // Create admin user
        $admin = User::factory()->create();
        $adminRole = Role::where('name', 'administrator')->first();
        $admin->roles()->attach($adminRole);
        
        // Create pending users
        $users = User::factory()->count(3)->create(['approval_status' => 'pending']);
        $userIds = $users->pluck('id')->toArray();

        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/user-approval/users/bulk-approve', [
            'user_ids' => $userIds,
            'reason' => 'Bulk approval for valid users'
        ]);

        $response->assertStatus(200);
        
        foreach ($users as $user) {
            $user->refresh();
            $this->assertEquals('approved', $user->approval_status);
        }
    }

    /** @test */
    public function it_prevents_non_admin_access()
    {
        // Create regular user
        $user = User::factory()->create();
        $userRole = Role::where('name', 'user')->first();
        $user->roles()->attach($userRole);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user-approval/stats');

        $response->assertStatus(403);
    }

    /** @test */
    public function it_prevents_unauthenticated_access()
    {
        $response = $this->getJson('/api/user-approval/stats');

        $response->assertStatus(401);
    }
}
