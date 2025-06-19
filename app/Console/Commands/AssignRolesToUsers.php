<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class AssignRolesToUsers extends Command
{
    protected $signature = 'users:assign-roles';
    protected $description = 'Assign roles to existing users';

    public function handle()
    {
        $this->info('Assigning roles to existing users...');
        
        $users = User::with('roles')->get();
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();
        
        if (!$adminRole || !$userRole) {
            $this->error('Required roles not found. Please run RoleSeeder first.');
            return 1;
        }
        
        foreach ($users as $user) {
            if ($user->roles->isEmpty()) {
                // If this is the first user (by ID), assign admin role
                if ($user->id === User::orderBy('id')->first()->id) {
                    $user->roles()->attach($adminRole);
                    $this->info("Assigned admin role to first user: {$user->email}");
                } else {
                    $user->roles()->attach($userRole);
                    $this->info("Assigned user role to: {$user->email}");
                }
            } else {
                $this->info("User {$user->email} already has roles: " . $user->roles->pluck('name')->join(', '));
            }
        }
        
        $this->info('Role assignment completed!');
        return 0;
    }
}
