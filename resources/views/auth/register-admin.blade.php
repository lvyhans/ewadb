<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Administrator - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'bounce-subtle': 'bounceSubtle 2s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' }
                        },
                        bounceSubtle: {
                            '0%, 20%, 50%, 80%, 100%': { transform: 'translateY(0)' },
                            '40%': { transform: 'translateY(-3px)' },
                            '60%': { transform: 'translateY(-1px)' }
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 min-h-screen relative overflow-x-hidden">
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/10 to-indigo-600/10 rounded-full blur-3xl animate-bounce-subtle"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-indigo-400/10 to-purple-600/10 rounded-full blur-3xl animate-bounce-subtle" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-blue-300/5 to-indigo-400/5 rounded-full blur-3xl animate-bounce-subtle" style="animation-delay: 2s;"></div>
    </div>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl w-full space-y-8 animate-fade-in">
            <!-- Header Section -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 flex items-center justify-center rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 shadow-lg mb-6 animate-bounce-subtle">
                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-1.5h-16c-.828 0-1.5-.672-1.5-1.5v-6c0-.828.672-1.5 1.5 1.5v6c0 0-.672 1.5-1.5 1.5z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 bg-clip-text text-transparent mb-3">
                    Create New Administrator
                </h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Add a new administrator to the system with comprehensive management capabilities and full access privileges
                </p>
                <div class="mt-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200/50 rounded-xl p-4 shadow-sm">
                    <p class="text-sm text-blue-800 flex items-center justify-center">
                        <svg class="inline h-5 w-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                        Only Super Administrators can create new admin accounts
                    </p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-2xl border border-white/20 overflow-hidden">
                <!-- Progress Indicator -->
                <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-1">
                    <div class="h-full bg-gradient-to-r from-white/30 to-white/10 w-full"></div>
                </div>
                
                <form method="POST" action="{{ route('admin.register') }}" x-data="adminForm()" @submit="onSubmit" class="space-y-8 p-8 lg:p-10">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">
                                        There were errors with your submission
                                    </h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Personal Information Section -->
                    <div class="border-b border-gray-200/60 pb-8">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 mr-4">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Personal Information</h3>
                                <p class="text-sm text-gray-500 mt-1">Basic details about the new administrator</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Full Name -->
                            <div class="group">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           name="name" 
                                           id="name"
                                           value="{{ old('name') }}"
                                           required
                                           x-model="formData.name"
                                           class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('name') border-red-500 ring-red-500 @enderror hover:border-gray-400 bg-white/50 backdrop-blur-sm"
                                           placeholder="Enter full name">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="group">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                    Email Address <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="email" 
                                           name="email" 
                                           id="email"
                                           value="{{ old('email') }}"
                                           required
                                           x-model="formData.email"
                                           class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('email') border-red-500 ring-red-500 @enderror hover:border-gray-400 bg-white/50 backdrop-blur-sm"
                                           placeholder="admin@company.com">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('email')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="border-b border-gray-200/60 pb-8">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-teal-600 mr-4">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Security Credentials</h3>
                                <p class="text-sm text-gray-500 mt-1">Set up secure authentication for the administrator</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Password -->
                            <div class="group">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                    Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" 
                                           name="password" 
                                           id="password"
                                           required
                                           x-ref="password"
                                           x-model="password"
                                           @input="checkPasswordStrength"
                                           class="w-full px-4 py-3 pl-11 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('password') border-red-500 ring-red-500 @enderror hover:border-gray-400 bg-white/50 backdrop-blur-sm"
                                           placeholder="Enter secure password">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <button type="button" 
                                            @click="showPassword = !showPassword"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center hover:text-indigo-600 transition-colors">
                                        <svg x-show="!showPassword" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                <!-- Enhanced Password Strength Indicator -->
                                <div x-show="password.length > 0" class="mt-3 animate-slide-up">
                                    <div class="flex items-center space-x-3">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2.5 overflow-hidden">
                                            <div :class="strengthColor" 
                                                 :style="`width: ${strengthPercentage}%`"
                                                 class="h-2.5 rounded-full transition-all duration-500 ease-out"></div>
                                        </div>
                                        <span :class="strengthTextColor" 
                                              class="text-xs font-semibold px-2 py-1 rounded-full" 
                                              x-text="strengthText"></span>
                                    </div>
                                    <div class="mt-2 text-xs text-gray-500 space-y-1">
                                        <div class="flex items-center space-x-2">
                                            <div :class="password.length >= 8 ? 'text-green-500' : 'text-gray-400'" class="flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>8+ characters</span>
                                            </div>
                                            <div :class="/[A-Z]/.test(password) ? 'text-green-500' : 'text-gray-400'" class="flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>Uppercase</span>
                                            </div>
                                            <div :class="/[0-9]/.test(password) ? 'text-green-500' : 'text-gray-400'" class="flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>Number</span>
                                            </div>
                                            <div :class="/[^A-Za-z0-9]/.test(password) ? 'text-green-500' : 'text-gray-400'" class="flex items-center">
                                                <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                <span>Special</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="group">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input :type="showConfirmPassword ? 'text' : 'password'" 
                                           name="password_confirmation" 
                                           id="password_confirmation"
                                           required
                                           x-ref="confirmPassword"
                                           x-model="confirmPassword"
                                           @input="checkPasswordMatch"
                                           class="w-full px-4 py-3 pl-11 pr-12 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 hover:border-gray-400 bg-white/50 backdrop-blur-sm"
                                           :class="passwordMatch === false ? 'border-red-500 ring-red-500' : ''"
                                           placeholder="Confirm password">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                        </svg>
                                    </div>
                                    <button type="button" 
                                            @click="showConfirmPassword = !showConfirmPassword"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center hover:text-indigo-600 transition-colors">
                                        <svg x-show="!showConfirmPassword" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <svg x-show="showConfirmPassword" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div x-show="confirmPassword.length > 0" class="mt-2 animate-slide-up">
                                    <div x-show="passwordMatch === false" class="flex items-center text-red-600">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        <p class="text-sm">Passwords do not match</p>
                                    </div>
                                    <div x-show="passwordMatch === true" class="flex items-center text-green-600">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <p class="text-sm font-medium">Passwords match perfectly!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Information Section -->
                    <div class="border-b border-gray-200/60 pb-8">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600 mr-4">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Company Details</h3>
                                <p class="text-sm text-gray-500 mt-1">Business information and contact details</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <!-- Company Name -->
                            <div class="group">
                                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                    Company Name <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           name="company_name" 
                                           id="company_name"
                                           value="{{ old('company_name') }}"
                                           required
                                           x-model="formData.company_name"
                                           class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('company_name') border-red-500 ring-red-500 @enderror hover:border-gray-400 bg-white/50 backdrop-blur-sm"
                                           placeholder="Enter company name">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('company_name')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="group">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="tel" 
                                           name="phone" 
                                           id="phone"
                                           value="{{ old('phone') }}"
                                           required
                                           x-model="formData.phone"
                                           @input="formatPhone"
                                           class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('phone') border-red-500 ring-red-500 @enderror hover:border-gray-400 bg-white/50 backdrop-blur-sm"
                                           placeholder="+91 XXXXX XXXXX">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('phone')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Location Information Section -->
                    <div class="pb-8">
                        <div class="flex items-center mb-6">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 mr-4">
                                <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900">Location Details</h3>
                                <p class="text-sm text-gray-500 mt-1">Geographic information for the administrator</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- City -->
                            <div class="group">
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           name="city" 
                                           id="city"
                                           value="{{ old('city') }}"
                                           required
                                           x-model="formData.city"
                                           class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('city') border-red-500 ring-red-500 @enderror hover:border-gray-400 bg-white/50 backdrop-blur-sm"
                                           placeholder="Enter city">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('city')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- State -->
                            <div class="group">
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           name="state" 
                                           id="state"
                                           value="{{ old('state') }}"
                                           required
                                           x-model="formData.state"
                                           class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('state') border-red-500 ring-red-500 @enderror hover:border-gray-400 bg-white/50 backdrop-blur-sm"
                                           placeholder="Enter state">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('state')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- ZIP Code -->
                            <div class="group">
                                <label for="zip" class="block text-sm font-medium text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                    ZIP Code <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" 
                                           name="zip" 
                                           id="zip"
                                           value="{{ old('zip') }}"
                                           required
                                           pattern="[0-9]{5,6}"
                                           x-model="formData.zip"
                                           class="w-full px-4 py-3 pl-11 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 @error('zip') border-red-500 ring-red-500 @enderror hover:border-gray-400 bg-white/50 backdrop-blur-sm"
                                           placeholder="XXXXXX">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                </div>
                                @error('zip')
                                    <p class="mt-2 text-sm text-red-600 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Admin Privileges Notice -->
                    <div class="bg-gradient-to-r from-indigo-50 via-blue-50 to-purple-50 border border-indigo-200/60 rounded-2xl p-6 shadow-sm">
                        <div class="flex items-start">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 mr-4 flex-shrink-0 animate-bounce-subtle">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-indigo-900 mb-2">Administrator Privileges</h4>
                                <p class="text-sm text-indigo-700 mb-3 leading-relaxed">
                                    This user will be granted comprehensive administrative access to the system with the following capabilities:
                                </p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div class="flex items-center text-sm text-indigo-700">
                                        <div class="w-2 h-2 bg-indigo-500 rounded-full mr-3 flex-shrink-0"></div>
                                        <span>Manage users and approval workflows</span>
                                    </div>
                                    <div class="flex items-center text-sm text-indigo-700">
                                        <div class="w-2 h-2 bg-indigo-500 rounded-full mr-3 flex-shrink-0"></div>
                                        <span>Access admin hierarchy management</span>
                                    </div>
                                    <div class="flex items-center text-sm text-indigo-700">
                                        <div class="w-2 h-2 bg-indigo-500 rounded-full mr-3 flex-shrink-0"></div>
                                        <span>Review and process registrations</span>
                                    </div>
                                    <div class="flex items-center text-sm text-indigo-700">
                                        <div class="w-2 h-2 bg-indigo-500 rounded-full mr-3 flex-shrink-0"></div>
                                        <span>Access all system features and settings</span>
                                    </div>
                                </div>
                                <div class="mt-4 p-3 bg-white/50 rounded-lg border border-indigo-200/50">
                                    <p class="text-xs text-indigo-600 font-medium">
                                        ⚠️ Important: Administrator accounts require approval before gaining full access to the system.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-8 border-t border-gray-200/60">
                        <div class="flex items-center text-sm text-gray-500">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>All fields marked with * are required</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('users.index') }}" 
                               class="px-6 py-3 border border-gray-300 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                <span>Cancel</span>
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3 border border-transparent rounded-xl text-sm font-medium text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 shadow-lg hover:shadow-xl transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200 flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span>Create Administrator</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function adminForm() {
            return {
                showPassword: false,
                showConfirmPassword: false,
                password: '',
                confirmPassword: '',
                passwordMatch: null,
                strengthScore: 0,
                // Track form field values for reactivity
                formData: {
                    name: '',
                    email: '',
                    company_name: '',
                    phone: '',
                    city: '',
                    state: '',
                    zip: ''
                },
                
                init() {
                    // Add smooth animations and focus management
                    this.$nextTick(() => {
                        // Auto-focus first input
                        document.getElementById('name')?.focus();
                    });
                },
                
                get strengthPercentage() {
                    return Math.min((this.strengthScore / 5) * 100, 100);
                },
                
                get strengthColor() {
                    if (this.strengthScore <= 1) return 'bg-gradient-to-r from-red-400 to-red-500';
                    if (this.strengthScore <= 2) return 'bg-gradient-to-r from-yellow-400 to-orange-500';
                    if (this.strengthScore <= 3) return 'bg-gradient-to-r from-blue-400 to-blue-500';
                    if (this.strengthScore <= 4) return 'bg-gradient-to-r from-green-400 to-green-500';
                    return 'bg-gradient-to-r from-emerald-400 to-emerald-500';
                },
                
                get strengthText() {
                    if (this.strengthScore <= 1) return 'Weak';
                    if (this.strengthScore <= 2) return 'Fair';
                    if (this.strengthScore <= 3) return 'Good';
                    if (this.strengthScore <= 4) return 'Strong';
                    return 'Excellent';
                },
                
                get strengthTextColor() {
                    if (this.strengthScore <= 1) return 'text-red-600 bg-red-100';
                    if (this.strengthScore <= 2) return 'text-orange-600 bg-orange-100';
                    if (this.strengthScore <= 3) return 'text-blue-600 bg-blue-100';
                    if (this.strengthScore <= 4) return 'text-green-600 bg-green-100';
                    return 'text-emerald-600 bg-emerald-100';
                },
                
                get formValid() {
                    // Check form data reactively
                    const allFieldsFilled = this.formData.name.trim().length > 0 &&
                                          this.formData.email.trim().length > 0 &&
                                          this.formData.company_name.trim().length > 0 &&
                                          this.formData.phone.trim().length > 0 &&
                                          this.formData.city.trim().length > 0 &&
                                          this.formData.state.trim().length > 0 &&
                                          this.formData.zip.trim().length > 0;
                    
                    const passwordValid = this.password && this.password.length >= 8;
                    const passwordsMatch = this.passwordMatch === true;
                    const strengthGood = this.strengthScore >= 3;
                    
                    return allFieldsFilled && passwordValid && passwordsMatch && strengthGood;
                },
                
                checkPasswordStrength() {
                    // this.password is automatically updated via x-model
                    let score = 0;
                    
                    // Length check
                    if (this.password.length >= 8) score++;
                    if (this.password.length >= 12) score++;
                    
                    // Character variety checks
                    if (/[a-z]/.test(this.password)) score++;
                    if (/[A-Z]/.test(this.password)) score++;
                    if (/[0-9]/.test(this.password)) score++;
                    if (/[^A-Za-z0-9]/.test(this.password)) score++;
                    
                    // Bonus for complexity
                    if (this.password.length >= 16 && score >= 4) score++;
                    
                    this.strengthScore = Math.min(score, 5);
                    this.checkPasswordMatch();
                },
                
                checkPasswordMatch() {
                    // this.confirmPassword is automatically updated via x-model
                    if (this.confirmPassword.length === 0) {
                        this.passwordMatch = null;
                    } else {
                        this.passwordMatch = this.password === this.confirmPassword;
                    }
                },
                
                validateEmail(email) {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    return re.test(email);
                },
                
                validatePhone(phone) {
                    const re = /^[\+]?[1-9][\d]{0,15}$/;
                    return re.test(phone.replace(/\s+/g, ''));
                },
                
                formatPhone(event) {
                    let value = event.target.value.replace(/\D/g, '');
                    if (value.startsWith('91')) {
                        value = '+' + value;
                    }
                    event.target.value = value;
                },
                
                onSubmit(event) {
                    // Always allow form submission - let server handle validation
                    console.log('Form submission attempt');
                    
                    // Add loading state
                    const submitButton = event.target.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.innerHTML = `
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Creating Administrator...
                        `;
                    }
                    
                    // Allow form to submit
                    return true;
                },
                
                showValidationErrors() {
                    // Scroll to first error field
                    const requiredFields = ['name', 'email', 'company_name', 'phone', 'city', 'state', 'zip'];
                    for (let field of requiredFields) {
                        const element = document.getElementById(field);
                        if (!element || element.value.trim().length === 0) {
                            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            element.focus();
                            break;
                        }
                    }
                }
            }
        }
        
        // Add global form enhancements
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling for focus events
            const inputs = document.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    this.parentElement.classList.add('ring-2', 'ring-indigo-500');
                });
                
                input.addEventListener('blur', function() {
                    this.parentElement.classList.remove('ring-2', 'ring-indigo-500');
                });
            });
            
            // Add keyboard navigation enhancements
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName === 'INPUT') {
                    e.preventDefault();
                    const form = e.target.closest('form');
                    const inputs = Array.from(form.querySelectorAll('input[required]'));
                    const currentIndex = inputs.indexOf(e.target);
                    const nextInput = inputs[currentIndex + 1];
                    
                    if (nextInput) {
                        nextInput.focus();
                    } else {
                        // Focus submit button if all required fields are filled
                        const submitButton = form.querySelector('button[type="submit"]');
                        if (submitButton && !submitButton.disabled) {
                            submitButton.focus();
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>
