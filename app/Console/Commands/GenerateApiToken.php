<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateApiToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:api-token {name} {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate API token for external teams';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $email = $this->option('email') ?: $name . '@external.team';

        // Create a user for the external team if it doesn't exist
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => bcrypt('random_password_' . time()),
                'email_verified_at' => now(),
            ]
        );

        // Generate a new token
        $token = $user->createToken($name . '_api_token')->plainTextToken;

        $this->info("API Token generated successfully for: {$name}");
        $this->info("Email: {$email}");
        $this->line("Token: {$token}");
        $this->warn("Keep this token secure and provide it to the external team.");
        
        return 0;
    }
}
