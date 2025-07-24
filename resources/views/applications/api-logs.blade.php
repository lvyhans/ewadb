@extends('layouts.app')

@section('page-title', 'API Logs - Tarun Demo API')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-8 mb-8 border border-white/20">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold gradient-text">API Request Logs</h1>
                    <p class="text-gray-600 mt-2">Monitor external API calls to Tarun Demo API</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('applications.index') }}" 
                       class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        ← Back to Applications
                    </a>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 mb-8 border border-white/20">
            <div class="flex flex-wrap gap-4 justify-between items-center">
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('applications.download-api-logs', ['type' => 'all']) }}" 
                       class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors shadow-lg">
                        📥 Download All Logs
                    </a>
                    <a href="{{ route('applications.clear-api-logs', ['type' => 'all']) }}" 
                       onclick="return confirm('Are you sure you want to clear all API logs? This action cannot be undone.')"
                       class="px-6 py-3 bg-red-600 text-white rounded-xl hover:bg-red-700 transition-colors shadow-lg">
                        🗑️ Clear All Logs
                    </a>
                </div>
                <div class="text-sm text-gray-600">
                    Total Log Files: {{ count($logs) }}
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-6 mb-8 border border-white/20">
            <form method="GET" action="{{ route('applications.view-api-logs') }}" class="flex items-center space-x-4">
                <label class="text-sm font-medium text-gray-700">Filter by Type:</label>
                <select name="type" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">All Types</option>
                    <option value="requests" {{ request('type') === 'requests' ? 'selected' : '' }}>API Requests</option>
                    <option value="payloads" {{ request('type') === 'payloads' ? 'selected' : '' }}>Payloads</option>
                </select>
                
                <label class="text-sm font-medium text-gray-700">Limit:</label>
                <select name="limit" class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="25" {{ request('limit', 50) == 25 ? 'selected' : '' }}>25 entries</option>
                    <option value="50" {{ request('limit', 50) == 50 ? 'selected' : '' }}>50 entries</option>
                    <option value="100" {{ request('limit', 50) == 100 ? 'selected' : '' }}>100 entries</option>
                </select>
                
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                    Apply Filters
                </button>
                
                @if(request()->anyFilled(['type', 'limit']))
                    <a href="{{ route('applications.view-api-logs') }}" class="ml-2 px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Clear Filters
                    </a>
                @endif
            </form>
        </div>

        <!-- Log Files Display -->
        @if(empty($logs))
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl p-8 border border-white/20 text-center">
                <div class="text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <h3 class="text-xl font-semibold mb-2">No API Logs Found</h3>
                    <p class="text-gray-600">No API logs are available to display.</p>
                </div>
            </div>
        @else
            @foreach($logs as $title => $log)
                <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl mb-8 border border-white/20 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 text-white p-6">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold">{{ $title }}</h2>
                                @if(isset($log['data']))
                                    <p class="text-blue-100 mt-1">
                                        @if($log['type'] === 'requests')
                                            Total: {{ $log['data']['total_count'] ?? 0 }} | 
                                            Success: {{ $log['data']['successful_requests'] ?? 0 }} | 
                                            Failed: {{ $log['data']['failed_requests'] ?? 0 }}
                                        @elseif($log['type'] === 'payloads')
                                            Total Payloads: {{ $log['data']['total_count'] ?? 0 }}
                                        @endif
                                        @if(isset($log['truncated']))
                                            <span class="text-yellow-200">(Showing latest entries)</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                @if($log['type'] !== 'missing')
                                    <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                                        {{ formatBytes($log['file_size']) }}
                                    </span>
                                    <span class="px-3 py-1 bg-white/20 rounded-full text-sm">
                                        {{ $log['last_modified'] }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        @if($log['type'] === 'missing')
                            <div class="text-center text-gray-500 py-8">
                                <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-semibold">{{ $log['message'] }}</p>
                                <p class="text-sm text-gray-400 mt-2">{{ $log['file_path'] }}</p>
                            </div>
                        @elseif($log['type'] === 'requests' && isset($log['data']['requests']))
                            @if(empty($log['data']['requests']))
                                <div class="text-center text-gray-500 py-8">
                                    <p class="text-lg">No API requests logged yet</p>
                                </div>
                            @else
                                <div class="space-y-4 max-h-96 overflow-y-auto">
                                    @foreach(array_reverse($log['data']['requests']) as $request)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                                        {{ $request['response']['successful'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                        {{ $request['response']['status_code'] }}
                                                        {{ $request['response']['successful'] ? 'Success' : 'Failed' }}
                                                    </span>
                                                    <span class="ml-2 text-sm text-gray-600">
                                                        {{ \Carbon\Carbon::parse($request['timestamp'])->format('Y-m-d H:i:s') }}
                                                    </span>
                                                </div>
                                                <span class="text-sm text-gray-500">
                                                    Attempt #{{ $request['attempt_number'] }}
                                                </span>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    @if(isset($request['application_id']))
                                                        <p><strong>Application:</strong> {{ $request['application_number'] ?? $request['application_id'] }}</p>
                                                        <p><strong>Applicant:</strong> {{ $request['applicant_name'] ?? 'N/A' }}</p>
                                                    @elseif(isset($request['lead_id']))
                                                        <p><strong>Lead:</strong> {{ $request['lead_ref_no'] ?? $request['lead_id'] }}</p>
                                                        <p><strong>Lead Name:</strong> {{ $request['lead_name'] ?? 'N/A' }}</p>
                                                    @endif
                                                    <p><strong>Created By:</strong> {{ $request['created_by'] ?? 'Unknown' }}</p>
                                                </div>
                                                <div>
                                                    <p><strong>Payload Size:</strong> {{ formatBytes($request['payload_size'] ?? 0) }}</p>
                                                    @if(isset($request['documents_count']))
                                                        <p><strong>Documents:</strong> {{ $request['documents_count'] }}</p>
                                                    @endif
                                                    @if(isset($request['employment_history_count']))
                                                        <p><strong>Employment Records:</strong> {{ $request['employment_history_count'] }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            @if(!$request['response']['successful'] && isset($request['response']['body']))
                                                <div class="mt-3 p-3 bg-red-50 rounded border-l-4 border-red-500">
                                                    <p class="text-sm text-red-700">
                                                        <strong>Error Response:</strong> 
                                                        {{ Str::limit($request['response']['body'], 200) }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @elseif($log['type'] === 'payloads' && isset($log['data']['payloads']))
                            @if(empty($log['data']['payloads']))
                                <div class="text-center text-gray-500 py-8">
                                    <p class="text-lg">No payloads logged yet</p>
                                </div>
                            @else
                                <div class="space-y-4 max-h-96 overflow-y-auto">
                                    @foreach(array_reverse($log['data']['payloads']) as $payload)
                                        <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition-colors">
                                            <div class="flex justify-between items-start mb-3">
                                                <div>
                                                    @if(isset($payload['application_id']))
                                                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                                            Application
                                                        </span>
                                                    @elseif(isset($payload['lead_id']))
                                                        <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-medium">
                                                            Lead
                                                        </span>
                                                    @endif
                                                    <span class="ml-2 text-sm text-gray-600">
                                                        {{ \Carbon\Carbon::parse($payload['timestamp'])->format('Y-m-d H:i:s') }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                                <div>
                                                    @if(isset($payload['application_number']))
                                                        <p><strong>Application:</strong> {{ $payload['application_number'] }}</p>
                                                        <p><strong>Applicant:</strong> {{ $payload['applicant_name'] ?? 'N/A' }}</p>
                                                    @elseif(isset($payload['lead_ref_no']))
                                                        <p><strong>Lead:</strong> {{ $payload['lead_ref_no'] }}</p>
                                                        <p><strong>Lead Name:</strong> {{ $payload['lead_name'] ?? 'N/A' }}</p>
                                                    @endif
                                                    <p><strong>Created By:</strong> {{ $payload['created_by'] ?? 'Unknown' }}</p>
                                                </div>
                                                <div>
                                                    <p><strong>Payload Type:</strong> {{ $payload['payload']['type'] ?? 'Unknown' }}</p>
                                                    @if(isset($payload['payload']['created_by_user']))
                                                        <p><strong>User:</strong> {{ $payload['payload']['created_by_user']['name'] ?? 'N/A' }}</p>
                                                        <p><strong>Role:</strong> {{ $payload['payload']['created_by_user']['role'] ?? 'N/A' }}</p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mt-3">
                                                <details class="cursor-pointer">
                                                    <summary class="text-sm font-medium text-gray-600 hover:text-gray-800">
                                                        View Full Payload ({{ formatBytes(strlen(json_encode($payload['payload']))) }})
                                                    </summary>
                                                    <pre class="mt-2 p-3 bg-gray-100 rounded text-xs overflow-x-auto max-h-40">{{ json_encode($payload['payload'], JSON_PRETTY_PRINT) }}</pre>
                                                </details>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif

                        @if($log['type'] !== 'missing')
                            <div class="mt-6 flex justify-between items-center pt-4 border-t border-gray-200">
                                <div class="text-sm text-gray-600">
                                    File: {{ basename($log['file_path']) }}
                                </div>
                                <div class="flex space-x-2">
                                    @php
                                        $downloadType = str_replace(' ', '_', strtolower(str_replace(' API', '', $title)));
                                        $downloadType = str_replace('_requests', '', $downloadType);
                                        $downloadType = str_replace('_payloads', '', $downloadType);
                                        if (strpos($title, 'Lead') !== false) {
                                            $downloadType = 'lead_' . ($log['type'] === 'requests' ? 'requests' : 'payloads');
                                        } else {
                                            $downloadType = $log['type'] === 'requests' ? 'requests' : 'payloads';
                                        }
                                    @endphp
                                    <a href="{{ route('applications.download-api-logs', ['type' => $downloadType]) }}" 
                                       class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors text-sm">
                                        Download
                                    </a>
                                    <a href="{{ route('applications.clear-api-logs', ['type' => $downloadType]) }}" 
                                       onclick="return confirm('Are you sure you want to clear this log file?')"
                                       class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors text-sm">
                                        Clear
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

@php
function formatBytes($size, $precision = 2) {
    $base = log($size, 1024);
    $suffixes = ['B', 'KB', 'MB', 'GB', 'TB'];
    return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
}
@endphp

<style>
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>
@endsection
