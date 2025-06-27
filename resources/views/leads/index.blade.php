@extends('layouts.app')

@section('page-title', $pageTitle ?? 'Lead Management')

@section('content')
<div class="glass-effect rounded-2xl shadow-xl border border-white/20 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600/10 to-purple-600/10 p-6 border-b border-white/20">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $pageTitle ?? 'Lead Management' }}</h1>
                <p class="text-gray-600 mt-1">Manage and track your leads efficiently</p>
            </div>
            <a href="{{ route('leads.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add New Lead
            </a>
        </div>
    </div>

    <div class="p-6">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-8">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-xl shadow-lg">
                <h6 class="text-blue-100 text-sm font-medium">Total Leads</h6>
                <h3 class="text-2xl font-bold">{{ $statistics['total'] ?? 0 }}</h3>
            </div>
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-xl shadow-lg">
                <h6 class="text-green-100 text-sm font-medium">New</h6>
                <h3 class="text-2xl font-bold">{{ $statistics['new'] ?? 0 }}</h3>
            </div>
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white p-4 rounded-xl shadow-lg">
                <h6 class="text-yellow-100 text-sm font-medium">Contacted</h6>
                <h3 class="text-2xl font-bold">{{ $statistics['contacted'] ?? 0 }}</h3>
            </div>
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-4 rounded-xl shadow-lg">
                <h6 class="text-purple-100 text-sm font-medium">Qualified</h6>
                <h3 class="text-2xl font-bold">{{ $statistics['qualified'] ?? 0 }}</h3>
            </div>
            <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 text-white p-4 rounded-xl shadow-lg">
                <h6 class="text-emerald-100 text-sm font-medium">Converted</h6>
                <h3 class="text-2xl font-bold">{{ $statistics['converted'] ?? 0 }}</h3>
            </div>
            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white p-4 rounded-xl shadow-lg">
                <h6 class="text-red-100 text-sm font-medium">Rejected</h6>
                <h3 class="text-2xl font-bold">{{ $statistics['rejected'] ?? 0 }}</h3>
            </div>
        </div>

        <!-- Filters -->
        <form method="GET" class="mb-8">
            <div class="bg-white/5 backdrop-blur-xl rounded-2xl border border-white/20 shadow-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                        Search & Filter Leads
                    </h3>
                    @if(request()->has('filter') || request()->has('search') || request()->has('status'))
                        <a href="{{ route('leads.index') }}" 
                           class="text-sm text-blue-600 hover:text-blue-800 transition-colors flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear all filters
                        </a>
                    @endif
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                        <div class="relative">
                            <select name="status" class="w-full pl-10 pr-4 py-3 bg-white/60 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-white/80 backdrop-blur-sm transition-all duration-200 shadow-md">
                                <option value="">All Status</option>
                                <option value="new" {{ request('status') === 'new' ? 'selected' : '' }}>New</option>
                                <option value="contacted" {{ request('status') === 'contacted' ? 'selected' : '' }}>Contacted</option>
                                <option value="qualified" {{ request('status') === 'qualified' ? 'selected' : '' }}>Qualified</option>
                                <option value="converted" {{ request('status') === 'converted' ? 'selected' : '' }}>Converted</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Leads</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   class="w-full pl-10 pr-4 py-3 bg-white/60 border border-white/30 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 focus:bg-white/80 backdrop-blur-sm transition-all duration-200 shadow-md" 
                                   placeholder="Search by name, email, phone...">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Actions</label>
                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                    </div>
                    <div class="relative">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reset</label>
                        <a href="{{ route('leads.index') }}" class="block w-full px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white text-center font-medium rounded-xl hover:from-gray-600 hover:to-gray-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Clear
                        </a>
                    </div>
                </div>
                @if(request('filter'))
                    <input type="hidden" name="filter" value="{{ request('filter') }}">
                @endif
            </div>
        </form>

        <!-- Leads Table -->
        <div class="overflow-hidden rounded-2xl border border-white/20 shadow-2xl bg-white/5 backdrop-blur-xl">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-blue-50/80 to-purple-50/80 backdrop-blur-sm">
                        <tr class="border-b border-white/20">
                            <th class="px-6 py-5 text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</span>
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Ref No</span>
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</span>
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Phone</span>
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</span>
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Country</span>
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</span>
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Assigned To</span>
                                </div>
                            </th>
                            <th class="px-6 py-5 text-left">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs font-semibold text-gray-700 uppercase tracking-wider">Created</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10">
                        @forelse($leads as $lead)
                            <tr class="group hover:bg-gradient-to-r hover:from-blue-50/50 hover:to-purple-50/50 transition-all duration-300 ease-in-out transform hover:scale-[1.01] hover:shadow-lg">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('leads.show', $lead->id) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                                            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('leads.edit', $lead->id) }}" 
                                           class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white text-sm font-medium rounded-xl hover:from-amber-600 hover:to-orange-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 group">
                                            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            {{ substr($lead->ref_no, -2) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $lead->ref_no }}</div>
                                            <div class="text-xs text-gray-500">Reference Number</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-lg">
                                            {{ strtoupper(substr($lead->name, 0, 2)) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900">{{ $lead->name }}</div>
                                            <div class="text-xs text-gray-500">Lead Name</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-green-400 to-blue-500 rounded-lg flex items-center justify-center text-white shadow-md">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $lead->phone }}</div>
                                            <div class="text-xs text-gray-500">Contact Number</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-pink-400 to-red-500 rounded-lg flex items-center justify-center text-white shadow-md">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 truncate max-w-48">{{ $lead->email }}</div>
                                            <div class="text-xs text-gray-500">Email Address</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center text-white shadow-md">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $lead->preferred_country }}</div>
                                            <div class="text-xs text-gray-500">Preferred Country</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold shadow-md
                                        @switch($lead->status)
                                            @case('new') 
                                                bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 border border-blue-300
                                                @break
                                            @case('contacted') 
                                                bg-gradient-to-r from-yellow-100 to-amber-200 text-yellow-800 border border-yellow-300
                                                @break
                                            @case('qualified') 
                                                bg-gradient-to-r from-purple-100 to-violet-200 text-purple-800 border border-purple-300
                                                @break
                                            @case('converted') 
                                                bg-gradient-to-r from-green-100 to-emerald-200 text-green-800 border border-green-300
                                                @break
                                            @case('rejected') 
                                                bg-gradient-to-r from-red-100 to-rose-200 text-red-800 border border-red-300
                                                @break
                                            @default 
                                                bg-gradient-to-r from-gray-100 to-slate-200 text-gray-800 border border-gray-300
                                        @endswitch
                                    ">
                                        <div class="w-2 h-2 rounded-full mr-2
                                            @switch($lead->status)
                                                @case('new') bg-blue-500 @break
                                                @case('contacted') bg-yellow-500 @break
                                                @case('qualified') bg-purple-500 @break
                                                @case('converted') bg-green-500 @break
                                                @case('rejected') bg-red-500 @break
                                                @default bg-gray-500
                                            @endswitch
                                        "></div>
                                        {{ ucfirst($lead->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($lead->assignedUser)
                                            <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-cyan-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-md">
                                                {{ strtoupper(substr($lead->assignedUser->name, 0, 2)) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $lead->assignedUser->name }}</div>
                                                <div class="text-xs text-gray-500">Assigned User</div>
                                            </div>
                                        @else
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-400">Unassigned</div>
                                                    <div class="text-xs text-gray-400">No assignment</div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 w-8 h-8 bg-gradient-to-br from-rose-400 to-pink-500 rounded-lg flex items-center justify-center text-white shadow-md">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $lead->created_at->format('M d, Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $lead->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4">
                                        <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No leads found</h3>
                                            <p class="text-gray-500 mb-6 max-w-md">
                                                @if(request()->has('filter') || request()->has('search') || request()->has('status'))
                                                    <span class="block text-sm">Try adjusting your filters or search criteria.</span>
                                                    <span class="text-xs text-gray-400 mt-1 block">Or clear all filters to see all leads.</span>
                                                @else
                                                    <span class="block text-sm">Get started by creating your first lead.</span>
                                                    <span class="text-xs text-gray-400 mt-1 block">Build your pipeline and track opportunities.</span>
                                                @endif
                                            </p>
                                            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                                                <a href="{{ route('leads.create') }}" 
                                                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Create New Lead
                                                </a>
                                                @if(request()->has('filter') || request()->has('search') || request()->has('status'))
                                                    <a href="{{ route('leads.index') }}" 
                                                       class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 font-medium rounded-xl hover:bg-gray-200 transition-all duration-200 shadow-md hover:shadow-lg">
                                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                        </svg>
                                                        Clear Filters
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($leads->hasPages())
            <div class="mt-8 flex justify-center">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl border border-white/20 shadow-xl p-4">
                    {{ $leads->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
