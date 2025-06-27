@extends('layouts.app')

@section('content')
<div class="py-12 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 bg-white">
        <!-- Header -->
        <div class="mb-6 bg-white p-6 rounded-lg shadow-sm">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Overdue Follow-ups</h2>
                    <p class="text-gray-600">Follow-ups that are past their scheduled time</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('followups.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('followups.today') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Today's Follow-ups
                    </a>
                    <a href="{{ route('leads.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-300 ease-in-out transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add Follow-up
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-white p-6 rounded-lg shadow-sm">
            <div class="bg-red-50 rounded-lg border border-red-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-500/20">
                        <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Overdue</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $overdueFollowups->total() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg border border-yellow-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500/20">
                        <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Current Page</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $overdueFollowups->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-orange-50 rounded-lg border border-orange-200 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-orange-500/20">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Needs Attention</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $overdueFollowups->total() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Follow-ups List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Overdue Follow-ups</h3>
            </div>

            @if($overdueFollowups->count() > 0)
                <div class="divide-y divide-gray-200">
                    @foreach($overdueFollowups as $followup)
                        <div class="p-6 bg-red-50 border-l-4 border-red-500 hover:bg-red-100 transition-colors duration-200">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-100 text-red-800">
                                            {{ ucfirst($followup->type) }}
                                        </span>
                                        
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                            @if($followup->priority === 'urgent') bg-red-100 text-red-800
                                            @elseif($followup->priority === 'high') bg-orange-100 text-orange-800
                                            @elseif($followup->priority === 'medium') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300
                                            @endif">
                                            {{ ucfirst($followup->priority) }}
                                        </span>

                                        <span class="text-sm font-medium text-red-600 dark:text-red-400">
                                            {{ $followup->scheduled_at->format('M j, g:i A') }}
                                        </span>

                                        <span class="text-xs text-red-500 font-medium bg-red-100 px-2 py-1 rounded">
                                            {{ $followup->scheduled_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-1">
                                        {{ $followup->subject }}
                                    </h4>

                                    <div class="flex items-center space-x-4 mb-2">
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            @if($followup->lead && $followup->lead->id)
                                                <a href="{{ route('leads.show', $followup->lead->id) }}" class="hover:text-blue-500">
                                                    {{ $followup->lead->name }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">Lead not found</span>
                                            @endif
                                        </div>
                                        
                                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Created by {{ $followup->user->name }}
                                        </div>
                                    </div>

                                    @if($followup->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ Str::limit($followup->description, 150) }}</p>
                                    @endif

                                    @if($followup->notes)
                                        <p class="text-sm text-gray-500 dark:text-gray-500">
                                            <strong>Notes:</strong> {{ Str::limit($followup->notes, 100) }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex space-x-2 ml-4">
                                    <button onclick="markComplete('{{ $followup->id }}')" 
                                            class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition duration-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Complete
                                    </button>
                                    
                                    <button onclick="reschedule('{{ $followup->id }}')" 
                                            class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition duration-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Reschedule
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($overdueFollowups->hasPages())
                    <div class="px-6 py-4 border-t border-white/20">
                        {{ $overdueFollowups->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <svg class="w-12 h-12 text-green-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No overdue follow-ups</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">Great job staying on top of your follow-ups!</p>
                    <a href="{{ route('followups.today') }}" 
                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition duration-300 mr-2">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Today's Follow-ups
                    </a>
                    <a href="{{ route('leads.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Schedule Follow-up
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function markComplete(followupId) {
    if (confirm('Mark this follow-up as completed?')) {
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
                alert('Error marking follow-up as complete');
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
                alert('Error rescheduling follow-up');
            }
        });
    }
}
</script>
@endsection
