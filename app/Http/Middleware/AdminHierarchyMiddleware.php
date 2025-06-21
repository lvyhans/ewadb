<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminHierarchyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Super admin has access to everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // For user management routes, ensure admin hierarchy is respected
        if ($request->route('user')) {
            $targetUser = $request->route('user');
            
            // Check if current user can access the target user
            if (!$this->canAccessUser($user, $targetUser)) {
                return redirect()->route('users.index')
                    ->with('error', 'You do not have permission to access this user.');
            }
        }

        return $next($request);
    }

    /**
     * Check if current user can access the target user
     */
    private function canAccessUser($currentUser, $targetUser)
    {
        // Users can always access themselves
        if ($currentUser->id === $targetUser->id) {
            return true;
        }
        
        // Admins can access their members
        if ($currentUser->isRegularAdmin() && $targetUser->admin_id === $currentUser->id) {
            return true;
        }
        
        // Members can access other members in the same group
        if ($currentUser->isMember() && $currentUser->admin_id === $targetUser->admin_id) {
            return true;
        }
        
        // Members can access their admin
        if ($currentUser->isMember() && $targetUser->id === $currentUser->admin_id) {
            return true;
        }
        
        return false;
    }
}
