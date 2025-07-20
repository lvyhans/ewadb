@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="min-h-screen from-slate-50 via-blue-50 to-indigo-100">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Welcome Hero Section -->
        <div class="mb-8">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-400/10 to-purple-600/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-emerald-400/10 to-teal-600/10 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                        <div class="mb-6 lg:mb-0">
                            <div class="flex items-center mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h1 class="text-4xl font-bold text-gray-900 mb-2">
                                        Welcome back, <span class="bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">{{ auth()->user()->name }}</span>! 👋
                                    </h1>
                                    <p class="text-lg text-gray-600">B2B Management System</p>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    System Status: {{ $stats['api_status'] ?? 'Online' }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Last Login: {{ auth()->user()->updated_at->format('M d, Y H:i') }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    Role: {{ ucfirst(auth()->user()->role) }}
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button onclick="window.location.reload()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-xl transition-all duration-200 flex items-center font-medium shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh
                            </button>
                            <a href="{{ route('notifications.index') }}" class="bg-gradient-to-r from-blue-500 to-purple-600 hover:from-blue-600 hover:to-purple-700 text-white px-6 py-3 rounded-xl transition-all duration-200 flex items-center font-medium shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM11 13h4m-4-4h4m-4-4h4M7 13h1m-1-4h1m-1-4h1"></path>
                                </svg>
                                View Notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Metrics Dashboard -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">
                            @if($stats['hierarchy_view'])
                                Total Users (System)
                            @elseif(auth()->user()->isRegularAdmin())
                                My Members
                            @else
                                Group Members
                            @endif
                        </p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_users'] }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                @if($stats['hierarchy_view'])
                                    System Wide
                                @else
                                    Your Network
                                @endif
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Leads -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Total Leads</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_leads'] }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs text-blue-600 bg-blue-100 px-2 py-1 rounded-full">
                                +{{ $stats['new_leads_this_month'] }} this month
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-emerald-500 to-green-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Applications -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Active Applications</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_applications'] }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                                {{ $stats['pending_applications'] }} pending
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Pending Tasks -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Pending Tasks</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['pending_tasks'] }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs text-amber-600 bg-amber-100 px-2 py-1 rounded-full">
                                Requires attention
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v2m0 0v6a2 2 0 002 2h6a2 2 0 002-2V9m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m-6 4h.01M15 13h.01M9 17h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Follow-up Metrics Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Follow-ups -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Total Follow-ups</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_followups'] }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs text-indigo-600 bg-indigo-100 px-2 py-1 rounded-full">
                                All time
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Today's Follow-ups -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Today's Follow-ups</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['today_followups'] }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs text-green-600 bg-green-100 px-2 py-1 rounded-full">
                                Due today
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Overdue Follow-ups -->
            <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Overdue Follow-ups</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['overdue_followups'] }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs text-red-600 bg-red-100 px-2 py-1 rounded-full">
                                Needs attention
                            </span>
                        </div>
                    </div>
                    <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-rose-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytics & Insights Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Analytics & Insights</h2>
                <span class="text-sm text-gray-500">Real-time data overview</span>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Performance Chart -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Activity Overview</h3>
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Leads Progress -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600">Leads Conversion</span>
                                <span class="text-sm font-bold text-emerald-600">
                                    {{ $stats['total_applications'] && $stats['total_leads'] ? round(($stats['total_applications'] / max($stats['total_leads'], 1)) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-emerald-400 to-green-600 h-2 rounded-full" 
                                     style="width: {{ $stats['total_applications'] && $stats['total_leads'] ? min(round(($stats['total_applications'] / max($stats['total_leads'], 1)) * 100, 1), 100) : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Task Completion -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600">Task Progress</span>
                                <span class="text-sm font-bold text-blue-600">
                                    {{ $stats['pending_tasks'] && $stats['total_tasks'] ? round((($stats['total_tasks'] - $stats['pending_tasks']) / max($stats['total_tasks'], 1)) * 100, 1) : 0 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" 
                                     style="width: {{ $stats['pending_tasks'] && $stats['total_tasks'] ? round((($stats['total_tasks'] - $stats['pending_tasks']) / max($stats['total_tasks'], 1)) * 100, 1) : 0 }}%"></div>
                            </div>
                        </div>
                        
                        <!-- Follow-up Efficiency -->
                        <div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm font-medium text-gray-600">Follow-up Efficiency</span>
                                <span class="text-sm font-bold text-purple-600">
                                    {{ $stats['total_followups'] ? round((($stats['total_followups'] - ($stats['overdue_followups'] ?? 0)) / max($stats['total_followups'], 1)) * 100, 1) : 100 }}%
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-2 rounded-full" 
                                     style="width: {{ $stats['total_followups'] ? round((($stats['total_followups'] - ($stats['overdue_followups'] ?? 0)) / max($stats['total_followups'], 1)) * 100, 1) : 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Stats Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <!-- Applications This Month -->
                    <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl p-6 border border-purple-200">
                        <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-purple-900">{{ $stats['applications_this_month'] }}</p>
                        <p class="text-sm text-purple-700">Applications This Month</p>
                    </div>

                    <!-- Completion Rate -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-2xl p-6 border border-green-200">
                        <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['completion_rate'] }}%</p>
                        <p class="text-sm text-green-700">Success Rate</p>
                    </div>

                    <!-- Average Response -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl p-6 border border-blue-200">
                        <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['avg_response_time'] }}</p>
                        <p class="text-sm text-blue-700">Avg Response Time</p>
                    </div>

                    <!-- Satisfaction Score -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl p-6 border border-yellow-200">
                        <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center mb-4">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                        <p class="text-2xl font-bold text-yellow-900">{{ $stats['satisfaction_score'] }}★</p>
                        <p class="text-sm text-yellow-700">Client Rating</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Modules Grid -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-900">System Modules</h2>
                <span class="text-sm text-gray-500">Access all system features</span>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                
                <!-- Lead Management -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <span class="text-xs bg-green-100 text-green-600 px-2 py-1 rounded-full font-medium">Active</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Lead Management</h3>
                    <p class="text-sm text-gray-600 mb-4">Manage leads, track conversion rates, and analyze lead sources effectively.</p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('leads.index') }}" class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            View Leads
                        </a>
                        <a href="{{ route('leads.create') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            Create Lead
                        </a>
                    </div>
                </div>

                <!-- Application Management -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded-full font-medium">Core</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Applications</h3>
                    <p class="text-sm text-gray-600 mb-4">Process student applications, manage documents, and track admission status.</p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('applications.index') }}" class="bg-gradient-to-r from-purple-500 to-indigo-600 hover:from-purple-600 hover:to-indigo-700 text-white px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            View Applications
                        </a>
                        <a href="{{ route('applications.create') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            New Application
                        </a>
                    </div>
                </div>

                <!-- Task Management -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v2m0 0v6a2 2 0 002 2h6a2 2 0 002-2V9m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m-6 4h.01M15 13h.01M9 17h.01"></path>
                            </svg>
                        </div>
                        <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full font-medium">New</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Task Management</h3>
                    <p class="text-sm text-gray-600 mb-4">Track tasks, deadlines, and completion status with advanced workflow management.</p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('task-management.index') }}" class="bg-gradient-to-r from-blue-500 to-cyan-600 hover:from-blue-600 hover:to-cyan-700 text-white px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            View Tasks
                        </a>
                        <a href="{{ route('tasks.create') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            Create Task
                        </a>
                    </div>
                </div>

                <!-- Follow-up Management -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-rose-500 to-pink-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs bg-rose-100 text-rose-600 px-2 py-1 rounded-full font-medium">Active</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Follow-ups</h3>
                    <p class="text-sm text-gray-600 mb-4">Schedule, track, and manage follow-up activities with clients and leads.</p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('followups.dashboard') }}" class="bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            View Follow-ups
                        </a>
                        <a href="{{ route('followups.today') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            Today's Tasks
                        </a>
                    </div>
                </div>

                <!-- Course Finder -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="text-xs bg-yellow-100 text-yellow-600 px-2 py-1 rounded-full font-medium">Tool</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Course Finder</h3>
                    <p class="text-sm text-gray-600 mb-4">Search and discover courses from various institutions worldwide.</p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('courses.finder') }}" class="bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            Find Courses
                        </a>
                        <a href="{{ route('courses.filters') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            Advanced Search
                        </a>
                    </div>
                </div>

                <!-- User Management -->
                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-gray-700 to-gray-900 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full font-medium">Admin</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">User Management</h3>
                    <p class="text-sm text-gray-600 mb-4">Manage user accounts, roles, permissions, and access controls.</p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('users.index') }}" class="bg-gradient-to-r from-gray-700 to-gray-900 hover:from-gray-800 hover:to-black text-white px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            Manage Users
                        </a>
                        <a href="{{ route('users.create') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            Add User
                        </a>
                    </div>
                </div>
                @endif

                <!-- Notifications -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM11 13h4m-4-4h4m-4-4h4M7 13h1m-1-4h1m-1-4h1"></path>
                            </svg>
                        </div>
                        <span class="text-xs bg-indigo-100 text-indigo-600 px-2 py-1 rounded-full font-medium">Live</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Notifications</h3>
                    <p class="text-sm text-gray-600 mb-4">View system notifications, alerts, and important updates.</p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('notifications.index') }}" class="bg-gradient-to-r from-indigo-500 to-purple-600 hover:from-indigo-600 hover:to-purple-700 text-white px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            View Notifications
                        </a>
                        <button onclick="markAllAsRead()" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            Mark All Read
                        </button>
                    </div>
                </div>

                <!-- Settings -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 group">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-slate-500 to-slate-700 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <span class="text-xs bg-slate-100 text-slate-600 px-2 py-1 rounded-full font-medium">Config</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Settings</h3>
                    <p class="text-sm text-gray-600 mb-4">Configure system settings, preferences, and customizations.</p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('settings.index') }}" class="bg-gradient-to-r from-slate-500 to-slate-700 hover:from-slate-600 hover:to-slate-800 text-white px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            System Settings
                        </a>
                        <a href="{{ route('profile.edit') }}" class="border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2 rounded-lg transition-all duration-200 text-center text-sm font-medium">
                            Profile Settings
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
                    <span class="text-sm font-bold text-green-600">{{ $stats['api_status'] }}</span>
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
                    <span class="text-sm font-bold text-blue-600">{{ $stats['database_status'] }}</span>
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
                    <span class="text-sm font-bold text-purple-600">{{ $stats['security_status'] }}</span>
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
                    <span class="text-sm font-bold text-green-600">{{ $stats['response_time'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-green-400 to-green-600 h-2 rounded-full" 
                         style="width: {{ $stats['response_time_percentage'] }}%"></div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">Uptime</span>
                    <span class="text-sm font-bold text-blue-600">{{ $stats['uptime'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-blue-400 to-blue-600 h-2 rounded-full" 
                         style="width: {{ $stats['uptime_percentage'] }}%"></div>
                </div>
                
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-gray-600">User Satisfaction</span>
                    <span class="text-sm font-bold text-purple-600">{{ $stats['user_satisfaction'] }}</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-gradient-to-r from-purple-400 to-purple-600 h-2 rounded-full" 
                         style="width: {{ $stats['satisfaction_percentage'] }}%"></div>
                </div>
            </div>
        </div>
    </div>
@endsection