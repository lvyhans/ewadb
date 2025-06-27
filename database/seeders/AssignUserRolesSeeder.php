<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssignUserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Clearing existing user roles and adding new data...');
        
        // Disable foreign key checks temporarily
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Clear existing user roles
        DB::table('user_roles')->truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // User roles data from your provided SQL
        $userRoles = [
            ['id' => 1, 'user_id' => 1, 'role_id' => 1, 'created_at' => null, 'updated_at' => null],
            ['id' => 2, 'user_id' => 2, 'role_id' => 1, 'created_at' => null, 'updated_at' => null],
            ['id' => 5, 'user_id' => 5, 'role_id' => 3, 'created_at' => null, 'updated_at' => null],
            ['id' => 13, 'user_id' => 16, 'role_id' => 1, 'created_at' => null, 'updated_at' => null],
            ['id' => 14, 'user_id' => 17, 'role_id' => 1, 'created_at' => null, 'updated_at' => null],
            ['id' => 15, 'user_id' => 18, 'role_id' => 1, 'created_at' => null, 'updated_at' => null],
            ['id' => 17, 'user_id' => 20, 'role_id' => 1, 'created_at' => null, 'updated_at' => null],
            ['id' => 18, 'user_id' => 21, 'role_id' => 1, 'created_at' => null, 'updated_at' => null],
            ['id' => 19, 'user_id' => 22, 'role_id' => 3, 'created_at' => null, 'updated_at' => null],
            ['id' => 20, 'user_id' => 23, 'role_id' => 3, 'created_at' => null, 'updated_at' => null],
            ['id' => 22, 'user_id' => 24, 'role_id' => 3, 'created_at' => null, 'updated_at' => null],
            ['id' => 23, 'user_id' => 15, 'role_id' => 3, 'created_at' => null, 'updated_at' => null],
        ];

        // Insert user roles data
        foreach ($userRoles as $userRole) {
            // Only insert if both user and role exist
            $userExists = User::find($userRole['user_id']);
            $roleExists = Role::find($userRole['role_id']);
            
            if ($userExists && $roleExists) {
                DB::table('user_roles')->insert($userRole);
                $this->command->info("Assigned role {$userRole['role_id']} to user {$userRole['user_id']} ({$userExists->name})");
            } else {
                if (!$userExists) {
                    $this->command->warn("User with ID {$userRole['user_id']} not found, skipping...");
                }
                if (!$roleExists) {
                    $this->command->warn("Role with ID {$userRole['role_id']} not found, skipping...");
                }
            }
        }

        $this->command->info('✅ User roles assignment completed!');
        $this->command->info('Total assignments processed: ' . count($userRoles));
    }
}
