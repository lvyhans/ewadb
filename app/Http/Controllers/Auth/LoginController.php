<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show the login form
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if user exists and credentials are correct
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $user = Auth::user();
            
            // Check if user is approved
            if ($user->approval_status !== 'approved') {
                Auth::logout();
                
                $message = match($user->approval_status) {
                    'pending' => 'Your account is pending approval. Please wait for administrator approval.',
                    'rejected' => 'Your account has been rejected. Reason: ' . ($user->rejection_reason ?: 'No reason provided.'),
                    default => 'Your account is not approved for login.'
                };
                
                throw ValidationException::withMessages([
                    'email' => [$message],
                ]);
            }
            
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        throw ValidationException::withMessages([
            'email' => ['The provided credentials do not match our records.'],
        ]);
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
