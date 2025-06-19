@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="glass-effect rounded-2xl p-8 border border-white/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-400/20 to-purple-600/20 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            Welcome back, <span class="gradient-text">{{ auth()->user()->name }}</span>! 👋
                        </h1>
                        <p class="text-gray-600 text-lg">Here's what's happening with your CRM today.</p>
                    </div>
                    <div class="hidden lg:block">
                        <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center animate-float">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 mb-8 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Total Users -->
        <div class="glass-effect p-6 rounded-2xl border border-white/20 hover-lift group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::count() }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">+12%</span>
                        <span class="text-xs text-gray-500 ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Admin Users -->
        <div class="glass-effect p-6 rounded-2xl border border-white/20 hover-lift group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Admin Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::where('email', 'like', '%admin%')->count() }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">Active</span>
                        <span class="text-xs text-gray-500 ml-2">All systems</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Member Users -->
        <div class="glass-effect p-6 rounded-2xl border border-white/20 hover-lift group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Members</p>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\User::where('email', 'not like', '%admin%')->count() }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">+8%</span>
                        <span class="text-xs text-gray-500 ml-2">new members</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        </div>

        <!-- API Tokens -->
        <div class="glass-effect p-6 rounded-2xl border border-white/20 hover-lift group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Active Tokens</p>
                    <p class="text-3xl font-bold text-gray-900">{{ auth()->user()->tokens()->count() }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-xs text-yellow-600 bg-yellow-100 px-2 py-1 rounded-full">Secure</span>
                        <span class="text-xs text-gray-500 ml-2">API access</span>
                    </div>
                </div>
                <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-200">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2a2 2 0 00-2 2m0 0a2 2 0 00-2-2m2 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9a2 2 0 012-2h6z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- User Information Card -->
        <div class="lg:col-span-2">
            <div class="glass-effect rounded-2xl border border-white/20 p-8 hover-lift">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">Your Account Information</h3>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="space-y-6">
                    <div class="flex items-center space-x-6">
                        <div class="relative">
                            <img class="w-20 h-20 rounded-2xl ring-4 ring-white/50 shadow-lg" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=667eea&color=fff&size=80" 
                                 alt="{{ auth()->user()->name }}">
                            <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-2 border-white flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-2xl font-bold text-gray-900">{{ auth()->user()->name }}</h4>
                            <p class="text-gray-600 text-lg">{{ auth()->user()->email }}</p>
                            <div class="flex items-center mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Active User
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-white/20">
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                            <p class="text-sm font-medium text-blue-600 mb-1">Account Type</p>
                            <div class="text-lg font-bold text-blue-900">User</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                            <p class="text-sm font-medium text-purple-600 mb-1">Member Since</p>
                            <div class="text-lg font-bold text-purple-900">{{ auth()->user()->created_at->format('M Y') }}</div>
                        </div>
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                            <p class="text-sm font-medium text-green-600 mb-1">Status</p>
                            <div class="text-lg font-bold text-green-900">Verified</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <!-- Quick Actions & Activity -->
        <div class="space-y-6">
            <!-- Quick Actions Card -->
            <div class="glass-effect rounded-2xl border border-white/20 p-6 hover-lift">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Quick Actions</h3>
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="space-y-3">
                    <a href="{{ route('users.create') }}" 
                       class="flex items-center p-4 text-sm text-gray-700 rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 transition-all duration-200 group border border-blue-200">
                        <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-blue-900">Add New User</div>
                            <div class="text-xs text-blue-700">Create a new user account</div>
                        </div>
                        <svg class="w-5 h-5 ml-auto text-blue-600 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="{{ route('users.index') }}" 
                       class="flex items-center p-4 text-sm text-gray-700 rounded-xl bg-gradient-to-r from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 transition-all duration-200 group border border-purple-200">
                        <div class="w-10 h-10 bg-purple-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-purple-900">Manage Users</div>
                            <div class="text-xs text-purple-700">View and edit user accounts</div>
                        </div>
                        <svg class="w-5 h-5 ml-auto text-purple-600 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    
                    <a href="#" 
                       class="flex items-center p-4 text-sm text-gray-700 rounded-xl bg-gradient-to-r from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 transition-all duration-200 group border border-green-200">
                        <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-green-900">View Reports</div>
                            <div class="text-xs text-green-700">Analytics and insights</div>
                        </div>
                        <svg class="w-5 h-5 ml-auto text-green-600 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Recent Activity Card -->
            <div class="glass-effect rounded-2xl border border-white/20 p-6 hover-lift">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Recent Activity</h3>
                    <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center space-x-4 p-3 rounded-xl bg-gradient-to-r from-green-50 to-green-100 border border-green-200">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-green-900">Logged in successfully</p>
                            <p class="text-xs text-green-700">2 minutes ago</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 p-3 rounded-xl bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 00-2-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-blue-900">Dashboard accessed</p>
                            <p class="text-xs text-blue-700">5 minutes ago</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 p-3 rounded-xl bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-purple-900">Profile viewed</p>
                            <p class="text-xs text-purple-700">15 minutes ago</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-4 p-3 rounded-xl bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-yellow-900">Settings updated</p>
                            <p class="text-xs text-yellow-700">1 hour ago</p>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-white/20">
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center group">
                        View all activity
                        <svg class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Dashboard Features -->
    <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- System Status -->
        <div class="glass-effect rounded-2xl border border-white/20 p-6 hover-lift">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">System Status</h3>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                    <span class="text-sm font-medium text-green-600">All Systems Operational</span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border border-green-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-green-900">API Server</span>
                    </div>
                    <span class="text-sm font-bold text-green-600">Online</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-blue-900">Database</span>
                    </div>
                    <span class="text-sm font-bold text-blue-600">Connected</span>
                </div>
                
                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <span class="font-medium text-purple-900">Security</span>
                    </div>
                    <span class="text-sm font-bold text-purple-600">Secure</span>
                </div>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="glass-effect rounded-2xl border border-white/20 p-6 hover-lift">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Performance Metrics</h3>
                <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Response Time</span>
                    <span class="text-sm font-bold text-green-600">234ms</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" style="width: 85%"></div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Uptime</span>
                    <span class="text-sm font-bold text-blue-600">99.9%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" style="width: 99%"></div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">User Satisfaction</span>
                    <span class="text-sm font-bold text-purple-600">4.8/5</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-2 rounded-full" style="width: 96%"></div>
                </div>
            </div>
        </div>
    </div>
@endsection