@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Notifications</h1>
            <p class="text-gray-600 mt-1">Stay updated with your lead activities</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="markAllAsRead()" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                Mark All as Read
            </button>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="glass-effect rounded-xl p-6">
        @if($notifications->isEmpty())
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5-5V9a4 4 0 00-8 0v3L2 17h5m8 0v1a3 3 0 01-6 0v-1m6 0H9"></path>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
                <p class="text-gray-600">You're all caught up! New notifications will appear here.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($notifications as $notification)
                    <div class="flex items-start space-x-4 p-4 rounded-lg transition-colors {{ $notification['read_at'] ? 'bg-gray-50/50' : 'bg-blue-50/50 border border-blue-100' }}">
                        <div class="flex-shrink-0">
                            <div class="w-3 h-3 rounded-full mt-2 {{ $notification['read_at'] ? 'bg-gray-400' : 'bg-blue-500' }}"></div>
                        </div>
                        
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">{{ $notification['title'] }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">{{ $notification['message'] }}</p>
                                    
                                    <div class="flex items-center space-x-4 mt-3">
                                        <span class="text-xs text-gray-500">{{ $notification['time_ago'] }}</span>
                                        @if($notification['lead_ref_no'])
                                            <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">{{ $notification['lead_ref_no'] }}</span>
                                        @endif
                                        @if($notification['submitted_by'])
                                            <span class="text-xs text-gray-500">by {{ $notification['submitted_by'] }}</span>
                                        @endif
                                        <span class="text-xs bg-{{ $notification['priority'] === 'high' ? 'red' : ($notification['priority'] === 'medium' ? 'yellow' : 'green') }}-100 text-{{ $notification['priority'] === 'high' ? 'red' : ($notification['priority'] === 'medium' ? 'yellow' : 'green') }}-700 px-2 py-1 rounded">
                                            {{ ucfirst($notification['priority']) }} Priority
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="flex items-center space-x-2 ml-4">
                                    @if($notification['action_url'])
                                        <a href="{{ route('notifications.read', $notification['id']) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            View Lead
                                        </a>
                                    @endif
                                    
                                    @if(!$notification['read_at'])
                                        <button onclick="markAsRead('{{ $notification['id'] }}')" 
                                                class="text-gray-500 hover:text-gray-700 text-sm">
                                            Mark as read
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    async function markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error marking notification as read:', error);
        }
    }
    
    async function markAllAsRead() {
        try {
            const response = await fetch('/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            if (response.ok) {
                location.reload();
            }
        } catch (error) {
            console.error('Error marking all notifications as read:', error);
        }
    }
</script>
@endpush
@endsection
