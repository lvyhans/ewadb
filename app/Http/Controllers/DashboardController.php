<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard
     */
    public function index()
    {
        $currentUser = auth()->user();
        $stats = $this->getDashboardStats($currentUser);
        
        return view('dashboard', compact('stats'));
    }
    
    /**
     * Get dashboard statistics based on user type
     */
    private function getDashboardStats($user)
    {
        if ($user->isSuperAdmin()) {
            return $this->getSuperAdminStats();
        } elseif ($user->isRegularAdmin()) {
            return $this->getRegularAdminStats($user);
        } else {
            return $this->getMemberStats($user);
        }
    }
    
    /**
     * Get statistics for super admin
     */
    private function getSuperAdminStats()
    {
        // Get all users except super admin (who is hidden)
        $totalUsers = User::where('id', '!=', auth()->id())->count();
        
        // Get admin users (excluding super admin)
        $adminUsers = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->where('id', '!=', auth()->id())->count();
        
        // Get member users
        $memberUsers = User::whereHas('roles', function($q) {
            $q->where('name', 'member');
        })->count();
        
        // Get pending approvals
        $pendingApprovals = User::pending()->count();
        
        // Get admin hierarchy details
        $adminHierarchy = User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })
            ->where('id', '!=', auth()->id())
            ->withCount('members')
            ->get();
        
        return [
            'total_users' => $totalUsers,
            'admin_users' => $adminUsers,
            'member_users' => $memberUsers,
            'pending_approvals' => $pendingApprovals,
            'active_tokens' => auth()->user()->tokens()->count(),
            'admin_hierarchy' => $adminHierarchy,
            'user_type' => 'Super Administrator',
            'can_create_admins' => true,
            'hierarchy_view' => true
        ];
    }
    
    /**
     * Get statistics for regular admin
     */
    private function getRegularAdminStats($user)
    {
        // Regular admin can only see their own members
        $myMembers = $user->members()->count();
        $pendingMembers = $user->members()->pending()->count();
        $approvedMembers = $user->members()->approved()->count();
        
        return [
            'total_users' => $myMembers,
            'admin_users' => 0, // Regular admins don't see other admins
            'member_users' => $myMembers,
            'pending_approvals' => $pendingMembers,
            'approved_members' => $approvedMembers,
            'active_tokens' => $user->tokens()->count(),
            'group_name' => $user->name . "'s Group",
            'user_type' => 'Administrator',
            'can_create_admins' => false,
            'hierarchy_view' => false
        ];
    }
    
    /**
     * Get statistics for member
     */
    private function getMemberStats($user)
    {
        // Members can only see basic personal stats
        $groupMembers = 0;
        $adminName = 'Independent';
        
        if ($user->admin_id) {
            $groupMembers = User::where('admin_id', $user->admin_id)->count();
            $admin = $user->admin;
            $adminName = $admin ? $admin->name : 'Unknown Admin';
        }
        
        return [
            'total_users' => $groupMembers,
            'admin_users' => 0,
            'member_users' => $groupMembers,
            'pending_approvals' => 0,
            'active_tokens' => $user->tokens()->count(),
            'admin_name' => $adminName,
            'group_name' => $user->admin_group_name ?: 'Member',
            'user_type' => 'Member',
            'can_create_admins' => false,
            'hierarchy_view' => false
        ];
    }
}
