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
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(10)->withQueryString();
        
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign roles if provided, otherwise assign default 'user' role
        if ($request->has('roles') && !empty($request->roles)) {
            $user->roles()->sync($request->roles);
        } else {
            $defaultRole = Role::where('name', 'user')->first();
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
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the user
     */
    public function edit(User $user)
    {
        // Check if user can be managed
        if (!$user->canBeManaged()) {
            return redirect()->route('users.index')
                ->with('error', 'The first administrator cannot be edited for security reasons.');
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
        // Check if user can be managed
        if (!$user->canBeManaged()) {
            return redirect()->route('users.index')
                ->with('error', 'The first administrator cannot be deleted for security reasons.');
        }
        
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully!');
    }
}
