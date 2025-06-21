<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Role;
use Illuminate\Console\Command;

class ManageAdminHierarchy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:hierarchy {action} {--admin-email=} {--member-email=} {--group-name=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage admin-member hierarchy. Actions: assign, remove, list, create-admin';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'assign':
                return $this->assignMemberToAdmin();
            case 'remove':
                return $this->removeMemberFromAdmin();
            case 'list':
                return $this->listHierarchy();
            case 'create-admin':
                return $this->createAdmin();
            default:
                $this->error('Invalid action. Available actions: assign, remove, list, create-admin');
                return 1;
        }
    }

    private function assignMemberToAdmin()
    {
        $adminEmail = $this->option('admin-email') ?: $this->ask('Enter admin email');
        $memberEmail = $this->option('member-email') ?: $this->ask('Enter member email');
        $groupName = $this->option('group-name');

        $admin = User::where('email', $adminEmail)->first();
        $member = User::where('email', $memberEmail)->first();

        if (!$admin) {
            $this->error("Admin not found: {$adminEmail}");
            return 1;
        }

        if (!$member) {
            $this->error("Member not found: {$memberEmail}");
            return 1;
        }

        if (!$admin->isRegularAdmin() && !$admin->isSuperAdmin()) {
            $this->error("User {$adminEmail} is not an admin");
            return 1;
        }

        $member->assignToAdmin($admin->id, $groupName);
        
        $this->info("Successfully assigned {$memberEmail} to admin {$adminEmail}");
        if ($groupName) {
            $this->line("Group: {$groupName}");
        }

        return 0;
    }

    private function removeMemberFromAdmin()
    {
        $memberEmail = $this->option('member-email') ?: $this->ask('Enter member email');
        $member = User::where('email', $memberEmail)->first();

        if (!$member) {
            $this->error("Member not found: {$memberEmail}");
            return 1;
        }

        if (!$member->admin_id) {
            $this->error("User {$memberEmail} is not assigned to any admin");
            return 1;
        }

        $adminName = $member->admin->name ?? 'Unknown';
        $member->update(['admin_id' => null, 'admin_group_name' => null]);
        
        $this->info("Successfully removed {$memberEmail} from admin {$adminName}");
        return 0;
    }

    private function listHierarchy()
    {
        $this->info('Admin Hierarchy Overview');
        $this->line('========================');

        $admins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->with(['members', 'roles'])->get();

        foreach ($admins as $admin) {
            $this->line('');
            $adminType = $admin->isSuperAdmin() ? ' (Super Admin - Hidden)' : ' (Regular Admin)';
            $this->info("📋 Admin: {$admin->name} ({$admin->email}){$adminType}");
            $this->line("   Members: {$admin->members->count()}");
            
            if ($admin->members->count() > 0) {
                foreach ($admin->members as $member) {
                    $groupInfo = $member->admin_group_name ? " [{$member->admin_group_name}]" : '';
                    $this->line("   └── {$member->name} ({$member->email}){$groupInfo}");
                }
            } else {
                $this->line("   └── No members assigned");
            }
        }

        // Show orphaned users
        $orphaned = User::orphaned()->get();
        if ($orphaned->count() > 0) {
            $this->line('');
            $this->warn('🔓 Orphaned Users (no admin assigned):');
            foreach ($orphaned as $user) {
                $this->line("   └── {$user->name} ({$user->email})");
            }
        }

        return 0;
    }

    private function createAdmin()
    {
        $email = $this->option('admin-email') ?: $this->ask('Enter new admin email');
        $name = $this->ask('Enter admin name');
        $password = $this->secret('Enter admin password');

        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists");
            return 1;
        }

        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            $this->error('Admin role not found. Please run role seeder first.');
            return 1;
        }

        $admin = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => 1 // Approved by first admin
        ]);

        $admin->roles()->attach($adminRole);

        $this->info("Admin created successfully!");
        $this->line("Name: {$admin->name}");
        $this->line("Email: {$admin->email}");
        $this->line("Status: Approved and ready to manage members");

        return 0;
    }
}
