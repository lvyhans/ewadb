<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super Admin Registration - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            backdrop-filter: blur(16px);
            background: rgba(255, 255, 255, 0.95);
        }
        .section-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.8) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-focus {
            transition: all 0.3s ease;
        }
        .input-focus:focus {
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }
        .button-hover {
            transition: all 0.3s ease;
        }
        .button-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }
        .fade-in {
            animation: fadeIn 0.8s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in-left {
            animation: slideInLeft 0.6s ease-out;
        }
        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .slide-in-right {
            animation: slideInRight 0.6s ease-out 0.2s both;
        }
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .progress-bar {
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            height: 4px;
            border-radius: 2px;
            transition: width 0.3s ease;
        }
    </style>
</head>
<body class="min-h-screen gradient-bg py-8">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 25% 25%, white 2px, transparent 2px), radial-gradient(circle at 75% 75%, white 2px, transparent 2px); background-size: 60px 60px;"></div>
    </div>

    <div class="relative max-w-5xl mx-auto px-4">
        <!-- Header Card -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8 mb-8 fade-in">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full mb-6">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-800 mb-4">Create Your Account</h1>
                <p class="text-lg text-gray-600 mb-6">Register your company to become a Super Administrator</p>
                
                <!-- Progress Bar -->
                <div class="max-w-md mx-auto">
                    <div class="bg-gray-200 rounded-full h-2">
                        <div class="progress-bar w-1/3"></div>
                    </div>
                    <p class="text-sm text-gray-500 mt-2">Step 1 of 3 - Account Setup</p>
                </div>
            </div>
        </div>

        <!-- Registration Form -->
        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" x-data="registrationForm()">
            @csrf

            @if ($errors->any())
                <div class="glass-effect rounded-2xl p-6 mb-8 border-l-4 border-red-500 slide-in-left">
                    <div class="flex items-center mb-3">
                        <svg class="w-6 h-6 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-red-700">Please fix the following errors:</h3>
                    </div>
                    <ul class="list-disc list-inside text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Personal Information Section -->
            <div class="section-card rounded-2xl p-8 mb-8 slide-in-left">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-full mr-4">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Personal Information</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Full Name -->
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                                   class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                   placeholder="Enter your full name">
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-semibold text-gray-700">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                   placeholder="Enter your email address">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <input :type="showPassword ? 'text' : 'password'" id="password" name="password" required
                                   class="input-focus w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                   placeholder="Create a strong password">
                            <button type="button" @click="showPassword = !showPassword" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center hover:text-blue-600 transition-colors">
                                <svg x-show="!showPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div class="space-y-2">
                        <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <input :type="showConfirmPassword ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                                   class="input-focus w-full pl-10 pr-12 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                   placeholder="Confirm your password">
                            <button type="button" @click="showConfirmPassword = !showConfirmPassword" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center hover:text-blue-600 transition-colors">
                                <svg x-show="!showConfirmPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirmPassword" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Information Section -->
            <div class="section-card rounded-2xl p-8 mb-8 slide-in-right">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-full mr-4">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Company Information</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Name -->
                    <div class="space-y-2">
                        <label for="company_name" class="block text-sm font-semibold text-gray-700">
                            Company Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" required
                                   class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                   placeholder="Enter company name">
                        </div>
                    </div>

                    <!-- Company Registration Number -->
                    <div class="space-y-2">
                        <label for="company_registration_number" class="block text-sm font-semibold text-gray-700">
                            Company Registration Number <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <input type="text" id="company_registration_number" name="company_registration_number" value="{{ old('company_registration_number') }}" required
                                   class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                   placeholder="Enter registration number">
                        </div>
                    </div>

                    <!-- GSTIN -->
                    <div class="space-y-2">
                        <label for="gstin" class="block text-sm font-semibold text-gray-700">
                            GSTIN <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500">(15 characters)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="gstin" name="gstin" value="{{ old('gstin') }}" required
                                   maxlength="15" placeholder="22AAAAA0000A1Z5"
                                   class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 uppercase">
                        </div>
                    </div>

                    <!-- PAN Number -->
                    <div class="space-y-2">
                        <label for="pan_number" class="block text-sm font-semibold text-gray-700">
                            PAN Number <span class="text-red-500">*</span>
                            <span class="text-xs text-gray-500">(10 characters)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                                </svg>
                            </div>
                            <input type="text" id="pan_number" name="pan_number" value="{{ old('pan_number') }}" required
                                   maxlength="10" placeholder="ABCDE1234F"
                                   class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 uppercase">
                        </div>
                    </div>

                    <!-- Company Phone -->
                    <div class="space-y-2">
                        <label for="company_phone" class="block text-sm font-semibold text-gray-700">
                            Company Phone <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <input type="tel" id="company_phone" name="company_phone" value="{{ old('company_phone') }}" required
                                   class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                   placeholder="+91 9876543210">
                        </div>
                    </div>

                    <!-- Company Email -->
                    <div class="space-y-2">
                        <label for="company_email" class="block text-sm font-semibold text-gray-700">
                            Company Email <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                </svg>
                            </div>
                            <input type="email" id="company_email" name="company_email" value="{{ old('company_email') }}" required
                                   class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                   placeholder="company@example.com">
                        </div>
                    </div>
                </div>

                <!-- Company Address -->
                <div class="mt-6 space-y-2">
                    <label for="company_address" class="block text-sm font-semibold text-gray-700">
                        Company Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute top-3 left-3 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <textarea id="company_address" name="company_address" rows="3" required
                                  class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                  placeholder="Enter complete company address">{{ old('company_address') }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                    <!-- City -->
                    <div class="space-y-2">
                        <label for="company_city" class="block text-sm font-semibold text-gray-700">
                            City <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <input type="text" id="company_city" name="company_city" value="{{ old('company_city') }}" required
                                   class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                   placeholder="Enter city">
                        </div>
                    </div>

                    <!-- State -->
                    <div class="space-y-2">
                        <label for="company_state" class="block text-sm font-semibold text-gray-700">
                            State <span class="text-red-500">*</span>
                        </label>
                        <select id="company_state" name="company_state" required
                                class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80">
                            <option value="">Select State</option>
                            <option value="Andhra Pradesh" {{ old('company_state') == 'Andhra Pradesh' ? 'selected' : '' }}>Andhra Pradesh</option>
                            <option value="Arunachal Pradesh" {{ old('company_state') == 'Arunachal Pradesh' ? 'selected' : '' }}>Arunachal Pradesh</option>
                            <option value="Assam" {{ old('company_state') == 'Assam' ? 'selected' : '' }}>Assam</option>
                            <option value="Bihar" {{ old('company_state') == 'Bihar' ? 'selected' : '' }}>Bihar</option>
                            <option value="Chhattisgarh" {{ old('company_state') == 'Chhattisgarh' ? 'selected' : '' }}>Chhattisgarh</option>
                            <option value="Goa" {{ old('company_state') == 'Goa' ? 'selected' : '' }}>Goa</option>
                            <option value="Gujarat" {{ old('company_state') == 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                            <option value="Haryana" {{ old('company_state') == 'Haryana' ? 'selected' : '' }}>Haryana</option>
                            <option value="Himachal Pradesh" {{ old('company_state') == 'Himachal Pradesh' ? 'selected' : '' }}>Himachal Pradesh</option>
                            <option value="Jharkhand" {{ old('company_state') == 'Jharkhand' ? 'selected' : '' }}>Jharkhand</option>
                            <option value="Karnataka" {{ old('company_state') == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                            <option value="Kerala" {{ old('company_state') == 'Kerala' ? 'selected' : '' }}>Kerala</option>
                            <option value="Madhya Pradesh" {{ old('company_state') == 'Madhya Pradesh' ? 'selected' : '' }}>Madhya Pradesh</option>
                            <option value="Maharashtra" {{ old('company_state') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                            <option value="Manipur" {{ old('company_state') == 'Manipur' ? 'selected' : '' }}>Manipur</option>
                            <option value="Meghalaya" {{ old('company_state') == 'Meghalaya' ? 'selected' : '' }}>Meghalaya</option>
                            <option value="Mizoram" {{ old('company_state') == 'Mizoram' ? 'selected' : '' }}>Mizoram</option>
                            <option value="Nagaland" {{ old('company_state') == 'Nagaland' ? 'selected' : '' }}>Nagaland</option>
                            <option value="Odisha" {{ old('company_state') == 'Odisha' ? 'selected' : '' }}>Odisha</option>
                            <option value="Punjab" {{ old('company_state') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                            <option value="Rajasthan" {{ old('company_state') == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                            <option value="Sikkim" {{ old('company_state') == 'Sikkim' ? 'selected' : '' }}>Sikkim</option>
                            <option value="Tamil Nadu" {{ old('company_state') == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                            <option value="Telangana" {{ old('company_state') == 'Telangana' ? 'selected' : '' }}>Telangana</option>
                            <option value="Tripura" {{ old('company_state') == 'Tripura' ? 'selected' : '' }}>Tripura</option>
                            <option value="Uttar Pradesh" {{ old('company_state') == 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                            <option value="Uttarakhand" {{ old('company_state') == 'Uttarakhand' ? 'selected' : '' }}>Uttarakhand</option>
                            <option value="West Bengal" {{ old('company_state') == 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                            <option value="Delhi" {{ old('company_state') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                            <option value="Chandigarh" {{ old('company_state') == 'Chandigarh' ? 'selected' : '' }}>Chandigarh</option>
                            <option value="Puducherry" {{ old('company_state') == 'Puducherry' ? 'selected' : '' }}>Puducherry</option>
                        </select>
                    </div>

                    <!-- Pincode -->
                    <div class="space-y-2">
                        <label for="company_pincode" class="block text-sm font-semibold text-gray-700">
                            Pincode <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                </svg>
                            </div>
                            <input type="text" id="company_pincode" name="company_pincode" value="{{ old('company_pincode') }}" required
                                   maxlength="6" pattern="[0-9]{6}"
                                   class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80"
                                   placeholder="123456">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Document Upload Section -->
            <div class="section-card rounded-2xl p-8 mb-8 slide-in-left">
                <div class="flex items-center mb-6">
                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-full mr-4">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-gray-800">Required Documents</h2>
                        <p class="text-sm text-gray-600 mt-1">Upload the following documents (PDF, JPG, JPEG, PNG - Max 5MB each)</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Registration Certificate -->
                    <div class="space-y-2">
                        <label for="company_registration_certificate" class="block text-sm font-semibold text-gray-700">
                            Company Registration Certificate <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" id="company_registration_certificate" name="company_registration_certificate" required
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- GST Certificate -->
                    <div class="space-y-2">
                        <label for="gst_certificate" class="block text-sm font-semibold text-gray-700">
                            GST Certificate <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" id="gst_certificate" name="gst_certificate" required
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- PAN Card -->
                    <div class="space-y-2">
                        <label for="pan_card" class="block text-sm font-semibold text-gray-700">
                            PAN Card <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" id="pan_card" name="pan_card" required
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- Address Proof -->
                    <div class="space-y-2">
                        <label for="address_proof" class="block text-sm font-semibold text-gray-700">
                            Address Proof <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" id="address_proof" name="address_proof" required
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>

                    <!-- Bank Statement -->
                    <div class="md:col-span-2 space-y-2">
                        <label for="bank_statement" class="block text-sm font-semibold text-gray-700">
                            Bank Statement (Last 3 months) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" id="bank_statement" name="bank_statement" required
                                   accept=".pdf,.jpg,.jpeg,.png"
                                   class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white/80 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms and Submit Section -->
            <div class="glass-effect rounded-2xl p-8 slide-in-right">
                <!-- Terms and Conditions -->
                <div class="mb-8">
                    <label class="inline-flex items-start">
                        <input type="checkbox" required class="mt-1 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-3 text-sm text-gray-700">
                            I agree to the <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold">Terms and Conditions</a> and 
                            <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold">Privacy Policy</a>. 
                            I understand that my application will be reviewed and I will be notified of the approval status.
                        </span>
                    </label>
                </div>

                <!-- Submit Section -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Already have an account? Sign in
                    </a>
                    <button type="submit" 
                            class="button-hover bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold px-8 py-3 rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Submit Registration
                        </span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function registrationForm() {
            return {
                showPassword: false,
                showConfirmPassword: false
            }
        }

        // Auto-format inputs
        document.addEventListener('DOMContentLoaded', function() {
            // GSTIN formatting
            const gstinInput = document.getElementById('gstin');
            if (gstinInput) {
                gstinInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.toUpperCase();
                });
            }

            // PAN formatting
            const panInput = document.getElementById('pan_number');
            if (panInput) {
                panInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.toUpperCase();
                });
            }

            // Pincode validation
            const pincodeInput = document.getElementById('company_pincode');
            if (pincodeInput) {
                pincodeInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '');
                });
            }

            // Phone number formatting
            const phoneInput = document.getElementById('company_phone');
            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '');
                });
            }
        });
    </script>
</body>
</html>