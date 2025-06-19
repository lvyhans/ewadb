<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating demo users...');
        
        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]
        );

        // Create Member User
        $member = User::updateOrCreate(
            ['email' => 'member@example.com'],
            [
                'name' => 'Member User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'approval_status' => 'approved',
                'approved_at' => now(),
            ]
        );

        $this->command->info('✅ Demo users created/updated successfully!');
    }
}
