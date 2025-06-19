<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AssignUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();
        
        if (!$adminRole || !$userRole) {
            $this->command->error('Required roles not found. Please run RoleSeeder first.');
            return;
        }
        
        // Assign admin role to admin@example.com
        $adminUser = User::where('email', 'admin@example.com')->first();
        if ($adminUser && $adminUser->roles()->count() === 0) {
            $adminUser->roles()->attach($adminRole);
            $this->command->info("Assigned admin role to: {$adminUser->email}");
        }
        
        // Assign user role to member@example.com
        $memberUser = User::where('email', 'member@example.com')->first();
        if ($memberUser && $memberUser->roles()->count() === 0) {
            $memberUser->roles()->attach($userRole);
            $this->command->info("Assigned user role to: {$memberUser->email}");
        }
        
        // Assign user role to any other users without roles
        $usersWithoutRoles = User::whereDoesntHave('roles')->get();
        foreach ($usersWithoutRoles as $user) {
            if (!in_array($user->email, ['admin@example.com', 'member@example.com'])) {
                $user->roles()->attach($userRole);
                $this->command->info("Assigned user role to: {$user->email}");
            }
        }
    }
}
