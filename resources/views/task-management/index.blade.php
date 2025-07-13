@extends('layouts.app')

@section('title', 'Task Management')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="px-6 py-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center mb-6 sm:mb-0">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-xl flex items-center justify-center mr-4 shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v2m0 0v6a2 2 0 002 2h6a2 2 0 002-2V9m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m-6 4h.01M15 13h.01M9 17h.01"></path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 mb-1">Task Management</h1>
                            <p class="text-gray-600">Manage and track your tasks efficiently</p>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <button onclick="refreshTasks()" 
                                class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-4 py-2.5 rounded-lg transition-all duration-200 flex items-center justify-center font-medium shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Refresh
                        </button>
                        <button onclick="exportTasks()" 
                                class="bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white px-4 py-2.5 rounded-lg transition-all duration-200 flex items-center justify-center font-medium shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export CSV
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Tasks</p>
                        <p class="text-3xl font-bold text-gray-900" id="total-tasks">{{ $taskCount }}</p>
                        <p class="text-xs text-gray-500 mt-1">All active tasks</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v2m0 0v6a2 2 0 002 2h6a2 2 0 002-2V9m0 0h2a2 2 0 002-2V7a2 2 0 00-2-2h-2m-6 4h.01M15 13h.01M9 17h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Current Status</p>
                        <p class="text-3xl font-bold text-gray-900">{{ ucfirst($filters['task_status'] ?? 'open') }}</p>
                        <p class="text-xs text-gray-500 mt-1">Filter status</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-r from-emerald-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Open Tasks</p>
                        <p class="text-3xl font-bold text-amber-600" id="open-tasks">{{ collect($tasks ?? [])->where('task_status', 0)->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Pending completion</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Completed</p>
                        <p class="text-3xl font-bold text-green-600" id="completed-tasks">{{ collect($tasks ?? [])->where('task_status', 1)->count() }}</p>
                        <p class="text-xs text-gray-500 mt-1">Successfully done</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Display -->
        @if(isset($error))
            <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4 mb-6 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Error occurred</p>
                        <p class="text-sm text-red-700 mt-1">{{ $error }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Success Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 rounded-lg p-4 mb-6 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">Success!</p>
                        <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Messages -->
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 rounded-lg p-4 mb-6 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">Error occurred</p>
                        <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Debug Info (Optional - can be removed in production) -->
        @if(config('app.debug'))
            <div class="bg-gradient-to-r from-yellow-50 to-amber-50 border-l-4 border-yellow-400 rounded-lg p-4 mb-6 shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-yellow-800">Debug Information</p>
                        <div class="text-sm text-yellow-700 mt-1">
                            <p class="font-medium">API Parameters:</p>
                            <p class="mt-1">
                                <span class="font-medium">Auto-set:</span> 
                                @if(auth()->user()->isAdmin() || auth()->user()->isSuperAdmin())
                                    b2b_admin_id={{ auth()->id() }}, b2b_member_id=0 (Admin User)
                                @else
                                    b2b_member_id={{ auth()->id() }}, b2b_admin_id={{ auth()->user()->admin_id ?? 'N/A' }} (Member User)
                                @endif
                                | return=full | page={{ $filters['page'] ?? 1 }} | limit=20
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Filters Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="border-b border-gray-200 px-6 py-4 bg-gray-50 rounded-t-xl">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                    </div>
                    Advanced Filters
                </h3>
            </div>
            <div class="p-6">
                <form id="filter-form" method="GET" action="{{ route('task-management.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label for="visa_form_id" class="block text-sm font-semibold text-gray-700">Visa Form ID</label>
                            <input type="number" id="visa_form_id" name="visa_form_id" 
                                   value="{{ $filters['visa_form_id'] ?? '' }}" 
                                   placeholder="Enter Visa Form ID"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="task_status" class="block text-sm font-semibold text-gray-700">Task Status</label>
                            <select id="task_status" name="task_status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white">
                                <option value="open" {{ ($filters['task_status'] ?? 'open') == 'open' ? 'selected' : '' }}>Open Tasks</option>
                                <option value="closed" {{ ($filters['task_status'] ?? 'open') == 'closed' ? 'selected' : '' }}>Completed Tasks</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label for="deadline_start" class="block text-sm font-semibold text-gray-700">Deadline From</label>
                            <input type="date" id="deadline_start" name="deadline_start" 
                                   value="{{ $filters['deadline_start'] ?? '' }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white">
                        </div>
                        
                        <div class="space-y-2">
                            <label for="deadline_end" class="block text-sm font-semibold text-gray-700">Deadline To</label>
                            <input type="date" id="deadline_end" name="deadline_end" 
                                   value="{{ $filters['deadline_end'] ?? '' }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 bg-gray-50 focus:bg-white">
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row justify-start gap-3 mt-6 pt-6 border-t border-gray-200">
                        <button type="submit" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg transition-all duration-200 flex items-center justify-center font-medium shadow-lg hover:shadow-xl">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Apply Filters
                        </button>
                        <button type="button" onclick="clearFilters()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-lg transition-all duration-200 flex items-center justify-center font-medium shadow-sm hover:shadow-md">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear Filters
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="border-b border-gray-200 px-6 py-4 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0V4a1 1 0 011-1h3m0 0a1 1 0 011 1v4h2m0 0a1 1 0 011-1h3a1 1 0 011 1v12a1 1 0 01-1 1H9a1 1 0 01-1-1V8z"></path>
                        </svg>
                    </div>
                    Task List
                    <span class="ml-2 px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">{{ count($tasks ?? []) }} tasks</span>
                </h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Task ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Task Key</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Task Name</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deadline</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Visa Form ID</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Added By</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if($success && !empty($tasks))
                            @foreach($tasks as $task)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                <span class="text-xs font-semibold text-blue-600">#{{ $task['id'] ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 text-blue-800 border border-blue-200">
                                            {{ $task['task_key'] ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900 max-w-xs truncate" title="{{ $task['task_name'] ?? 'N/A' }}">
                                            {{ $task['task_name'] ?? 'N/A' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if(($task['task_status'] ?? 1) == 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                </svg>
                                                Open
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Completed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            {{ isset($task['task_deadline']) ? \Carbon\Carbon::parse($task['task_deadline'])->format('M d, Y') : 'No deadline' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $task['visa_form_id'] ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-gray-200 rounded-full flex items-center justify-center mr-2">
                                                <span class="text-xs font-medium text-gray-600">{{ substr($task['added_by'] ?? 'U', 0, 1) }}</span>
                                            </div>
                                            {{ $task['added_by'] ?? 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ isset($task['created_at']) ? \Carbon\Carbon::parse($task['created_at'])->format('M d, Y H:i') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button onclick="viewTask({{ json_encode($task) }})" 
                                                    class="p-2 text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-all duration-200"
                                                    title="View Details">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            @if(isset($task['visa_form_id']) && $task['visa_form_id'])
                                                <a href="{{ url('applications/api/' . $task['visa_form_id']) }}" target="_blank" 
                                                   class="p-2 text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                                   title="View Application">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                            @if(($task['task_status'] ?? 1) == 0)
                                                <button onclick="showUploadModal({{ $task['id'] ?? 0 }})" 
                                                        class="p-2 text-green-600 hover:text-green-900 hover:bg-green-50 rounded-lg transition-all duration-200"
                                                        title="Upload & Complete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            @if($task['task_file'])
                                                <a href="{{ $task['task_file'] }}" target="_blank" 
                                                   class="p-2 text-purple-600 hover:text-purple-900 hover:bg-purple-50 rounded-lg transition-all duration-200"
                                                   title="Download File">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    @if(!$success)
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Failed to load tasks</h3>
                                            <p class="text-gray-500">There was an error connecting to the API. Please try again.</p>
                                        </div>
                                    @else
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">No tasks found</h3>
                                            <p class="text-gray-500">Try adjusting your filters or check back later for new tasks.</p>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($success && !empty($pagination))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-6">
                <div class="flex flex-col sm:flex-row justify-between items-center px-6 py-4">
                    <div class="text-sm text-gray-700 mb-4 sm:mb-0">
                        <span class="font-medium">Showing {{ $pagination['from'] ?? 1 }} to {{ $pagination['to'] ?? 0 }}</span>
                        of 
                        <span class="font-medium">{{ $pagination['total'] ?? 0 }} results</span>
                        <span class="text-gray-500 ml-2">(20 per page)</span>
                    </div>
                    <div class="flex space-x-2">
                        @if(isset($pagination['prev_page']))
                            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['prev_page']]) }}" 
                               class="bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-gray-700 px-4 py-2 rounded-lg transition-all duration-200 flex items-center font-medium shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Previous
                            </a>
                        @endif
                        
                        @if(isset($pagination['next_page']))
                            <a href="{{ request()->fullUrlWithQuery(['page' => $pagination['next_page']]) }}" 
                               class="bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 hover:text-gray-700 px-4 py-2 rounded-lg transition-all duration-200 flex items-center font-medium shadow-sm hover:shadow-md">
                                Next
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Task Details Modal -->
<div id="taskModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Task Details
                        </h3>
                        <div class="mt-4" id="taskModalBody">
                            <!-- Task details will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- File Upload Modal -->
<div id="uploadModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="upload-modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeUploadModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="upload-modal-title">
                                Complete Task
                            </h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-600 mb-4">
                                    Upload a document to complete this task. Once uploaded, the task will be marked as completed.
                                </p>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="task_document" class="block text-sm font-medium text-gray-700">
                                            Select Document
                                        </label>
                                        <input type="file" id="task_document" name="task_document" 
                                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.txt"
                                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                               required>
                                        <p class="mt-1 text-xs text-gray-500">
                                            Supported formats: PDF, DOC, DOCX, JPG, JPEG, PNG, TXT (Max: 10MB)
                                        </p>
                                    </div>
                                    
                                    <div id="uploadProgress" class="hidden">
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div id="progressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                        </div>
                                        <p class="text-sm text-gray-600 mt-2">Uploading...</p>
                                    </div>
                                    
                                    <div id="uploadError" class="hidden bg-red-50 border border-red-200 rounded-md p-3">
                                        <div class="flex">
                                            <svg class="w-5 h-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <p class="text-red-800 text-sm" id="uploadErrorText"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Upload & Complete
                    </button>
                    <button type="button" onclick="closeUploadModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Ensure all functions are in global scope
window.currentTaskId = null;

// Test function to verify JavaScript is loading
window.testFunction = function() {
    alert('JavaScript is working!');
    console.log('Test function executed successfully');
};

window.refreshTasks = function() {
    console.log('refreshTasks called');
    location.reload();
};

window.clearFilters = function() {
    console.log('clearFilters called');
    const form = document.getElementById('filter-form');
    if (form) {
        form.reset();
        // Set default values
        const statusSelect = document.getElementById('task_status');
        if (statusSelect) {
            statusSelect.value = 'open';
        }
    }
};

window.exportTasks = function() {
    console.log('exportTasks called');
    try {
        const form = document.getElementById('filter-form');
        if (!form) {
            console.error('Filter form not found');
            return;
        }
        
        const formData = new FormData(form);
        const params = new URLSearchParams();
        
        for (let [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }
        
        const exportUrl = "{{ route('task-management.export') }}?" + params.toString();
        console.log('Export URL:', exportUrl);
        window.location.href = exportUrl;
    } catch (error) {
        console.error('Export error:', error);
        alert('Export failed: ' + error.message);
    }
};

window.showUploadModal = function(taskId) {
    console.log('showUploadModal called with taskId:', taskId);
    
    try {
        window.currentTaskId = taskId;
        const uploadModal = document.getElementById('uploadModal');
        
        if (!uploadModal) {
            console.error('Upload modal element not found');
            alert('Upload modal not found. Please refresh the page.');
            return;
        }
        
        // Show modal
        uploadModal.classList.remove('hidden');
        console.log('Modal shown successfully');
        
        // Reset form
        const uploadForm = document.getElementById('uploadForm');
        if (uploadForm) {
            uploadForm.reset();
        }
        
        // Hide progress and error elements
        const uploadProgress = document.getElementById('uploadProgress');
        if (uploadProgress) {
            uploadProgress.classList.add('hidden');
        }
        
        const uploadError = document.getElementById('uploadError');
        if (uploadError) {
            uploadError.classList.add('hidden');
        }
        
        const progressBar = document.getElementById('progressBar');
        if (progressBar) {
            progressBar.style.width = '0%';
        }
        
    } catch (error) {
        console.error('showUploadModal error:', error);
        alert('Failed to open upload modal: ' + error.message);
    }
};

window.closeUploadModal = function() {
    console.log('closeUploadModal called');
    
    try {
        const uploadModal = document.getElementById('uploadModal');
        if (uploadModal) {
            uploadModal.classList.add('hidden');
        }
        window.currentTaskId = null;
    } catch (error) {
        console.error('closeUploadModal error:', error);
    }
};

window.viewTask = function(task) {
    console.log('viewTask called with task:', task);
    
    try {
        const modalBody = document.getElementById('taskModalBody');
        const modalTitle = document.getElementById('modal-title');
        
        if (!modalBody || !modalTitle) {
            console.error('Modal elements not found');
            alert('Modal elements not found. Please refresh the page.');
            return;
        }
        
        modalTitle.textContent = task.task_name || 'Task Details';
        
        modalBody.innerHTML = '<div class="space-y-4"><p class="text-sm text-gray-900">Loading task details...</p></div>';
        
        // Show modal first
        document.getElementById('taskModal').classList.remove('hidden');
        
        // Then populate with content
        setTimeout(function() {
            modalBody.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Task ID</label>
                            <p class="mt-1 text-sm text-gray-900">${task.id || 'N/A'}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Task Key</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                ${task.task_key || 'N/A'}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="mt-1 inline-flex px-2 py-1 text-xs font-semibold rounded-full ${task.task_status == 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'}">
                                ${task.task_status == 0 ? 'Open' : 'Completed'}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Deadline</label>
                            <p class="mt-1 text-sm text-gray-900">${task.task_deadline || 'N/A'}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Task Name</label>
                        <div class="mt-1 p-3 bg-gray-50 rounded-lg border">
                            <p class="text-sm text-gray-900">${task.task_name || 'No task name available'}</p>
                        </div>
                    </div>
                </div>
            `;
        }, 100);
        
    } catch (error) {
        console.error('viewTask error:', error);
        alert('Failed to view task: ' + error.message);
    }
};

window.closeModal = function() {
    console.log('closeModal called');
    
    try {
        const taskModal = document.getElementById('taskModal');
        if (taskModal) {
            taskModal.classList.add('hidden');
        }
    } catch (error) {
        console.error('closeModal error:', error);
    }
};

window.showUploadError = function(message) {
    console.log('showUploadError called with message:', message);
    
    try {
        const uploadErrorText = document.getElementById('uploadErrorText');
        const uploadError = document.getElementById('uploadError');
        
        if (uploadErrorText) {
            uploadErrorText.textContent = message;
        }
        if (uploadError) {
            uploadError.classList.remove('hidden');
        }
    } catch (error) {
        console.error('showUploadError error:', error);
    }
}

 

// DOM Ready functionality
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Setting up event listeners');
    
    // Handle file upload form submission
    const uploadForm = document.getElementById('uploadForm');
    if (uploadForm) {
        console.log('Upload form found, adding event listener');
        
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Upload form submitted');
            
            if (!window.currentTaskId) {
                window.showUploadError('No task selected');
                return;
            }
            
            const fileInput = document.getElementById('task_document');
            const file = fileInput ? fileInput.files[0] : null;
            
            if (!file) {
                window.showUploadError('Please select a file to upload');
                return;
            }
            
            // Check file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                window.showUploadError('File size must be less than 10MB');
                return;
            }
            
            console.log('Starting file upload for task:', window.currentTaskId);
            
            // Show progress
            const uploadProgress = document.getElementById('uploadProgress');
            const uploadError = document.getElementById('uploadError');
            
            if (uploadProgress) {
                uploadProgress.classList.remove('hidden');
            }
            if (uploadError) {
                uploadError.classList.add('hidden');
            }
            
            // Create form data
            const formData = new FormData();
            formData.append('task_id', window.currentTaskId);
            formData.append('task_document', file);
            formData.append('_token', '{{ csrf_token() }}');
            
            // Upload file
            fetch('{{ route("task-management.complete") }}', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                console.log('Upload response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Upload response data:', data);
                
                if (uploadProgress) {
                    uploadProgress.classList.add('hidden');
                }
                
                if (data.success) {
                    alert('Task completed successfully!');
                    window.closeUploadModal();
                    location.reload();
                } else {
                    window.showUploadError(data.error || 'Failed to complete task');
                }
            })
            .catch(error => {
                console.error('Upload error:', error);
                if (uploadProgress) {
                    uploadProgress.classList.add('hidden');
                }
                window.showUploadError('An error occurred while uploading the file');
            });
        });
    } else {
        console.error('Upload form not found');
    }
    
    // Close modals with Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            window.closeModal();
            window.closeUploadModal();
        }
    });
    
    console.log('DOM setup complete');
});

// Final test - log when script is fully loaded
console.log('JavaScript file loaded successfully');
</script>
@endpush
