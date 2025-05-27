<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'view users',
            'create users',
            'edit users',
            'delete users',
            
            // Content management
            'view posts',
            'create posts',
            'edit posts',
            'delete posts',
            
            // General permissions
            'view dashboard',
            'manage settings',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create Admin role - has all permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Create Super Admin role - has all permissions (for company owners)
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Create Members role - has basic permissions
        $members = Role::create(['name' => 'members']);
        $members->givePermissionTo([
            'view posts',
            'create posts',
            'edit posts',
            'view dashboard',
        ]);

        $this->command->info('Admin, Super Admin, and Members roles created successfully!');
    }
}
