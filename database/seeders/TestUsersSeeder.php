<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
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

        $this->command->info('Demo users created/updated successfully!');
        $this->command->info('Admin: admin@example.com / password');
        $this->command->info('Member: member@example.com / password');
    }
}
