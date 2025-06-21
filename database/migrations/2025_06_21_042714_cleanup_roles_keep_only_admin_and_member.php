<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update the 'user' role to 'member'
        $userRole = Role::where('name', 'user')->first();
        if ($userRole) {
            $userRole->update([
                'name' => 'member',
                'display_name' => 'Member'
            ]);
        }
        
        // Remove unwanted roles and reassign users
        $rolesToRemove = ['manager', 'guest'];
        $memberRole = Role::where('name', 'member')->first();
        
        foreach ($rolesToRemove as $roleName) {
            $role = Role::where('name', $roleName)->first();
            if ($role) {
                // Reassign all users with this role to member role
                $usersWithRole = User::whereHas('roles', function($q) use ($role) {
                    $q->where('role_id', $role->id);
                })->get();
                
                foreach ($usersWithRole as $user) {
                    $user->roles()->detach($role->id);
                    if ($memberRole && !$user->roles()->where('role_id', $memberRole->id)->exists()) {
                        $user->roles()->attach($memberRole->id);
                    }
                }
                
                // Delete the role
                $role->delete();
            }
        }
        
        // Ensure we have exactly 2 roles: admin and member
        $adminRole = Role::firstOrCreate(
            ['name' => 'admin'],
            ['display_name' => 'Administrator', 'description' => 'Full system administrator with all permissions']
        );
        
        $memberRoleEnsured = Role::firstOrCreate(
            ['name' => 'member'],
            ['display_name' => 'Member', 'description' => 'Regular system member with limited permissions']
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate the removed roles
        Role::firstOrCreate(
            ['name' => 'manager'],
            ['display_name' => 'Manager', 'description' => 'System manager with elevated permissions']
        );
        
        Role::firstOrCreate(
            ['name' => 'guest'],
            ['display_name' => 'Guest', 'description' => 'Guest user with minimal permissions']
        );
        
        // Revert member role back to user
        $memberRole = Role::where('name', 'member')->first();
        if ($memberRole) {
            $memberRole->update([
                'name' => 'user',
                'display_name' => 'User'
            ]);
        }
    }
};
