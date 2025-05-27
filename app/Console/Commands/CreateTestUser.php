<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:test-user {email} {password} {--name=Test User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test user for API authentication';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->option('name');

        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }

        // Create the user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now(),
            'approval_status' => 'approved'
        ]);

        $this->info("Test user created successfully!");
        $this->line("Email: {$email}");
        $this->line("Password: {$password}");
        $this->line("User ID: {$user->id}");
        $this->line("");
        $this->line("You can now login using:");
        $this->line("POST /api/auth/login");
        $this->line("Body: {\"email\": \"{$email}\", \"password\": \"{$password}\"}");

        return 0;
    }
}
