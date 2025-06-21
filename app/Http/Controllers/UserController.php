<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        $query = User::query();

        // Apply filtering based on user type
        if ($currentUser->isSuperAdmin()) {
            // Super admin can see all users EXCEPT themselves (hidden from dashboard)
            $query->where('id', '!=', $currentUser->id);
        } elseif ($currentUser->isRegularAdmin()) {
            // Regular admins can only see their own members (not other admins)
            $query->where('admin_id', $currentUser->id);
        } else {
            // Members can only see themselves and other members in their group
            if ($currentUser->admin_id) {
                $query->where(function($q) use ($currentUser) {
                    $q->where('admin_id', $currentUser->admin_id)
                      ->orWhere('id', $currentUser->id);
                });
            } else {
                // Orphaned users can only see themselves
                $query->where('id', $currentUser->id);
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->with(['roles', 'admin'])->paginate(10)->withQueryString();
        
        // Get roles from database (exclude admin role for filtering)
        $roles = Role::active()->where('name', '!=', 'admin')->get();

        return view('users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        // Get roles from database (exclude admin role)
        $roles = Role::active()->where('name', '!=', 'admin')->get();
        
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Prevent admin role assignment through user management
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && $request->has('roles') && in_array($adminRole->id, $request->roles)) {
            return back()->withErrors(['roles' => 'Administrator role cannot be assigned through user management.'])->withInput();
        }

        $currentUser = auth()->user();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign to current admin if current user is a regular admin
        if ($currentUser->isRegularAdmin()) {
            $user->assignToAdmin($currentUser->id, $currentUser->name . "'s Group");
        }

        // Assign roles if provided, otherwise assign default 'admin' role
        if ($request->has('roles') && !empty($request->roles)) {
            $user->roles()->sync($request->roles);
        } else {
            $defaultRole = Role::where('name', 'admin')->first();
            if ($defaultRole) {
                $user->roles()->attach($defaultRole);
            }
        }

        return redirect()->route('users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        $currentUser = auth()->user();
        
        // Check if current user can access this user
        if (!$this->canAccessUser($currentUser, $user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to view this user.');
        }
        
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the user
     */
    public function edit(User $user)
    {
        $currentUser = auth()->user();
        
        // Check if current user can access this user
        if (!$this->canAccessUser($currentUser, $user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to edit this user.');
        }
        
        // Check if user can be managed
        if (!$user->canBeManaged()) {
            return redirect()->route('users.index')
                ->with('error', 'The first administrator cannot be edited for security reasons.');
        }
        
        // Regular admins cannot edit admin accounts
        if ($currentUser->isRegularAdmin() && $user->isRegularAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Regular administrators cannot edit admin accounts.');
        }
        
        // Only super admin can edit admin accounts
        if ($user->isRegularAdmin() && !$currentUser->isSuperAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Only the super administrator can edit admin accounts.');
        }
        
        // Get roles from database (exclude admin role)
        $roles = Role::active()->where('name', '!=', 'admin')->get();
        
        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        $currentUser = auth()->user();
        
        // Check if current user can access this user
        if (!$this->canAccessUser($currentUser, $user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to edit this user.');
        }
        
        // Check if user can be managed
        if (!$user->canBeManaged()) {
            return redirect()->route('users.index')
                ->with('error', 'The first administrator cannot be modified for security reasons.');
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Prevent admin role assignment through user management
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && $request->has('roles') && in_array($adminRole->id, $request->roles)) {
            return back()->withErrors(['roles' => 'Administrator role cannot be assigned through user management.'])->withInput();
        }
        
        // Regular admins cannot edit admin accounts
        if ($currentUser->isRegularAdmin() && $user->isRegularAdmin()) {
            return back()->with('error', 'Regular administrators cannot edit admin accounts.');
        }
        
        // Only super admin can edit admin accounts
        if ($user->isRegularAdmin() && !$currentUser->isSuperAdmin()) {
            return back()->with('error', 'Only the super administrator can edit admin accounts.');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Update roles if provided (admin role will be filtered out by validation above)
        if ($request->has('roles')) {
            $user->roles()->sync($request->roles);
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user
     */
    public function destroy(User $user)
    {
        $currentUser = auth()->user();
        
        // Check if current user can access this user
        if (!$this->canAccessUser($currentUser, $user)) {
            return redirect()->route('users.index')
                ->with('error', 'You do not have permission to delete this user.');
        }
        
        // Check if user can be managed
        if (!$user->canBeManaged()) {
            return redirect()->route('users.index')
                ->with('error', 'The first administrator cannot be deleted for security reasons.');
        }
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }
        
        // Regular admins cannot delete admin accounts (including themselves)
        if ($currentUser->isRegularAdmin() && $user->isRegularAdmin()) {
            return back()->with('error', 'Regular administrators cannot delete admin accounts.');
        }
        
        // Only super admin can delete admin accounts
        if ($user->isRegularAdmin() && !$currentUser->isSuperAdmin()) {
            return back()->with('error', 'Only the super administrator can delete admin accounts.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }
    
    /**
     * Check if current user can access the target user
     */
    private function canAccessUser($currentUser, $targetUser)
    {
        // Super admin can access all users except themselves (they're hidden)
        if ($currentUser->isSuperAdmin()) {
            return $targetUser->id !== $currentUser->id;
        }
        
        // Users can always access themselves
        if ($currentUser->id === $targetUser->id) {
            return true;
        }
        
        // Regular admins can access their members only
        if ($currentUser->isRegularAdmin() && $targetUser->admin_id === $currentUser->id) {
            return true;
        }
        
        // Members can access other members in the same group
        if ($currentUser->isMember() && $currentUser->admin_id === $targetUser->admin_id) {
            return true;
        }
        
        return false;
    }
}
