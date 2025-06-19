<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AssignUserRolesSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();
        
        if (!$adminRole || !$userRole) {
            echo "Required roles not found. Please run RoleSeeder first.\n";
            return;
        }
        
        $users = User::with('roles')->get();
        
        foreach ($users as $user) {
            if ($user->roles->isEmpty()) {
                // If this is the first user (by ID), assign admin role
                if ($user->id === User::orderBy('id')->first()->id) {
                    $user->roles()->attach($adminRole);
                    echo "Assigned admin role to first user: {$user->email}\n";
                } else {
                    $user->roles()->attach($userRole);
                    echo "Assigned user role to: {$user->email}\n";
                }
            } else {
                echo "User {$user->email} already has roles: " . $user->roles->pluck('name')->join(', ') . "\n";
            }
        }
    }
}
