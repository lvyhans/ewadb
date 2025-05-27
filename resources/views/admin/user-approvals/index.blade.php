@extends('layouts.app')

@section('title', 'Pending Registrations')
@section('page-title', 'User Approval Management')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pending Registrations</h1>
                <p class="mt-1 text-sm text-gray-600">Review and approve Super Admin registrations</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">
                    {{ $pendingUsers->total() }} Pending Approval{{ $pendingUsers->total() !== 1 ? 's' : '' }}
                </span>
            </div>
        </div>

        @if($pendingUsers->count() > 0)
            <!-- Pending Users List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-200">
                        @foreach($pendingUsers as $user)
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 border-l-4 border-orange-400">
                                <div class="sm:col-span-2">
                                    <div class="flex items-start space-x-4">
                                        <!-- User Avatar -->
                                        <div class="flex-shrink-0">
                                            <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 font-medium text-lg">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <!-- User Info -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center space-x-2">
                                                <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                                    Pending
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-600">{{ $user->email }}</p>
                                            
                                            <!-- Company Info -->
                                            <div class="mt-2 space-y-1">
                                                <p class="text-sm text-gray-900 font-medium">{{ $user->company_name }}</p>
                                                <p class="text-xs text-gray-500">
                                                    GSTIN: {{ $user->gstin }} | PAN: {{ $user->pan_number }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $user->company_city }}, {{ $user->company_state }}
                                                </p>
                                            </div>
                                            
                                            <!-- Registration Date -->
                                            <p class="text-xs text-gray-500 mt-2">
                                                Registered {{ $user->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Actions -->
                                <div class="mt-4 sm:mt-0 sm:col-span-1">
                                    <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2 sm:justify-end">
                                        <!-- View Details Button -->
                                        <a href="{{ route('user-approvals.show', $user) }}" 
                                           class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            Review
                                        </a>
                                        
                                        <!-- Quick Approve Button -->
                                        <form method="POST" action="{{ route('user-approvals.approve', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    onclick="return confirm('Are you sure you want to approve this user?')"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Approve
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </dl>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $pendingUsers->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Pending Registrations</h3>
                <p class="text-gray-600">All Super Admin registrations have been processed.</p>
            </div>
        @endif
    </div>
@endsection