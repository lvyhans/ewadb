@extends('layouts.app')

@section('page-title', 'Applications')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- API Status Warning -->
    @if(session('warning'))
        <div class="glass-effect rounded-2xl shadow-xl border border-orange-200/50 p-4 mb-6 bg-orange-50/30">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <span class="text-orange-800 font-medium">{{ session('warning') }}</span>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-3xl font-bold gradient-text mb-2">Applications</h1>
                    @if(isset($apiData) && $apiData)
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            API Connected
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Local Data
                        </span>
                    @endif
                </div>
                <p class="text-gray-600">
                    @if(isset($apiData) && $apiData)
                        Manage all visa applications via external API
                    @else
                        Manage all visa applications from local database
                    @endif
                </p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Entries Filter -->
                <div class="flex items-center space-x-2">
                    <label for="entries" class="text-sm font-medium text-gray-700">Show:</label>
                    <select id="entries" onchange="changeEntries()" 
                            class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="5" {{ (isset($entries) && $entries == 5) ? 'selected' : '' }}>5</option>
                        <option value="20" {{ (!isset($entries) || $entries == 20) ? 'selected' : '' }}>20</option>
                        <option value="100" {{ (isset($entries) && $entries == 100) ? 'selected' : '' }}>100</option>
                        <option value="250" {{ (isset($entries) && $entries == 250) ? 'selected' : '' }}>250</option>
                        <option value="500" {{ (isset($entries) && $entries == 500) ? 'selected' : '' }}>500</option>
                    </select>
                    <span class="text-sm text-gray-600">entries</span>
                </div>
                
                @if(isset($apiData) && $apiData)
                    <button onclick="refreshApplications()" 
                           class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-medium rounded-xl hover:bg-gray-600 transition-all duration-200"
                           title="Refresh from API">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </button>
                @endif
                <a href="{{ route('applications.create') }}" 
                   class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Application
                </a>
            </div>
        </div>
    </div>

    <!-- Applications Table -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 overflow-hidden">
        <!-- Table Info Bar -->
        <div class="bg-white/10 px-6 py-3 border-b border-white/20">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <div>
                    @if(isset($apiData) && $apiData)
                        Showing {{ (($apiData['page'] - 1) * $apiData['limit']) + 1 }} to {{ min($apiData['page'] * $apiData['limit'], $apiData['total_count']) }} of {{ $apiData['total_count'] }} applications
                    @else
                        Showing {{ $applications->firstItem() ?? 0 }} to {{ $applications->lastItem() ?? 0 }} of {{ $applications->total() }} applications
                    @endif
                </div>
                <div class="text-xs">
                    @if(isset($apiData) && $apiData)
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">API Data</span>
                    @else
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Local Data</span>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-white/30">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Application Details</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant Info</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Destination</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course & Intake</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documents</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status & Date</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @if(isset($apiData) && $apiData)
                        {{-- API Data --}}
                        @forelse($apiData['data'] as $application)
                        <tr class="hover:bg-white/10 transition-colors duration-200">
                            <!-- Application Details -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <div class="text-sm font-medium text-gray-900">ID: {{ $application['visa_form_id'] ?? 'N/A' }}</div>
                                    @if(!empty($application['ref_no']))
                                        <div class="text-xs text-blue-600">Ref: {{ $application['ref_no'] }}</div>
                                    @endif
                                    @if(!empty($application['source2']))
                                        <div class="text-xs text-gray-500">Source: {{ $application['source2'] }}</div>
                                    @endif
                                    @if($application['admissions_count'] > 0)
                                        <div class="text-xs text-purple-600">{{ $application['admissions_count'] }} Admission(s)</div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Applicant Info -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $application['name'] ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-600 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                        </svg>
                                        {{ $application['email'] ?? 'N/A' }}
                                    </div>
                                    <div class="text-sm text-gray-600 flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                        </svg>
                                        {{ $application['phone'] ?? 'N/A' }}
                                    </div>
                                    @if(!empty($application['resi_city']))
                                        <div class="text-xs text-gray-500">📍 {{ $application['resi_city'] }}</div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Destination -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <div class="text-sm font-medium text-gray-900">{{ $application['country'] ?? 'Not specified' }}</div>
                                    @if(!empty($application['city']))
                                        <div class="text-xs text-gray-600">🏙️ {{ $application['city'] }}</div>
                                    @endif
                                    @if(!empty($application['institute_name']))
                                        <div class="text-xs text-purple-600 font-medium">🏫 {{ Str::limit($application['institute_name'], 25) }}</div>
                                    @endif
                                    @if(!empty($application['prefer_city']))
                                        <div class="text-xs text-gray-500">Pref: {{ $application['prefer_city'] }}</div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Course & Intake -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-1">
                                    <div class="text-sm font-medium text-gray-900">{{ ucfirst($application['course'] ?? 'Not specified') }}</div>
                                    @if(!empty($application['last_qual']))
                                        <div class="text-xs text-blue-600">Level: {{ ucfirst($application['last_qual']) }}</div>
                                    @endif
                                    @if(!empty($application['intake']) || !empty($application['intakeyear']))
                                        <div class="text-xs text-green-600">
                                            📅 {{ $application['intake'] ?? '' }} {{ $application['intakeyear'] ?? '' }}
                                        </div>
                                    @endif
                                    @if(!empty($application['study_course']))
                                        <div class="text-xs text-gray-500">Study: {{ Str::limit($application['study_course'], 20) }}</div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Documents with Tooltip -->
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    @if($application['documents_count'] > 0)
                                        <div class="relative group">
                                            <button type="button" 
                                                    class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full hover:bg-blue-200 transition-colors"
                                                    onclick="showDocuments({{ json_encode($application['documents']) }}, '{{ $application['name'] }}')">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                                {{ $application['documents_count'] }} Docs
                                            </button>
                                            
                                            <!-- Tooltip -->
                                            <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-xs rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 z-10 whitespace-nowrap">
                                                Click to view all documents
                                                <div class="absolute top-full left-1/2 transform -translate-x-1/2 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded-full">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h4a1 1 0 010 2H6.414l2.293 2.293a1 1 0 01-1.414 1.414L5 6.414V8a1 1 0 01-2 0V4z" clip-rule="evenodd"></path>
                                                <path fill-rule="evenodd" d="M13 4a1 1 0 100 2h1.586l-2.293 2.293a1 1 0 001.414 1.414L16 7.414V9a1 1 0 102 0V4a1 1 0 00-1-1h-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            No Docs
                                        </span>
                                    @endif
                                    
                                    <!-- English Test Scores if available -->
                                    @if(!empty($application['ielts_overall']) || !empty($application['pte_overall']))
                                        <div class="text-xs">
                                            @if(!empty($application['ielts_overall']))
                                                <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">IELTS: {{ $application['ielts_overall'] }}</span>
                                            @endif
                                            @if(!empty($application['pte_overall']))
                                                <span class="px-2 py-1 bg-purple-100 text-purple-800 rounded text-xs">PTE: {{ $application['pte_overall'] }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Status & Date -->
                            <td class="px-6 py-4">
                                <div class="flex flex-col space-y-2">
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
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                        {{ ucfirst($status) }}
                                    </span>
                                    
                                    <div class="text-xs text-gray-600">
                                        <div>{{ \Carbon\Carbon::parse($application['date'])->format('M d, Y') }}</div>
                                        <div class="text-gray-400">{{ \Carbon\Carbon::parse($application['date'])->format('H:i') }}</div>
                                    </div>
                                    
                                    @if(!empty($application['visa_status']))
                                        <div class="text-xs text-blue-600">Visa: {{ $application['visa_status'] }}</div>
                                    @endif
                                </div>
                            </td>
                            
                            <!-- Actions -->
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center space-x-2">
                                    <button onclick="viewApplicationDetails({{ $application['visa_form_id'] }})" 
                                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-all duration-200">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Details
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
        
        <!-- Pagination -->
        @if(isset($apiData) && $apiData)
            @if($apiData['total_pages'] > 1)
            <div class="bg-white/20 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between sm:hidden">
                        @if($apiData['page'] > 1)
                            <a href="{{ request()->fullUrlWithQuery(['page' => $apiData['page'] - 1, 'entries' => $entries ?? 20]) }}" 
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        @endif
                        @if($apiData['page'] < $apiData['total_pages'])
                            <a href="{{ request()->fullUrlWithQuery(['page' => $apiData['page'] + 1, 'entries' => $entries ?? 20]) }}" 
                               class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        @endif
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium">{{ (($apiData['page'] - 1) * $apiData['limit']) + 1 }}</span>
                                to
                                <span class="font-medium">{{ min($apiData['page'] * $apiData['limit'], $apiData['total_count']) }}</span>
                                of
                                <span class="font-medium">{{ $apiData['total_count'] }}</span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                @if($apiData['page'] > 1)
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $apiData['page'] - 1, 'entries' => $entries ?? 20]) }}" 
                                       class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        Previous
                                    </a>
                                @endif
                                
                                @for($i = 1; $i <= $apiData['total_pages']; $i++)
                                    @if($i == $apiData['page'])
                                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-50 text-sm font-medium text-blue-600">
                                            {{ $i }}
                                        </span>
                                    @else
                                        <a href="{{ request()->fullUrlWithQuery(['page' => $i, 'entries' => $entries ?? 20]) }}" 
                                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                            {{ $i }}
                                        </a>
                                    @endif
                                @endfor
                                
                                @if($apiData['page'] < $apiData['total_pages'])
                                    <a href="{{ request()->fullUrlWithQuery(['page' => $apiData['page'] + 1, 'entries' => $entries ?? 20]) }}" 
                                       class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        Next
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
            <div class="bg-white/20 px-6 py-4">
                {{ $applications->appends(['entries' => $entries ?? 20])->links() }}
            </div>
            @endif
        @endif
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mt-6">
        <div class="glass-effect rounded-xl p-6 border border-white/20">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Total Applications</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @if(isset($apiData) && $apiData)
                            {{ $apiData['total_count'] }}
                        @else
                            {{ $applications->total() }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="glass-effect rounded-xl p-6 border border-white/20">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Pending</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @if(isset($apiData) && $apiData)
                            {{ collect($apiData['data'])->where('status', 'pending')->count() }}
                        @else
                            {{ $applications->where('status', 'pending')->count() }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="glass-effect rounded-xl p-6 border border-white/20">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Approved</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @if(isset($apiData) && $apiData)
                            {{ collect($apiData['data'])->where('status', 'approved')->count() }}
                        @else
                            {{ $applications->where('status', 'approved')->count() }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
        
        <div class="glass-effect rounded-xl p-6 border border-white/20">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">In Progress</p>
                    <p class="text-2xl font-bold text-gray-900">
                        @if(isset($apiData) && $apiData)
                            {{ collect($apiData['data'])->where('status', 'in_progress')->count() }}
                        @else
                            {{ $applications->where('status', 'in_progress')->count() }}
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

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
