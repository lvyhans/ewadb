<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');
        
        // Seed in proper order
        $this->call([
            RoleSeeder::class,
            TestUsersSeeder::class,
            AssignUserRolesSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('🔑 Demo credentials available:');
        $this->command->line('   👑 Administrator: admin@example.com / password');
        $this->command->line('   👤 Member: member@example.com / password');
        $this->command->newLine();
    }
}
