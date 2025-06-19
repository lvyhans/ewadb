@extends('layouts.app')

@section('title', 'Edit User - ' . $user->name)
@section('page-title', 'Edit User')

@section('content')
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('users.show', $user) }}" 
                   class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit User</h1>
                    <p class="mt-1 text-sm text-gray-600">Update {{ $user->name }}'s account information and permissions</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <form method="POST" action="{{ route('users.update', $user) }}" x-data="editUserForm()" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- User Avatar Section -->
                <div class="flex items-center space-x-6 pb-6 border-b border-gray-200">
                    <img class="w-16 h-16 rounded-full" 
                         src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=3b82f6&color=fff&size=64" 
                         alt="{{ $user->name }}">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <p class="text-xs text-gray-400">Member since {{ $user->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name', $user->name) }}"
                           required
                           autocomplete="name"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('name') border-red-500 @enderror"
                           placeholder="Enter user's full name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <input type="email" 
                           name="email" 
                           id="email"
                           value="{{ old('email', $user->email) }}"
                           required
                           autocomplete="email"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('email') border-red-500 @enderror"
                           placeholder="Enter email address">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Section -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Password Settings</h3>
                        <button type="button" 
                                @click="showPasswordSection = !showPasswordSection"
                                class="text-sm text-blue-600 hover:text-blue-700">
                            <span x-text="showPasswordSection ? 'Cancel Password Change' : 'Change Password'"></span>
                        </button>
                    </div>
                    
                    <div x-show="showPasswordSection" x-transition class="space-y-4">
                        <!-- New Password Field -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                New Password
                            </label>
                            <div class="relative">
                                <input x-bind:type="showPassword ? 'text' : 'password'" 
                                       name="password" 
                                       id="password"
                                       autocomplete="new-password"
                                       class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all @error('password') border-red-500 @enderror"
                                       placeholder="Enter new password (min. 8 characters)">
                                <button type="button" 
                                        @click="showPassword = !showPassword"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Leave blank to keep current password</p>
                        </div>

                        <!-- Confirm Password Field -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm New Password
                            </label>
                            <div class="relative">
                                <input x-bind:type="showConfirmPassword ? 'text' : 'password'" 
                                       name="password_confirmation" 
                                       id="password_confirmation"
                                       autocomplete="new-password"
                                       class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                       placeholder="Confirm new password">
                                <button type="button" 
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600">
                                    <svg x-show="!showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    <svg x-show="showConfirmPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div x-show="!showPasswordSection" class="text-sm text-gray-500 bg-gray-50 rounded-lg p-3">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0h-2m-2-6a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                            </svg>
                            Password will remain unchanged unless you click "Change Password" above.
                        </div>
                    </div>
                </div>

                <!-- Role Selection -->
                <div class="border-t border-gray-200 pt-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        User Roles <span class="text-red-500">*</span>
                    </label>
                    
                    <!-- Show existing admin roles as read-only -->
                    @if($user->roles->where('name', 'admin')->count() > 0)
                        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <h5 class="text-sm font-medium text-red-900 mb-2">Current Administrator Roles (Read-only)</h5>
                            <div class="space-y-2">
                                @foreach($user->roles->where('name', 'admin') as $adminRole)
                                    <div class="flex items-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                            </svg>
                                            {{ $adminRole->display_name }}
                                        </span>
                                        <span class="ml-2 text-xs text-red-600">Cannot be modified</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    
                    <!-- Editable roles (non-admin) -->
                    <div class="space-y-3">
                        @foreach($roles as $role)
                            <label class="flex items-center">
                                <input type="checkbox" 
                                       name="roles[]" 
                                       value="{{ $role->id }}"
                                       {{ in_array($role->id, old('roles', $user->roles->where('name', '!=', 'admin')->pluck('id')->toArray())) ? 'checked' : '' }}
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                <div class="ml-3">
                                    <span class="text-sm font-medium text-gray-900">{{ $role->display_name }}</span>
                                    @if($role->description)
                                        <p class="text-sm text-gray-500">{{ $role->description }}</p>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('roles')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('roles.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <div class="mt-3">
                        <span class="text-sm text-gray-600">All current roles:</span>
                        <div class="mt-1 space-x-2">
                            @forelse($user->roles as $userRole)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                           {{ $userRole->name === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $userRole->display_name }}
                                    @if($userRole->name === 'admin')
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    @endif
                                </span>
                            @empty
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    No roles assigned
                                </span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Role Information -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-blue-900 mb-2">Role System</h4>
                    <p class="text-sm text-blue-800">
                        Users can have multiple roles. Each role grants specific permissions and access levels within the system.
                        Modifying roles will take effect immediately after saving.
                    </p>
                    <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded">
                        <p class="text-xs text-yellow-800">
                            <strong>Note:</strong> Administrator role cannot be assigned or removed through user management for security reasons.
                        </p>
                    </div>
                </div>

                <!-- Email Verification Status -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Account Status</h4>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Email Verification</span>
                            @if($user->email_verified_at)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Verified
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Pending
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Account Status</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('users.show', $user) }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-blue-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update User
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
function editUserForm() {
    return {
        showPassword: false,
        showConfirmPassword: false,
        showPasswordSection: false,
        selectedRole: '{{ old('role', $user->email === 'admin@example.com' ? 'admin' : 'user') }}'
    }
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('password_confirmation');
    
    // Real-time password confirmation validation
    confirmPassword.addEventListener('input', function() {
        if (password.value && this.value !== password.value) {
            this.setCustomValidity('Passwords do not match');
            this.classList.add('border-red-500');
        } else {
            this.setCustomValidity('');
            this.classList.remove('border-red-500');
        }
    });
    
    // Clear password confirmation when main password is cleared
    password.addEventListener('input', function() {
        if (!this.value) {
            confirmPassword.value = '';
            confirmPassword.setCustomValidity('');
            confirmPassword.classList.remove('border-red-500');
        }
    });
});
</script>
@endpush