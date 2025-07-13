<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define authorization gates
        Gate::define('manage settings', function ($user) {
            // Check if user has admin role or is super admin
            try {
                // Load roles if not already loaded
                $user->load('roles');
                
                // Check for admin role directly from roles relationship
                $hasAdminRole = $user->roles()->where('name', 'admin')->exists();
                
                // Check if user is super admin (first admin user)
                $isSuperAdmin = false;
                if ($hasAdminRole) {
                    $firstAdmin = \App\Models\User::whereHas('roles', function($q) {
                        $q->where('name', 'admin');
                    })->orderBy('created_at')->first();
                    
                    $isSuperAdmin = $firstAdmin && $user->id === $firstAdmin->id;
                }
                
                return $hasAdminRole || $isSuperAdmin;
            } catch (\Exception $e) {
                // Log error and deny access for safety
                \Log::error('Settings gate error: ' . $e->getMessage());
                return false;
            }
        });
    }
}
