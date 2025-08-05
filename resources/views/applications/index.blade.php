@extends('layouts.app')

@section('page-title', 'Applications')

@section('content')
<div class="mx-auto space-y-8">
    <!-- Page Header with Animation -->
    <div class="relative overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 via-purple-600/10 to-indigo-600/10 rounded-3xl"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.05"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>
        
        <div class="relative glass-effect rounded-3xl shadow-2xl border border-white/30 p-8 animate-fade-in">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-6 lg:space-y-0">
                <!-- Left Side - Title & Description -->
                <div class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-xl rotate-3 hover:rotate-0 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-400 rounded-full border-2 border-white animate-pulse"></div>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold gradient-text">Applications</h1>
                            <div class="flex items-center space-x-3 mt-2">
                                @if(isset($apiData) && $apiData)
                               @if(config('app.debug'))        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200">
                                       <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                       API Connected 
                                    </span>@endif
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-yellow-100 to-amber-100 text-yellow-800 border border-yellow-200">
                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        Local Data
                                    </span>
                                @endif
                                <span class="text-sm text-gray-600">•</span>
                                <span class="text-sm text-gray-600">
                                    @if(isset($apiData) && $apiData)
                                        {{ $apiData['total_count'] ?? 0 }} Total Applications
                                    @else
                                        {{ $applications->total() ?? 0 }} Total Applications
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <p class="text-lg text-gray-600 max-w-2xl">
                        @if(isset($apiData) && $apiData)
                            Manage all visa applications seamlessly through our integrated external API system
                        @else
                            Manage all visa applications from your local database with comprehensive tracking
                        @endif
                    </p>
                </div>

                <!-- Right Side - Actions -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center space-y-3 sm:space-y-0 sm:space-x-4">
                    <!-- Entries Filter -->
                    <div class="glass-effect rounded-xl p-4 border border-white/20 bg-white/30">
                        <div class="flex items-center space-x-3">
                            <label for="entries" class="text-sm font-medium text-gray-700 whitespace-nowrap">Show:</label>
                            <select id="entries" onchange="changeEntries()" 
                                    class="px-3 py-2 bg-white/80 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent backdrop-blur-sm">
                                <option value="5" {{ (isset($entries) && $entries == 5) ? 'selected' : '' }}>5</option>
                                <option value="20" {{ (!isset($entries) || $entries == 20) ? 'selected' : '' }}>20</option>
                                <option value="100" {{ (isset($entries) && $entries == 100) ? 'selected' : '' }}>100</option>
                                <option value="250" {{ (isset($entries) && $entries == 250) ? 'selected' : '' }}>250</option>
                                <option value="500" {{ (isset($entries) && $entries == 500) ? 'selected' : '' }}>500</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="flex space-x-3">
                        @if(isset($apiData) && $apiData)
                            <button onclick="refreshApplications()" 
                                   class="inline-flex items-center px-4 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-medium rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-lg hover:shadow-xl group"
                                   title="Refresh from API">
                                <svg class="w-5 h-5 mr-2 group-hover:rotate-180 transition-transform duration-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh
                            </button>
                            @if(config('app.debug'))
                                <a href="{{ route('applications.view-api-logs') }}" 
                                   class="inline-flex items-center px-4 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all duration-200 shadow-lg hover:shadow-xl"
                                   title="View API Logs">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    API Logs
                                </a>
                            @endif
                        @endif
                        <a href="{{ route('applications.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            New Application
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Status Warning -->
    @if(session('warning'))
        <div class="glass-effect rounded-2xl shadow-xl border border-orange-200/50 p-6 bg-gradient-to-r from-orange-50/50 to-amber-50/50 animate-slide-in">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-orange-800">System Warning</h3>
                    <p class="text-orange-700 mt-1">{{ session('warning') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 animate-fade-in">
        <!-- Total Applications -->
        <div class="group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-blue-600/20 rounded-2xl transform group-hover:scale-105 transition-transform duration-300"></div>
            <div class="relative glass-effect rounded-2xl p-6 border border-white/30 hover:border-blue-300/50 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Applications</p>
                        <p class="text-3xl font-bold gradient-text">
                            @if(isset($apiData) && $apiData)
                                {{ $apiData['total_count'] }}
                            @else
                                {{ $applications->total() }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">All time records</p>
                    </div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-blue-400 rounded-full animate-ping opacity-75"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Pending Applications -->
        <div class="group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-yellow-500/20 to-amber-600/20 rounded-2xl transform group-hover:scale-105 transition-transform duration-300"></div>
            <div class="relative glass-effect rounded-2xl p-6 border border-white/30 hover:border-yellow-300/50 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Pending Review</p>
                        <p class="text-3xl font-bold text-yellow-600">
                            @if(isset($apiData) && $apiData)
                                {{ collect($apiData['data'])->where('status', 'pending')->count() }}
                            @else
                                {{ $applications->where('status', 'pending')->count() }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Awaiting action</p>
                    </div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-amber-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-400 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Approved Applications -->
        <div class="group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-green-500/20 to-emerald-600/20 rounded-2xl transform group-hover:scale-105 transition-transform duration-300"></div>
            <div class="relative glass-effect rounded-2xl p-6 border border-white/30 hover:border-green-300/50 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Approved</p>
                        <p class="text-3xl font-bold text-green-600">
                            @if(isset($apiData) && $apiData)
                                {{ collect($apiData['data'])->where('status', 'approved')->count() }}
                            @else
                                {{ $applications->where('status', 'approved')->count() }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Successfully processed</p>
                    </div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- In Progress Applications -->
        <div class="group relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-500/20 to-indigo-600/20 rounded-2xl transform group-hover:scale-105 transition-transform duration-300"></div>
            <div class="relative glass-effect rounded-2xl p-6 border border-white/30 hover:border-purple-300/50 transition-all duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">In Progress</p>
                        <p class="text-3xl font-bold text-purple-600">
                            @if(isset($apiData) && $apiData)
                                {{ collect($apiData['data'])->where('status', 'in_progress')->count() }}
                            @else
                                {{ $applications->where('status', 'in_progress')->count() }}
                            @endif
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Under review</p>
                    </div>
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div class="absolute -top-1 -right-1 w-4 h-4 bg-purple-400 rounded-full animate-spin opacity-75"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="glass-effect rounded-3xl shadow-2xl border border-white/30 overflow-hidden animate-fade-in">
        <!-- Enhanced Table Header -->
        <div class="bg-gradient-to-r from-white/20 to-white/10 px-8 py-6 border-b border-white/20">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Application Records</h3>
                        <p class="text-sm text-gray-600">
                            @if(isset($apiData) && $apiData)
                                Showing {{ (($apiData['page'] - 1) * $apiData['limit']) + 1 }} to {{ min($apiData['page'] * $apiData['limit'], $apiData['total_count']) }} of {{ $apiData['total_count'] }} applications
                            @else
                                Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }} applications
                            @endif
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    @if(isset($apiData) && $apiData)
                      @if(config('app.debug'))    <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-medium bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200/50">
                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2 animate-pulse"></div>
                            Live API Data
                        </span> @endif
                    @else
                        <span class="inline-flex items-center px-3 py-2 rounded-xl text-sm font-medium bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200/50">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                            Local Database
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-slate-50/80 to-gray-50/80 backdrop-blur-sm">
                    <tr>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200/50">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <span>Application Details</span>
                            </div>
                        </th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200/50">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span>Applicant Info</span>
                            </div>
                        </th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200/50">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span>Destination</span>
                            </div>
                        </th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200/50">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <span>Course & Intake</span>
                            </div>
                        </th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200/50">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <span>Documents</span>
                            </div>
                        </th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200/50">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span>Status & Date</span>
                            </div>
                        </th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-600 uppercase tracking-wider border-b border-gray-200/50">
                            <div class="flex items-center space-x-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                </svg>
                                <span>Actions</span>
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200/50 bg-white/40">
                    @if(isset($apiData) && $apiData)
                        {{-- API Data --}}
                        @forelse($apiData['data'] as $application)
                        <tr class="group hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-purple-50/50 transition-all duration-300 border-b border-gray-100/50">
                            <!-- Application Details -->
                            <td class="px-8 py-6">
                                <div class="flex flex-col space-y-2">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-md">
                                            <span class="text-white font-bold text-sm">{{ substr($application['visa_form_id'] ?? 'N/A', -2) }}</span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-bold text-gray-900">ID: {{ $application['visa_form_id'] ?? 'N/A' }}</div>
                                            @if(!empty($application['ref_no']))
                                                <div class="text-xs text-blue-600 font-medium">Ref: {{ $application['ref_no'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if(!empty($application['source2']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 w-fit">
                                            📍 {{ $application['source2'] }}
                                        </span>
                                    @endif
                                    @if($application['admissions_count'] > 0)
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 w-fit">
                                            🎓 {{ $application['admissions_count'] }} Admission(s)
                                        </span>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Applicant Info -->
                            <td class="px-8 py-6">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                                        <span class="text-white font-bold text-lg">{{ strtoupper(substr($application['name'] ?? 'U', 0, 1)) }}</span>
                                    </div>
                                    <div class="flex-1 space-y-2">
                                        <div class="font-semibold text-gray-900 text-sm">{{ $application['name'] ?? 'N/A' }}</div>
                                        <div class="space-y-1">
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                                </svg>
                                                {{ Str::limit($application['email'] ?? 'N/A', 25) }}
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600">
                                                <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                                </svg>
                                                {{ $application['phone'] ?? 'N/A' }}
                                            </div>
                                            @if(!empty($application['resi_city']))
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $application['resi_city'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            
                            <!-- Destination -->
                            <td class="px-8 py-6">
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-md">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="font-semibold text-gray-900 text-sm">{{ $application['country'] ?? 'Not specified' }}</div>
                                    </div>
                                    @if(!empty($application['city']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                            🏙️ {{ $application['city'] }}
                                        </span>
                                    @endif
                                    @if(!empty($application['institute_name']))
                                        <div class="p-2 bg-purple-50 rounded-lg border border-purple-200">
                                            <div class="text-xs font-medium text-purple-700">🏫 Institute</div>
                                            <div class="text-xs text-purple-600 font-semibold">{{ Str::limit($application['institute_name'], 30) }}</div>
                                        </div>
                                    @endif
                                    @if(!empty($application['prefer_city']))
                                        <div class="text-xs text-gray-500">Preferred: {{ $application['prefer_city'] }}</div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Course & Intake -->
                            <td class="px-8 py-6">
                                <div class="space-y-3">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-gradient-to-br from-orange-500 to-red-500 rounded-lg flex items-center justify-center shadow-md">
                                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="font-semibold text-gray-900 text-sm">{{ ucfirst($application['course'] ?? 'Not specified') }}</div>
                                    </div>
                                    @if(!empty($application['last_qual']))
                                        <span class="inline-flex items-center px-2 py-1 rounded-lg text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                            📚 Level: {{ ucfirst($application['last_qual']) }}
                                        </span>
                                    @endif
                                    @if(!empty($application['intake']) || !empty($application['intakeyear']))
                                        <div class="p-2 bg-green-50 rounded-lg border border-green-200">
                                            <div class="text-xs font-medium text-green-700">📅 Intake Period</div>
                                            <div class="text-xs text-green-600 font-semibold">{{ $application['intake'] ?? '' }} {{ $application['intakeyear'] ?? '' }}</div>
                                        </div>
                                    @endif
                                    @if(!empty($application['study_course']))
                                        <div class="text-xs text-gray-500">Study: {{ Str::limit($application['study_course'], 25) }}</div>
                                    @endif
                                </div>
                            </td>
                            <!-- Documents with Enhanced Tooltip -->
                            <td class="px-8 py-6">
                                <div class="space-y-3">
                                    @if($application['documents_count'] > 0)
                                        <div class="relative group">
                                            <button type="button" 
                                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-800 text-sm font-semibold rounded-xl hover:from-blue-100 hover:to-indigo-100 transition-all duration-200 border border-blue-200 shadow-sm hover:shadow-md"
                                                    onclick="showDocuments({{ json_encode($application['documents']) }}, '{{ $application['name'] }}')">
                                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $application['documents_count'] }} Document{{ $application['documents_count'] > 1 ? 's' : '' }}
                                            </button>
                                            
                                            <!-- Enhanced Tooltip -->
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-3 px-4 py-2 bg-gray-900 text-white text-xs rounded-xl opacity-0 group-hover:opacity-100 transition-all duration-200 z-20 whitespace-nowrap shadow-xl">
                                                <div class="font-medium">Click to view all documents</div>
                                                <div class="text-gray-300">{{ $application['documents_count'] }} files available</div>
                                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-4 py-2 bg-gray-50 text-gray-600 text-sm font-medium rounded-xl border border-gray-200">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                                            </svg>
                                            No Documents
                                        </span>
                                    @endif
                                    
                                    <!-- English Test Scores with Enhanced Design -->
                                    @if(!empty($application['ielts_overall']) || !empty($application['pte_overall']))
                                        <div class="flex flex-wrap gap-2">
                                            @if(!empty($application['ielts_overall']))
                                                <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 rounded-lg text-xs font-bold border border-green-200">
                                                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                                    IELTS: {{ $application['ielts_overall'] }}
                                                </span>
                                            @endif
                                            @if(!empty($application['pte_overall']))
                                                <span class="inline-flex items-center px-3 py-1 bg-gradient-to-r from-purple-100 to-indigo-100 text-purple-800 rounded-lg text-xs font-bold border border-purple-200">
                                                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                                    PTE: {{ $application['pte_overall'] }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Status & Date with Enhanced Design -->
                            <td class="px-8 py-6">
                                <div class="space-y-3">
                                    @php
                                        $status = $application['status'] ?? 'pending';
                                        $statusConfig = match($status) {
                                            'pending' => [
                                                'bg' => 'bg-gradient-to-r from-yellow-100 to-amber-100',
                                                'text' => 'text-yellow-800',
                                                'border' => 'border-yellow-200',
                                                'icon' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path></svg>'
                                            ],
                                            'in_progress' => [
                                                'bg' => 'bg-gradient-to-r from-blue-100 to-indigo-100',
                                                'text' => 'text-blue-800',
                                                'border' => 'border-blue-200',
                                                'icon' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path></svg>'
                                            ],
                                            'approved' => [
                                                'bg' => 'bg-gradient-to-r from-green-100 to-emerald-100',
                                                'text' => 'text-green-800',
                                                'border' => 'border-green-200',
                                                'icon' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>'
                                            ],
                                            'rejected' => [
                                                'bg' => 'bg-gradient-to-r from-red-100 to-pink-100',
                                                'text' => 'text-red-800',
                                                'border' => 'border-red-200',
                                                'icon' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>'
                                            ],
                                            'completed' => [
                                                'bg' => 'bg-gradient-to-r from-purple-100 to-indigo-100',
                                                'text' => 'text-purple-800',
                                                'border' => 'border-purple-200',
                                                'icon' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>'
                                            ],
                                            default => [
                                                'bg' => 'bg-gradient-to-r from-gray-100 to-slate-100',
                                                'text' => 'text-gray-800',
                                                'border' => 'border-gray-200',
                                                'icon' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
                                            ]
                                        };
                                    @endphp
                                    <div class="flex items-center justify-center">
                                        <span class="inline-flex items-center px-3 py-2 text-xs font-bold rounded-xl {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} border {{ $statusConfig['border'] }} shadow-sm">
                                            {!! $statusConfig['icon'] !!}
                                            <span class="ml-2">{{ ucfirst($status) }}</span>
                                        </span>
                                    </div>
                                    
                                    <div class="text-center space-y-1">
                                        <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($application['date'])->format('M d, Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($application['date'])->format('H:i A') }}</div>
                                        <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($application['date'])->diffForHumans() }}</div>
                                    </div>
                                    
                                    @if(!empty($application['visa_status']))
                                        <div class="p-2 bg-blue-50 rounded-lg border border-blue-200">
                                            <div class="text-xs font-medium text-blue-700 text-center">Visa Status</div>
                                            <div class="text-xs text-blue-600 font-semibold text-center">{{ $application['visa_status'] }}</div>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Enhanced Actions -->
                            <td class="px-8 py-6">
                                <div class="flex flex-col items-center space-y-2">
                                    <button onclick="viewApplicationDetails({{ $application['visa_form_id'] }})" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-sm font-semibold rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View Details
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No applications found</h3>
                                    <p class="text-gray-500 mb-4">Get started by creating a new application.</p>
                                    <a href="{{ route('applications.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create First Application
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    @else
                        {{-- Fallback to Local Database --}}
                        @forelse($applications as $application)
                        <tr class="hover:bg-white/10 transition-colors duration-200">
                            <!-- Application Details -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">{{ $application->application_number }}</div>
                                    @if($application->lead_ref_no)
                                        <div class="text-xs text-blue-600">Lead: {{ $application->lead_ref_no }}</div>
                                    @endif
                                    <div class="text-xs text-gray-500">Source: Local DB</div>
                                </div>
                            </td>
                            
                            <!-- Applicant Info -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $application->name }}</div>
                                    <div class="text-sm text-gray-600 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                        {{ $application->email }}
                                    </div>
                                    <div class="text-sm text-gray-600 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                        </svg>
                                        {{ $application->phone }}
                                    </div>
                                    @if($application->city)
                                        <div class="text-xs text-gray-500">📍 {{ $application->city }}</div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Destination -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $application->preferred_country ?? 'Not specified' }}</div>
                                    @if($application->preferred_city)
                                        <div class="text-xs text-gray-600">🏙️ {{ $application->preferred_city }}</div>
                                    @endif
                                    @if($application->preferred_college)
                                        <div class="text-xs text-purple-600 font-medium">🏫 {{ Str::limit($application->preferred_college, 25) }}</div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Course & Intake -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <div class="text-sm font-medium text-gray-900">{{ ucfirst($application->course_level ?? 'Not specified') }}</div>
                                    @if($application->field_of_study)
                                        <div class="text-xs text-blue-600">Field: {{ ucfirst($application->field_of_study) }}</div>
                                    @endif
                                    @if($application->courseOptions && $application->courseOptions->count() > 0)
                                        <div class="text-xs text-green-600">
                                            📅 {{ $application->courseOptions->first()->intake_month }} {{ $application->courseOptions->first()->intake_year }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Documents -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    @if($application->documents && $application->documents->count() > 0)
                                        <div class="relative group">
                                            <button type="button" 
                                                    class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full hover:bg-blue-200 transition-colors"
                                                    onclick="showLocalDocuments({{ $application->id }}, '{{ $application->name }}')">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $application->documents->count() }} Docs
                                            </button>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                                            </svg>
                                            No Docs
                                        </span>
                                    @endif
                                    
                                    <!-- English Test Scores if available -->
                                    @if($application->ielts_score || $application->pte_score)
                                        <div class="text-xs">
                                            @if($application->ielts_score)
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">IELTS: {{ $application->ielts_score }}</span>
                                            @endif
                                            @if($application->pte_score)
                                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">PTE: {{ $application->pte_score }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Status & Date -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @switch($application->status)
                                            @case('pending') bg-yellow-100 text-yellow-800 @break
                                            @case('in_progress') bg-blue-100 text-blue-800 @break
                                            @case('approved') bg-green-100 text-green-800 @break
                                            @case('rejected') bg-red-100 text-red-800 @break
                                            @case('completed') bg-purple-100 text-purple-800 @break
                                            @default bg-gray-100 text-gray-800
                                        @endswitch
                                    ">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                    
                                    <div class="text-xs text-gray-600">
                                        <div>{{ $application->created_at->format('M d, Y') }}</div>
                                        <div class="text-gray-400">{{ $application->created_at->format('H:i') }}</div>
                                    </div>
                                    
                                    <div class="text-xs text-gray-500">{{ $application->creator->name ?? 'Unknown' }}</div>
                                </div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('applications.show', $application->id) }}" 
                                       class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-all duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Details
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No applications found</h3>
                                    <p class="text-gray-500 mb-4">Get started by creating a new application.</p>
                                    <a href="{{ route('applications.create') }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create First Application
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Enhanced Pagination -->
        @if(isset($apiData) && $apiData)
            @if($apiData['total_pages'] > 1)
            <div class="bg-gradient-to-r from-white/30 to-white/20 px-8 py-6 border-t border-white/20">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                    <!-- Mobile Pagination -->
                    <div class="flex-1 flex justify-between lg:hidden">
                        @if($apiData['page'] > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $apiData['page'] - 1, 'entries' => $entries ?? 20]) }}" 
                               class="relative inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white/80 hover:bg-white/90 transition-all duration-200 shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Previous
                            </a>
                        @endif
                        @if($apiData['page'] < $apiData['total_pages'])
                            <a href="{{ request()->fullUrlWithQuery(['page' => $apiData['page'] + 1, 'entries' => $entries ?? 20]) }}" 
                               class="ml-3 relative inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white/80 hover:bg-white/90 transition-all duration-200 shadow-sm hover:shadow-md">
                                Next
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                    
                    <!-- Desktop Pagination -->
                    <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">
                                    Showing
                                    <span class="font-bold text-blue-600">{{ (($apiData['page'] - 1) * $apiData['limit']) + 1 }}</span>
                                    to
                                    <span class="font-bold text-blue-600">{{ min($apiData['page'] * $apiData['limit'], $apiData['total_count']) }}</span>
                                    of
                                    <span class="font-bold text-blue-600">{{ $apiData['total_count'] }}</span>
                                    results
                                </p>
                                <p class="text-xs text-gray-500">Page {{ $apiData['page'] }} of {{ $apiData['total_pages'] }}</p>
                            </div>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-xl shadow-sm -space-x-px bg-white/50" aria-label="Pagination">
                                @if($apiData['page'] > 1)
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $apiData['page'] - 1, 'entries' => $entries ?? 20]) }}" 
                                       class="relative inline-flex items-center px-4 py-2 rounded-l-xl border border-gray-300 bg-white/80 text-sm font-medium text-gray-500 hover:bg-white/90 hover:text-gray-700 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        Previous
                                    </a>
                                @endif
                                
                                @for($i = 1; $i <= $apiData['total_pages']; $i++)
                                    @if($i == $apiData['page'])
                                        <span class="relative inline-flex items-center px-4 py-2 border border-blue-300 bg-gradient-to-r from-blue-500 to-indigo-500 text-sm font-bold text-white shadow-md">
                                            {{ $i }}
                                        </span>
                                    @else
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $i, 'entries' => $entries ?? 20]) }}" 
                                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white/80 text-sm font-medium text-gray-700 hover:bg-white/90 hover:text-gray-900 transition-all duration-200">
                                            {{ $i }}
                                        </a>
                                    @endif
                                @endfor
                                
                                @if($apiData['page'] < $apiData['total_pages'])
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $apiData['page'] + 1, 'entries' => $entries ?? 20]) }}" 
                                       class="relative inline-flex items-center px-4 py-2 rounded-r-xl border border-gray-300 bg-white/80 text-sm font-medium text-gray-500 hover:bg-white/90 hover:text-gray-700 transition-all duration-200">
                                        Next
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @endif
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        @else
            @if($applications->hasPages())
            <div class="bg-gradient-to-r from-white/30 to-white/20 px-8 py-6 border-t border-white/20">
                <div class="flex items-center justify-center">
                    {{ $applications->appends(['entries' => $entries ?? 20])->links() }}
                </div>
            </div>
            @endif
        @endif
    </div>
</div>
@endsection

<!-- Enhanced Documents Modal -->
<div id="documentsModal" class="fixed inset-0 bg-black bg-opacity-60 overflow-y-auto h-full w-full z-50 hidden backdrop-blur-sm">
    <div class="relative top-10 mx-auto p-0 border-0 w-11/12 md:w-3/4 lg:w-1/2 xl:w-2/5 shadow-2xl rounded-3xl">
        <div class="relative glass-effect rounded-3xl border border-white/30 overflow-hidden">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-white" id="modalTitle">Application Documents</h3>
                            <p class="text-blue-100 text-sm">View and download all attached documents</p>
                        </div>
                    </div>
                    <button onclick="closeDocumentsModal()" 
                            class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-all duration-200 backdrop-blur-sm">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Modal Content -->
            <div class="p-8">
                <div id="documentsContent" class="max-h-96 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-400 scrollbar-track-transparent">
                    <!-- Documents will be loaded here -->
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50/80 px-8 py-6 border-t border-gray-200/50 flex justify-end space-x-3">
                <button onclick="closeDocumentsModal()" 
                        class="px-6 py-3 bg-gray-500 text-white font-medium rounded-xl hover:bg-gray-600 transition-all duration-200 shadow-md hover:shadow-lg">
                    Close
                </button>
                <button id="downloadAllBtn" onclick="downloadAllDocuments()" 
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Download All
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Global variables
let currentDocuments = [];
let currentApplicationId = null;

// View application complete details
function viewApplicationDetails(visaFormId) {
    // Redirect to the detailed API application page with all information
    window.location.href = '/applications/api/' + visaFormId;
}

// Show documents modal for API data
function showDocuments(documents, applicationName) {
    currentDocuments = documents;
    currentApplicationId = null;
    
    document.getElementById('modalTitle').textContent = `Documents for ${applicationName}`;
    
    let content = '';
    if (documents && documents.length > 0) {
        content = '<div class="space-y-3">';
        documents.forEach((doc, index) => {
            const fileSize = doc.document_size ? formatFileSize(doc.document_size) : 'Unknown size';
            const createdDate = doc.created ? new Date(doc.created).toLocaleDateString() : 'Unknown date';
            
            content += `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">${doc.document_type || 'Unknown Document'}</div>
                            <div class="text-xs text-gray-500">${fileSize} • ${createdDate}</div>
                            ${doc.mandatory ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Required</span>' : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Optional</span>'}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="viewDocument('${doc.document}')" 
                                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded hover:bg-blue-200 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </button>
                        <button onclick="downloadDocument('${doc.document}', '${doc.document_type}')" 
                                class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded hover:bg-green-200 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download
                        </button>
                    </div>
                </div>
            `;
        });
        content += '</div>';
    } else {
        content = '<div class="text-center py-8 text-gray-500">No documents found for this application.</div>';
    }
    
    document.getElementById('documentsContent').innerHTML = content;
    document.getElementById('documentsModal').classList.remove('hidden');
}

// Show documents modal for local database data
function showLocalDocuments(applicationId, applicationName) {
    currentApplicationId = applicationId;
    currentDocuments = [];
    
    document.getElementById('modalTitle').textContent = `Documents for ${applicationName}`;
    document.getElementById('documentsContent').innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-gray-600">Loading documents...</p></div>';
    document.getElementById('documentsModal').classList.remove('hidden');
    
    // Fetch documents for local application
    fetch(`/applications/${applicationId}/documents`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.documents) {
                displayLocalDocuments(data.documents);
            } else {
                document.getElementById('documentsContent').innerHTML = '<div class="text-center py-8 text-gray-500">No documents found for this application.</div>';
            }
        })
        .catch(error => {
            console.error('Error fetching documents:', error);
            document.getElementById('documentsContent').innerHTML = '<div class="text-center py-8 text-red-500">Error loading documents.</div>';
        });
}

// Display local documents
function displayLocalDocuments(documents) {
    let content = '';
    if (documents && documents.length > 0) {
        content = '<div class="space-y-3">';
        documents.forEach((doc, index) => {
            const fileSize = doc.file_size ? formatFileSize(doc.file_size) : 'Unknown size';
            const createdDate = doc.created_at ? new Date(doc.created_at).toLocaleDateString() : 'Unknown date';
            
            content += `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">${doc.document_name || 'Unknown Document'}</div>
                            <div class="text-xs text-gray-500">${fileSize} • ${createdDate}</div>
                            ${doc.is_mandatory ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Required</span>' : '<span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Optional</span>'}
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="viewLocalDocument(${doc.id})" 
                                class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded hover:bg-blue-200 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </button>
                        <button onclick="downloadLocalDocument(${doc.id})" 
                                class="inline-flex items-center px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded hover:bg-green-200 transition-colors">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download
                        </button>
                    </div>
                </div>
            `;
        });
        content += '</div>';
    } else {
        content = '<div class="text-center py-8 text-gray-500">No documents found for this application.</div>';
    }
    
    document.getElementById('documentsContent').innerHTML = content;
}

// Close documents modal
function closeDocumentsModal() {
    document.getElementById('documentsModal').classList.add('hidden');
    currentDocuments = [];
    currentApplicationId = null;
}

// Format file size
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// View document (API)
function viewDocument(documentUrl) {
    window.open(documentUrl, '_blank');
}

// Download document (API)
function downloadDocument(documentUrl, documentName) {
    const link = document.createElement('a');
    link.href = documentUrl;
    link.download = documentName || 'document';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// View local document
function viewLocalDocument(documentId) {
    window.open(`/applications/documents/${documentId}/view`, '_blank');
}

// Download local document
function downloadLocalDocument(documentId) {
    window.location.href = `/applications/documents/${documentId}/download`;
}

// Download all documents
function downloadAllDocuments() {
    if (currentApplicationId) {
        // Local application
        window.location.href = `/applications/${currentApplicationId}/documents/download-all`;
    } else {
        // API documents - download each one
        currentDocuments.forEach((doc, index) => {
            setTimeout(() => {
                downloadDocument(doc.document, doc.document_type);
            }, index * 500); // Stagger downloads
        });
    }
}

// Download documents for application
function downloadDocuments(visaFormId) {
    // Create a temporary form to trigger download for all documents of this application
    alert('This would download all documents for application #' + visaFormId + '\n\nAPI endpoint for bulk download needs to be implemented.');
    
    // TODO: Implement actual API endpoint for bulk document download
    // The API would need to provide a ZIP download endpoint like:
    // window.location.href = '/api/applications/' + visaFormId + '/documents/download-all';
}

// Download local documents
function downloadLocalDocuments(applicationId) {
    window.location.href = `/applications/${applicationId}/documents/download-all`;
}

// Delete application
function deleteApplication(visaFormId) {
    if (confirm('Are you sure you want to delete application #' + visaFormId + '?')) {
        // Show loading state
        const button = event.target.closest('button');
        const originalHTML = button.innerHTML;
        button.disabled = true;
        button.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"></circle><path fill="currentColor" opacity="0.75" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>';
        
        // TODO: Implement actual delete API call
        setTimeout(() => {
            alert('Application deletion would be handled via API call here.');
            button.disabled = false;
            button.innerHTML = originalHTML;
        }, 1000);
        
        // Example API call structure:
        /*
        fetch('/api/applications/' + visaFormId + '/delete', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); // Refresh the page
            } else {
                alert('Error deleting application: ' + data.message);
            }
        })
        .catch(error => {
            alert('Error deleting application: ' + error.message);
        })
        .finally(() => {
            button.disabled = false;
            button.innerHTML = originalHTML;
        });
        */
    }
}

// Refresh applications list
function refreshApplications() {
    window.location.reload();
}

// Change number of entries per page
function changeEntries() {
    const entriesSelect = document.getElementById('entries');
    const selectedEntries = entriesSelect.value;
    
    // Get current URL and update entries parameter
    const url = new URL(window.location.href);
    url.searchParams.set('entries', selectedEntries);
    url.searchParams.set('page', '1'); // Reset to first page
    
    // Navigate to updated URL
    window.location.href = url.toString();
}

// Auto-refresh every 30 seconds (optional)
// setInterval(refreshApplications, 30000);
</script>
