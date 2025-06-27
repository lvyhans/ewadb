@extends('layouts.app')

@section('content')
<div class="py-12 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white">
        <div class="mb-6 bg-white p-6 rounded-lg shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Followup Dashboard</h2>
                    <p class="text-gray-600">Overview of your followup activities</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('followups.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        All Followups
                    </a>
                    <a href="{{ route('followups.today') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Today's Followups
                    </a>
                    <a href="{{ route('followups.overdue') }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Overdue Followups
                    </a>
                    <a href="{{ route('leads.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Followup
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 bg-white p-6 rounded-lg shadow-sm">
            <a href="{{ route('followups.today') }}" class="bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 p-6 transition-colors duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500/20">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Today's Followups</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['today'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500/20">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">This Week</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['this_week'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <a href="{{ route('followups.overdue') }}" class="bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 p-6 transition-colors duration-200">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-500/20">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Overdue</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['overdue'] ?? 0 }}</p>
                    </div>
                </div>
            </a>

            <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500/20">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Completed</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['completed'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-white p-6 rounded-lg shadow-sm">
            <!-- Today's Schedule -->
            <div class="bg-blue-50 rounded-lg border border-blue-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Today's Schedule
                </h3>
                
                <div class="space-y-3">
                    @forelse($todaysFollowups ?? [] as $followup)
                    <div class="bg-white/5 rounded-lg p-4 border border-white/10 hover:bg-white/10 transition-colors duration-200">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                        @if($followup->type === 'call') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                        @elseif($followup->type === 'email') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                        @elseif($followup->type === 'meeting') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst($followup->type) }}
                                    </span>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $followup->scheduled_at->format('g:i A') }}
                                    </span>
                                    @if($followup->scheduled_at->isPast() && $followup->status === 'pending')
                                        <span class="text-xs text-red-500 font-medium">Overdue</span>
                                    @endif
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if($followup->lead && $followup->lead->id)
                                        <a href="{{ route('leads.show', $followup->lead->id) }}" class="hover:text-blue-500">
                                            {{ $followup->lead->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">Lead not available</span>
                                    @endif
                                </h4>
                                @if($followup->subject)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $followup->subject }}</p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                @if($followup->status === 'pending')
                                    <button onclick="markComplete('{{ $followup->id }}')" 
                                            class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500">No followups scheduled for today</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Overdue Items -->
            <div class="bg-red-50 rounded-lg border border-red-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Overdue Items
                </h3>
                
                <div class="space-y-3">
                    @forelse($overdueFollowups ?? [] as $followup)
                    <div class="bg-red-500/10 border border-red-500/20 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                        {{ ucfirst($followup->type) }}
                                    </span>
                                    <span class="text-sm text-red-600 dark:text-red-400">
                                        {{ $followup->scheduled_at->format('M j, g:i A') }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                                    @if($followup->lead && $followup->lead->id)
                                        <a href="{{ route('leads.show', $followup->lead->id) }}" class="hover:text-blue-500">
                                            {{ $followup->lead->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">Lead not available</span>
                                    @endif
                                </h4>
                                @if($followup->subject)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $followup->subject }}</p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="markComplete('{{ $followup->id }}')" 
                                        class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                                <button onclick="reschedule('{{ $followup->id }}')" 
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-8 h-8 text-green-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No overdue followups</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 bg-white p-6 rounded-lg shadow-sm">
            <!-- Upcoming This Week -->
            <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Upcoming This Week
                </h3>
                
                <div class="space-y-3">
                    @forelse($upcomingFollowups ?? [] as $followup)
                    <div class="bg-white rounded-lg p-4 border border-yellow-300">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                        @if($followup->type === 'call') bg-green-100 text-green-800
                                        @elseif($followup->type === 'email') bg-blue-100 text-blue-800
                                        @elseif($followup->type === 'meeting') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst($followup->type) }}
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        {{ $followup->scheduled_at->format('D, M j - g:i A') }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900">
                                    @if($followup->lead && $followup->lead->id)
                                        <a href="{{ route('leads.show', $followup->lead->id) }}" class="hover:text-blue-500">
                                            {{ $followup->lead->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">Lead not available</span>
                                    @endif
                                </h4>
                                @if($followup->subject)
                                <p class="text-sm text-gray-600">{{ $followup->subject }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p class="text-gray-500">No upcoming followups this week</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-green-50 rounded-lg border border-green-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Recently Completed
                </h3>
                
                <div class="space-y-3">
                    @forelse($recentCompleted ?? [] as $followup)
                    <div class="bg-white border border-green-300 rounded-lg p-4">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                        {{ ucfirst($followup->type) }}
                                    </span>
                                    <span class="text-sm text-green-600">
                                        Completed {{ $followup->completed_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-medium text-gray-900">
                                    @if($followup->lead && $followup->lead->id)
                                        <a href="{{ route('leads.show', $followup->lead->id) }}" class="hover:text-blue-500">
                                            {{ $followup->lead->name }}
                                        </a>
                                    @else
                                        <span class="text-gray-500">Lead not available</span>
                                    @endif
                                </h4>
                                @if($followup->subject)
                                <p class="text-sm text-gray-600">{{ $followup->subject }}</p>
                                @endif
                            </div>
                            <a href="{{ route('followups.show', $followup) }}" 
                               class="text-blue-600 hover:text-blue-900">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8">
                        <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-gray-500">No recent completed followups</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script>
function markComplete(followupId) {
    if (confirm('Mark this followup as completed?')) {
        fetch(`/followups/${followupId}/complete`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error marking followup as complete');
            }
        });
    }
}

function reschedule(followupId) {
    const newDate = prompt('Enter new date (YYYY-MM-DD):');
    const newTime = prompt('Enter new time (HH:MM):');
    
    if (newDate && newTime) {
        fetch(`/followups/${followupId}/reschedule`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                scheduled_date: newDate,
                scheduled_time: newTime
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error rescheduling followup');
            }
        });
    }
}
</script>
@endsection
