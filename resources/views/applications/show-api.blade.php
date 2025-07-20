@extends('layouts.app')

@section('page-title', 'Application Details')

@section('content')
<div class="mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-3xl font-bold gradient-text">Application Details</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        API Data
                    </span>
                </div>
                <p class="text-gray-600 mt-2">
                    Application ID: {{ $application['visa_form_id'] }}
                    @if(!empty($application['ref_no']))
                        | Reference: {{ $application['ref_no'] }}
                    @endif
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('applications.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-medium rounded-xl hover:bg-gray-600 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Applications
                </a>
                
                <a href="{{ route('applications.show-api-details', $application['visa_form_id']) }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-medium rounded-xl hover:bg-purple-700 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Complete Details
                </a>
                
                @if($application['documents_count'] > 0)
                    <button onclick="showDocuments({{ json_encode($application['documents']) }}, '{{ $application['name'] }}')" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        View Documents ({{ $application['documents_count'] }})
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Application Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Personal Information
            </h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Full Name:</span>
                    <span class="font-medium">{{ $application['name'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium">{{ $application['email'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Phone:</span>
                    <span class="font-medium">{{ $application['phone'] ?? 'N/A' }}</span>
                </div>
                @if(!empty($application['resi_city']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">City:</span>
                        <span class="font-medium">{{ $application['resi_city'] }}</span>
                    </div>
                @endif
                @if(!empty($application['dob']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date of Birth:</span>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($application['dob'])->format('M d, Y') }}</span>
                    </div>
                @endif
                @if(!empty($application['gender']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Gender:</span>
                        <span class="font-medium">{{ ucfirst($application['gender']) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Application Status -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Application Status
            </h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Status:</span>
                    @php
                        $status = $application['status'] ?? 'pending';
                        $statusClass = match($status) {
                            'pending' => 'bg-yellow-100 text-yellow-800',
                            'in_progress' => 'bg-blue-100 text-blue-800',
                            'approved' => 'bg-green-100 text-green-800',
                            'rejected' => 'bg-red-100 text-red-800',
                            'completed' => 'bg-purple-100 text-purple-800',
                            default => 'bg-gray-100 text-gray-800'
                        };
                    @endphp
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $statusClass }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Created:</span>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($application['date'])->format('M d, Y H:i') }}</span>
                </div>
                @if(!empty($application['visa_status']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Visa Status:</span>
                        <span class="font-medium">{{ $application['visa_status'] }}</span>
                    </div>
                @endif
                @if(!empty($application['source2']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Source:</span>
                        <span class="font-medium">{{ $application['source2'] }}</span>
                    </div>
                @endif
                @if($application['admissions_count'] > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Admissions:</span>
                        <span class="font-medium text-purple-600">{{ $application['admissions_count'] }} received</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Study Preferences -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Study Preferences
            </h2>
            
            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Country:</span>
                    <span class="font-medium">{{ $application['country'] ?? 'Not specified' }}</span>
                </div>
                @if(!empty($application['city']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">City:</span>
                        <span class="font-medium">{{ $application['city'] }}</span>
                    </div>
                @endif
                @if(!empty($application['institute_name']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Institute:</span>
                        <span class="font-medium">{{ $application['institute_name'] }}</span>
                    </div>
                @endif
                @if(!empty($application['course']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Course:</span>
                        <span class="font-medium">{{ ucfirst($application['course']) }}</span>
                    </div>
                @endif
                @if(!empty($application['study_course']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Study Course:</span>
                        <span class="font-medium">{{ $application['study_course'] }}</span>
                    </div>
                @endif
                @if(!empty($application['intake']) || !empty($application['intakeyear']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Intake:</span>
                        <span class="font-medium">{{ $application['intake'] ?? '' }} {{ $application['intakeyear'] ?? '' }}</span>
                    </div>
                @endif
                @if(!empty($application['last_qual']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Qualification:</span>
                        <span class="font-medium">{{ ucfirst($application['last_qual']) }}</span>
                    </div>
                @endif
            </div>
        </div>

        <!-- English Proficiency -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 text-orange-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                </svg>
                English Proficiency
            </h2>
            
            <div class="space-y-3">
                @if(!empty($application['ielts_overall']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">IELTS Overall:</span>
                        <span class="font-medium text-green-600">{{ $application['ielts_overall'] }}</span>
                    </div>
                    @if(!empty($application['ielts_listening']))
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Listening:</span>
                                <span>{{ $application['ielts_listening'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Reading:</span>
                                <span>{{ $application['ielts_reading'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Writing:</span>
                                <span>{{ $application['ielts_writing'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Speaking:</span>
                                <span>{{ $application['ielts_speaking'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    @endif
                @endif
                
                @if(!empty($application['pte_overall']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">PTE Overall:</span>
                        <span class="font-medium text-purple-600">{{ $application['pte_overall'] }}</span>
                    </div>
                    @if(!empty($application['pte_listening']))
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Listening:</span>
                                <span>{{ $application['pte_listening'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Reading:</span>
                                <span>{{ $application['pte_reading'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Writing:</span>
                                <span>{{ $application['pte_writing'] ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Speaking:</span>
                                <span>{{ $application['pte_speaking'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    @endif
                @endif
                
                @if(empty($application['ielts_overall']) && empty($application['pte_overall']))
                    <div class="text-center py-4 text-gray-500">
                        <p>No English proficiency scores available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    @if($application['documents_count'] > 0)
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mt-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Documents ({{ $application['documents_count'] }})
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($application['documents'] as $document)
                    <div class="bg-white/50 rounded-lg p-4 border border-white/20">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 truncate">{{ $document['document_type'] ?? 'Unknown Document' }}</div>
                                <div class="text-xs text-gray-500">
                                    @if(isset($document['document_size']))
                                        {{ number_format($document['document_size'] / 1024, 2) }} KB
                                    @else
                                        Unknown size
                                    @endif
                                </div>
                                @if($document['mandatory'] ?? false)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">Required</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Optional</span>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3 flex space-x-2">
                            <button onclick="viewDocument('{{ $document['document'] }}')" 
                                    class="flex-1 inline-flex items-center justify-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded hover:bg-blue-200 transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View
                            </button>
                            <button onclick="downloadDocument('{{ $document['document'] }}', '{{ $document['document_type'] }}')" 
                                    class="flex-1 inline-flex items-center justify-center px-3 py-1 bg-green-100 text-green-800 text-xs font-medium rounded hover:bg-green-200 transition-colors">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Download
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Additional Information -->
    @if(!empty($application['remarks']) || !empty($application['any_refusal']) || !empty($application['travel_history']))
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mt-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Additional Information
            </h2>
            
            <div class="space-y-3">
                @if(!empty($application['travel_history']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Travel History:</span>
                        <span class="font-medium">{{ $application['travel_history'] }}</span>
                    </div>
                @endif
                @if(!empty($application['any_refusal']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Any Refusal:</span>
                        <span class="font-medium">{{ $application['any_refusal'] }}</span>
                    </div>
                @endif
                @if(!empty($application['remarks']))
                    <div>
                        <span class="text-gray-600">Remarks:</span>
                        <p class="mt-1 text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $application['remarks'] }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

<!-- Documents Modal -->
<div id="documentsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900" id="modalTitle">Application Documents</h3>
                <button onclick="closeDocumentsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="documentsContent" class="max-h-96 overflow-y-auto">
                <!-- Documents will be loaded here -->
            </div>
            
            <div class="flex justify-end mt-4 space-x-2">
                <button onclick="closeDocumentsModal()" 
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                    Close
                </button>
                <button id="downloadAllBtn" onclick="downloadAllDocuments()" 
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    Download All
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Include the same JavaScript from index.blade.php for document handling
let currentDocuments = [];
let currentApplicationId = null;

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

// Download all documents
function downloadAllDocuments() {
    if (currentDocuments && currentDocuments.length > 0) {
        // API documents - download each one
        currentDocuments.forEach((doc, index) => {
            setTimeout(() => {
                downloadDocument(doc.document, doc.document_type);
            }, index * 500); // Stagger downloads
        });
    }
}
</script>
@endsection
