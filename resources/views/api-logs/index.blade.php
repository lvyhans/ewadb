@extends('layouts.app')

@section('page-title', 'API Logs - Tarun Demo API')

@section('content')
<div class="mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold gradient-text">API Request Logs</h1>
                <p class="text-gray-600 mt-2">Complete logs of all data sent to Tarun Demo API when creating applications</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('applications.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-medium rounded-xl hover:bg-gray-600 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Applications
                </a>
                @if(isset($logs) && $logs)
                    <a href="{{ route('applications.download-api-logs') }}" 
                       class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download Logs
                    </a>
                    <a href="{{ route('applications.clear-api-logs') }}" 
                       onclick="return confirm('Are you sure you want to clear all API logs? This action cannot be undone.')"
                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Clear Logs
                    </a>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if(isset($error))
        <div class="glass-effect rounded-2xl shadow-xl border border-red-200 p-6">
            <div class="text-center">
                <svg class="w-16 h-16 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-red-900 mb-2">Error Loading Logs</h3>
                <p class="text-red-700">{{ $error }}</p>
            </div>
        </div>
    @elseif(isset($message))
        <div class="glass-effect rounded-2xl shadow-xl border border-blue-200 p-6">
            <div class="text-center">
                <svg class="w-16 h-16 text-blue-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-blue-900 mb-2">No Logs Found</h3>
                <p class="text-blue-700">{{ $message }}</p>
            </div>
        </div>
    @elseif(isset($logs) && $logs)
        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $logs['total_count'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Successful</p>
                        <p class="text-2xl font-bold text-green-600">{{ $logs['successful_requests'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Failed</p>
                        <p class="text-2xl font-bold text-red-600">{{ $logs['failed_requests'] ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Last Updated</p>
                        <p class="text-sm font-bold text-gray-900">{{ \Carbon\Carbon::parse($logs['last_updated'])->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
            <form method="GET" action="{{ route('applications.view-api-logs') }}" class="flex items-center space-x-4">
                <div class="flex-1">
                    <label for="application_id" class="block text-sm font-medium text-gray-700 mb-1">Application ID</label>
                    <input type="number" name="application_id" id="application_id" value="{{ request('application_id') }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex-1">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">All</option>
                        <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                        <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Filter
                    </button>
                    <a href="{{ route('applications.view-api-logs') }}" class="ml-2 px-6 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Logs Table -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-white/20">
                    <thead class="bg-white/10">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attempt</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payload Size</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/20">
                        @forelse($logs['requests'] as $index => $log)
                        <tr class="hover:bg-white/10 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($log['timestamp'])->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">ID: {{ $log['application_id'] }}</div>
                                <div class="text-sm text-gray-500">{{ $log['applicant_name'] }}</div>
                                <div class="text-xs text-gray-400">{{ $log['application_number'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($log['response']['successful'])
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $log['response']['status_code'] }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $log['response']['status_code'] }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $log['attempt_number'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ number_format($log['payload_size'] / 1024, 2) }} KB
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewLogDetails({{ $index }})" 
                                        class="text-blue-600 hover:text-blue-900 mr-3">
                                    View Details
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No logs found</h3>
                                    <p class="text-gray-500">Create some applications to see API logs here.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Log Details Modal -->
<div id="logDetailsModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">API Log Details</h3>
                    <button onclick="closeLogDetailsModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent" class="max-h-96 overflow-auto">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="closeLogDetailsModal()" 
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Store logs data for JavaScript access
const logsData = @json($logs['requests'] ?? []);

function viewLogDetails(index) {
    const log = logsData[index];
    if (!log) return;

    document.getElementById('modalTitle').textContent = `API Log Details - Application ${log.application_id}`;
    
    const content = `
        <div class="space-y-6">
            <!-- Basic Info -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-2">Request Information</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><strong>Timestamp:</strong> ${new Date(log.timestamp).toLocaleString()}</div>
                    <div><strong>Application ID:</strong> ${log.application_id}</div>
                    <div><strong>Applicant:</strong> ${log.applicant_name}</div>
                    <div><strong>Email:</strong> ${log.applicant_email}</div>
                    <div><strong>Phone:</strong> ${log.applicant_phone}</div>
                    <div><strong>Created By:</strong> ${log.created_by}</div>
                    <div><strong>Attempt:</strong> ${log.attempt_number}</div>
                    <div><strong>API Endpoint:</strong> ${log.api_endpoint}</div>
                </div>
            </div>

            <!-- Response Info -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-2">Response Information</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><strong>Status Code:</strong> <span class="${log.response.successful ? 'text-green-600' : 'text-red-600'}">${log.response.status_code}</span></div>
                    <div><strong>Successful:</strong> ${log.response.successful ? 'Yes' : 'No'}</div>
                    <div><strong>Payload Size:</strong> ${(log.payload_size / 1024).toFixed(2)} KB</div>
                    <div><strong>Documents Count:</strong> ${log.documents_count}</div>
                    <div><strong>Course Options:</strong> ${log.course_options_count}</div>
                    <div><strong>Employment History:</strong> ${log.employment_history_count}</div>
                </div>
            </div>

            <!-- Request Payload -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-2">Request Payload</h4>
                <pre class="bg-white p-3 rounded border text-xs overflow-auto max-h-64">${JSON.stringify(log.request_payload, null, 2)}</pre>
            </div>

            <!-- Response Body -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-medium text-gray-900 mb-2">Response Body</h4>
                <pre class="bg-white p-3 rounded border text-xs overflow-auto max-h-64">${log.response.body}</pre>
            </div>
        </div>
    `;

    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('logDetailsModal').classList.remove('hidden');
}

function closeLogDetailsModal() {
    document.getElementById('logDetailsModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('logDetailsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLogDetailsModal();
    }
});
</script>
@endsection
