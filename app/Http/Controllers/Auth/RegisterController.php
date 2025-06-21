<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            // Personal Information
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            
            // Company Information
            'company_name' => 'required|string|max:255',
            'company_registration_number' => 'required|string|max:255',
            'gstin' => 'required|string|size:15|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/',
            'pan_number' => 'required|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
            'company_address' => 'required|string',
            'company_city' => 'required|string|max:255',
            'company_state' => 'required|string|max:255',
            'company_pincode' => 'required|string|size:6|regex:/^[0-9]{6}$/',
            'company_phone' => 'required|string|max:15',
            'company_email' => 'required|email|max:255',
            
            // Required Documents
            'company_registration_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'gst_certificate' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'pan_card' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'address_proof' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'bank_statement' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        // Store uploaded documents
        $documentPaths = [];
        $documents = [
            'company_registration_certificate',
            'gst_certificate',
            'pan_card',
            'address_proof',
            'bank_statement'
        ];

        foreach ($documents as $document) {
            if ($request->hasFile($document)) {
                $file = $request->file($document);
                $filename = time() . '_' . $document . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('company_documents', $filename, 'private');
                $documentPaths[$document] = $path;
            }
        }

        // Create user with company details
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_name' => $validated['company_name'],
            'company_registration_number' => $validated['company_registration_number'],
            'gstin' => $validated['gstin'],
            'pan_number' => $validated['pan_number'],
            'company_address' => $validated['company_address'],
            'company_city' => $validated['company_city'],
            'company_state' => $validated['company_state'],
            'company_pincode' => $validated['company_pincode'],
            'company_phone' => $validated['company_phone'],
            'company_email' => $validated['company_email'],
            'company_registration_certificate' => $documentPaths['company_registration_certificate'] ?? null,
            'gst_certificate' => $documentPaths['gst_certificate'] ?? null,
            'pan_card' => $documentPaths['pan_card'] ?? null,
            'address_proof' => $documentPaths['address_proof'] ?? null,
            'bank_statement' => $documentPaths['bank_statement'] ?? null,
            'approval_status' => 'pending', // All new users start as pending
        ]);

        // Assign role based on registration order
        $userCount = User::count();
        if ($userCount === 1) {
            // First user gets Administrator role and is auto-approved (becomes super admin)
            $adminRole = Role::where('name', 'admin')->first();
            if ($adminRole) {
                $user->roles()->attach($adminRole);
                // Auto-approve first admin user
                $user->update([
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => $user->id // Self-approved
                ]);
            }
        } else {
            // All subsequent users get default Member role and are assigned to an admin
            $memberRole = Role::where('name', 'member')->first();
            if ($memberRole) {
                $user->roles()->attach($memberRole);
            }
            
            // Find an admin to assign this user to
            $this->assignUserToAdmin($user, $request);
        }

        return redirect()->route('registration.success')
            ->with('success', 'Registration submitted successfully! You can now login to your account.');
    }

    /**
     * Assign user to an available admin
     */
    private function assignUserToAdmin($user, $request)
    {
        // Check if there's a specific admin invitation
        $invitingAdminId = $request->get('admin_id');
        
        if ($invitingAdminId) {
            $admin = User::find($invitingAdminId);
            if ($admin && $admin->isRegularAdmin()) {
                $user->assignToAdmin($invitingAdminId, $admin->name . "'s Group");
                return;
            }
        }
        
        // Find the admin with the least members for load balancing
        $availableAdmins = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->get()->filter(function($admin) {
            return $admin->isRegularAdmin();
        });
        
        if ($availableAdmins->isNotEmpty()) {
            // Sort by member count (ascending) to balance load
            $selectedAdmin = $availableAdmins->sortBy(function($admin) {
                return $admin->members()->count();
            })->first();
            
            $user->assignToAdmin($selectedAdmin->id, $selectedAdmin->name . "'s Group");
        }
        // Note: If no admins available, user will remain orphaned but this should be rare
    }

    /**
     * Show registration success page
     */
    public function registrationSuccess()
    {
        return view('auth.registration-success');
    }

    /**
     * Show admin registration form (only accessible by super admin)
     */
    public function showAdminRegistrationForm()
    {
        // Only super admin can access this form
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Only the super administrator can create admin accounts.');
        }
        
        return view('auth.register-admin');
    }

    /**
     * Handle admin registration (only accessible by super admin)
     */
    public function registerAdmin(Request $request)
    {
        // Only super admin can create admin accounts
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            return redirect()->route('users.index')
                ->with('error', 'Only the super administrator can create admin accounts.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'company_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'zip' => 'required|string|max:10',
        ]);

        // Create admin user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'company_name' => $validated['company_name'],
            'phone' => $validated['phone'],
            'city' => $validated['city'],
            'state' => $validated['state'],
            'zip' => $validated['zip'],
            'approval_status' => 'approved', // Admins are auto-approved
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'email_verified_at' => now(), // Auto-verify admin emails
        ]);

        // Assign admin role
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $user->roles()->attach($adminRole);
        }

        return redirect()->route('users.index')
            ->with('success', 'Administrator account created successfully!');
    }
}
