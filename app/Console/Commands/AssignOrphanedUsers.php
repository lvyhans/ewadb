<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class AssignOrphanedUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:assign-orphaned {--auto-create-admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign all orphaned users to available admins';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Scanning for orphaned users...');
        
        // Get all orphaned users (no admin_id and not admins themselves)
        $orphanedUsers = User::orphaned()->get();
        
        if ($orphanedUsers->isEmpty()) {
            $this->info('✅ No orphaned users found!');
            return 0;
        }
        
        $this->warn("Found {$orphanedUsers->count()} orphaned users:");
        foreach ($orphanedUsers as $user) {
            $this->line("   - {$user->name} ({$user->email})");
        }
        
        // Get available regular admins (not super admin)
        $availableAdmins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get()->filter(function($admin) {
            return $admin->isRegularAdmin();
        });
        
        if ($availableAdmins->isEmpty()) {
            $this->warn('⚠️  No regular admins found to assign users to.');
            
            if ($this->option('auto-create-admin')) {
                $this->info('Creating a new admin...');
                return $this->createAdminAndAssign($orphanedUsers);
            } else {
                $this->line('Run with --auto-create-admin to create a new admin automatically.');
                return 1;
            }
        }
        
        $this->info("Found {$availableAdmins->count()} available admins:");
        foreach ($availableAdmins as $admin) {
            $memberCount = $admin->members()->count();
            $this->line("   - {$admin->name} ({$admin->email}) - {$memberCount} members");
        }
        
        // Distribute users evenly among admins
        return $this->distributeUsers($orphanedUsers, $availableAdmins);
    }
    
    private function distributeUsers($orphanedUsers, $availableAdmins)
    {
        $this->info('📝 Assigning users to admins...');
        
        $adminIndex = 0;
        $totalAdmins = $availableAdmins->count();
        
        foreach ($orphanedUsers as $user) {
            $admin = $availableAdmins[$adminIndex % $totalAdmins];
            
            $user->assignToAdmin($admin->id, $admin->name . "'s Group");
            
            $this->line("   ✅ Assigned {$user->name} to {$admin->name}");
            
            $adminIndex++;
        }
        
        $this->info('🎉 All orphaned users have been assigned to admins!');
        return 0;
    }
    
    private function createAdminAndAssign($orphanedUsers)
    {
        $adminEmail = $this->ask('Enter new admin email');
        $adminName = $this->ask('Enter new admin name');
        $adminPassword = $this->secret('Enter new admin password');
        
        if (User::where('email', $adminEmail)->exists()) {
            $this->error("User with email {$adminEmail} already exists");
            return 1;
        }
        
        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            $this->error('Admin role not found. Please run role seeder first.');
            return 1;
        }
        
        // Create new admin
        $admin = User::create([
            'name' => $adminName,
            'email' => $adminEmail,
            'password' => bcrypt($adminPassword),
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => 1
        ]);
        
        $admin->roles()->attach($adminRole);
        
        $this->info("✅ Created new admin: {$admin->name}");
        
        // Assign all orphaned users to this new admin
        foreach ($orphanedUsers as $user) {
            $user->assignToAdmin($admin->id, $admin->name . "'s Group");
            $this->line("   ✅ Assigned {$user->name} to {$admin->name}");
        }
        
        $this->info('🎉 All orphaned users have been assigned to the new admin!');
        return 0;
    }
}
