<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'approval_status' => 'pending', // Set as pending by default
        ]);

        // Assign default role (you can customize this)
        $user->assignRole('members');

        // Don't create token during registration - user needs approval first
        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully. Your account is pending approval.',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'approval_status' => $user->approval_status,
                ],
                'note' => 'You will receive a notification once your account is approved.'
            ]
        ], 201);
    }

    /**
     * Login user and create token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email', $request->email)->first();
        
        // Check if user is approved
        if (!$user->isApproved()) {
            $message = match($user->approval_status) {
                'pending' => 'Your account is pending approval. Please wait for admin approval.',
                'rejected' => 'Your account has been rejected. Reason: ' . ($user->rejection_reason ?? 'Not specified'),
                default => 'Your account is not approved for login.'
            };
            
            return response()->json([
                'status' => 'error',
                'message' => $message,
                'data' => [
                    'approval_status' => $user->approval_status,
                    'rejection_reason' => $user->rejection_reason
                ]
            ], 403);
        }
        
        // Delete old tokens (optional - for single device login)
        // $user->tokens()->delete();
        
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'user' => $user->load('roles', 'permissions'),
                'token' => $token,
                'expires_at' => now()->addDays(30)->toISOString()
            ]
        ]);
    }

    /**
     * Logout user (revoke token)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logged out from all devices successfully'
        ]);
    }

    /**
     * Get authenticated user profile
     */
    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user()->load('roles', 'permissions')
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only(['name', 'email']));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user->load('roles', 'permissions')
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Optionally revoke all tokens to force re-login
        // $user->tokens()->delete();

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

    /**
     * Get registration token for a newly registered user (for testing/admin purposes)
     */
    public function getRegistrationToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'admin_secret' => 'required|string',
        ]);

        // Simple security check - you can make this more sophisticated
        if ($request->admin_secret !== config('app.admin_secret', 'admin123')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid admin secret'
            ], 403);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);
        }

        // Generate a temporary token for this user (even if not approved)
        $token = $user->createToken('registration-token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Registration token generated',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'approval_status' => $user->approval_status,
                ],
                'token' => $token,
                'note' => 'This token can be used to approve the user via API'
            ]
        ]);
    }
}
