<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating system roles...');
        
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system access with all permissions',
                'is_active' => true
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager',
                'description' => 'Management level access with team oversight',
                'is_active' => true
            ],
            [
                'name' => 'user',
                'display_name' => 'User',
                'description' => 'Standard user access with basic permissions',
                'is_active' => true
            ],
            [
                'name' => 'guest',
                'display_name' => 'Guest',
                'description' => 'Limited access for guest users',
                'is_active' => true
            ]
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                $role
            );
        }
        
        $this->command->info('✅ System roles created/updated successfully!');
    }
}
