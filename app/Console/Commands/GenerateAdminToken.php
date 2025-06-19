<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class GenerateAdminToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:admin-token {email?} {--create-admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an admin token for user approval operations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        // If no email provided, ask for it
        if (!$email) {
            $email = $this->ask('Enter admin email');
        }

        // Check if user exists
        $user = User::where('email', $email)->first();

        if (!$user) {
            if ($this->option('create-admin')) {
                // Create new admin user
                $name = $this->ask('Enter admin name', 'Admin User');
                $password = $this->secret('Enter admin password');
                
                $user = User::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'email_verified_at' => now()
                ]);

                $this->info("Admin user created successfully!");
            } else {
                $this->error("User with email {$email} not found!");
                $this->line("Use --create-admin flag to create a new admin user");
                return 1;
            }
        }

        // Generate token
        $tokenName = 'admin-approval-token-' . now()->format('Y-m-d-H-i-s');
        $token = $user->createToken($tokenName, ['*'])->plainTextToken;

        $this->info("Admin token generated successfully!");
        $this->line("User: {$user->name} ({$user->email})");
        $this->line("User ID: {$user->id}");
        $this->line("");
        $this->line("🔑 Your Admin Token:");
        $this->line($token);
        $this->line("");
        $this->line("Use this token to approve users:");
        $this->line("Authorization: Bearer {$token}");
        $this->line("");
        $this->line("Example API call:");
        $this->line("curl -X GET \"http://127.0.0.1:8002/api/external/user-approvals/pending\" \\");
        $this->line("  -H \"Authorization: Bearer {$token}\" \\");
        $this->line("  -H \"Accept: application/json\"");

        return 0;
    }
}
