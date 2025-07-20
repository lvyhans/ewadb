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
                        Complete Information
                    </span>
                </div>
                <p class="text-gray-600 mt-2">
                    Application ID: {{ $visaFormId }}
                    @if(!empty($application['ref_no']))
                        | Reference: {{ $application['ref_no'] }}
                    @endif
                </p>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $details['admissions_count'] }} Admissions
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $details['documents_count'] }} Documents
                    </span>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('applications.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-medium rounded-xl hover:bg-gray-600 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Applications
                </a>
                
                @if($details['documents_count'] > 0)
                    <button onclick="downloadAllApiDocuments()" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Download All Documents
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Application Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
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
                    <span class="text-gray-600">Name:</span>
                    <span class="font-medium">{{ $application['name'] ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Email:</span>
                    <span class="font-medium text-sm">{{ $application['email'] ?? 'N/A' }}</span>
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
            </div>
        </div>

        <!-- Application Status -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Status & Summary
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
                    <span class="font-medium">{{ $application['date'] ? \Carbon\Carbon::parse($application['date'])->format('M d, Y') : 'N/A' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Admissions:</span>
                    <span class="font-medium text-green-600">{{ $details['admissions_count'] }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Documents:</span>
                    <span class="font-medium text-purple-600">{{ $details['documents_count'] }}</span>
                </div>
            </div>
        </div>

        <!-- Study Preferences -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Primary Preferences
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
                        <span class="font-medium text-sm">{{ $application['institute_name'] }}</span>
                    </div>
                @endif
                @if(!empty($application['course']))
                    <div class="flex justify-between">
                        <span class="text-gray-600">Course:</span>
                        <span class="font-medium">{{ ucfirst($application['course']) }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Admissions Section -->
    @if(!empty($details['admissions']) && count($details['admissions']) > 0)
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Admissions ({{ $details['admissions_count'] }})
            </h2>
            
            <div class="grid grid-cols-1 gap-6">
                @foreach($details['admissions'] as $admission)
                    <div class="bg-white/50 rounded-xl p-5 border border-white/20 hover:bg-white/70 transition-all duration-200">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    ID: {{ $admission['admissions_id'] }}
                                </span>
                                <span class="text-xs text-gray-500">{{ $admission['created'] ? \Carbon\Carbon::parse($admission['created'])->format('M d, Y') : 'N/A' }}</span>
                            </div>
                            @if(isset($journeyData[$admission['admissions_id']]))
                                <button onclick="toggleJourney({{ $admission['admissions_id'] }})" 
                                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs font-medium rounded-lg hover:bg-blue-700 transition-all duration-200">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    View Journey
                                </button>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-gray-200 text-gray-500 text-xs font-medium rounded-lg cursor-not-allowed" 
                                      title="Journey data not available for this admission">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Journey N/A
                                </span>
                            @endif
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 text-sm">Country:</span>
                                    <span class="font-medium text-sm">{{ $admission['country'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 text-sm">City:</span>
                                    <span class="font-medium text-sm">{{ $admission['city'] ?? 'N/A' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600 text-sm">Institute:</span>
                                    <span class="font-medium text-sm">{{ $admission['institute'] ?? 'N/A' }}</span>
                                </div>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600 text-sm">Intake:</span>
                                    <span class="font-medium text-sm">{{ ($admission['intake_month'] ?? '') }} {{ ($admission['intake_year'] ?? '') }}</span>
                                </div>
                                @if(($admission['branch'] ?? false) && !empty($admission['branch']))
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 text-sm">Branch:</span>
                                        <span class="font-medium text-sm">{{ $admission['branch'] }}</span>
                                    </div>
                                @endif
                                @if(isset($admission['course']) && $admission['course'])
                                    <div class="flex justify-between">
                                        <span class="text-gray-600 text-sm">Course:</span>
                                        <span class="font-medium text-sm">{{ $admission['course'] }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Journey Section -->
                        @if(isset($journeyData[$admission['admissions_id']]))
                            <div id="journey-{{ $admission['admissions_id'] }}" class="journey-section hidden border-t pt-4 mt-4">
                                @php
                                    $journey = $journeyData[$admission['admissions_id']];
                                    $journeyInfo = $journey['journey'] ?? [];
                                    $journeyFull = $journeyInfo['journey_full'] ?? [];
                                    $journeyDone = $journeyInfo['journey_done'] ?? [];
                                    $admissionInfo = $journey['admission'] ?? [];
                                @endphp
                                
                                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                    Application Journey
                                </h4>
                                
                                @if(!empty($journeyFull))
                                    <!-- Progress Overview -->
                                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                                        @php
                                            $totalSteps = count($journeyFull);
                                            $completedSteps = count($journeyDone);
                                            $progressPercentage = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
                                        @endphp
                                        <div class="flex items-center justify-between mb-2">
                                            <span class="text-sm font-medium text-gray-700">Overall Progress</span>
                                            <span class="text-sm font-medium text-gray-900">{{ $progressPercentage }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: {{ $progressPercentage }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                                            <span>{{ $completedSteps }} of {{ $totalSteps }} completed</span>
                                            <span>{{ $totalSteps - $completedSteps }} remaining</span>
                                        </div>
                                    </div>

                                    <!-- Journey Steps -->
                                    <div class="relative">
                                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                                        
                                        @foreach($journeyFull as $index => $step)
                                            @php
                                                $isCompleted = in_array($step, $journeyDone);
                                                $isActive = !$isCompleted && ($index == 0 || in_array($journeyFull[$index - 1], $journeyDone));
                                                $stepNumber = $index + 1;
                                            @endphp
                                            
                                            <div class="relative flex items-start mb-8 last:mb-0">
                                                <!-- Step Icon -->
                                                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold z-10 
                                                    @if($isCompleted) bg-green-500
                                                    @elseif($isActive) bg-blue-500 ring-4 ring-blue-200
                                                    @else bg-gray-400
                                                    @endif">
                                                    @if($isCompleted)
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @elseif($isActive)
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @else
                                                        {{ $stepNumber }}
                                                    @endif
                                                </div>
                                                
                                                <!-- Step Content -->
                                                <div class="ml-6 flex-1">
                                                    <div class="bg-white rounded-lg p-4 border-l-4 shadow-sm
                                                        @if($isCompleted) border-green-500 bg-green-50
                                                        @elseif($isActive) border-blue-500 bg-blue-50
                                                        @else border-gray-300 bg-gray-50
                                                        @endif">
                                                        
                                                        <div class="flex items-center justify-between mb-2">
                                                            <h5 class="font-semibold text-gray-900">{{ $step }}</h5>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                @if($isCompleted) bg-green-100 text-green-800
                                                                @elseif($isActive) bg-blue-100 text-blue-800
                                                                @else bg-gray-100 text-gray-800
                                                                @endif">
                                                                @if($isCompleted) Completed
                                                                @elseif($isActive) In Progress
                                                                @else Pending
                                                                @endif
                                                            </span>
                                                        </div>
                                                        
                                                        <p class="text-sm text-gray-600 mb-2">
                                                            Step {{ $stepNumber }} of {{ $totalSteps }}
                                                            @if($isActive)
                                                                - Currently in progress
                                                            @elseif($isCompleted)
                                                                - Completed successfully
                                                            @else
                                                                - Waiting to start
                                                            @endif
                                                        </p>

                                                        @if($isCompleted && isset($admissionInfo))
                                                            @php
                                                                $stepDetails = '';
                                                                switch($step) {
                                                                    case 'OFFER APPLIED':
                                                                        if($admissionInfo['mark_complete']) $stepDetails = 'Application marked as complete';
                                                                        break;
                                                                    case 'OFFER UPLOAD':
                                                                        if(isset($admissionInfo['offer_letter']['created'])) 
                                                                            $stepDetails = 'Offer letter uploaded on ' . \Carbon\Carbon::parse($admissionInfo['offer_letter']['created'])->format('M d, Y');
                                                                        break;
                                                                    case 'TT RECEIPT':
                                                                        if(isset($admissionInfo['tt_info']['tt_receipt_date'])) 
                                                                            $stepDetails = 'TT receipt on ' . \Carbon\Carbon::parse($admissionInfo['tt_info']['tt_receipt_date'])->format('M d, Y');
                                                                        break;
                                                                    case 'TT MAIL':
                                                                        if(isset($admissionInfo['tt_info']['tt_receipt_mail_date'])) 
                                                                            $stepDetails = 'TT mail sent on ' . \Carbon\Carbon::parse($admissionInfo['tt_info']['tt_receipt_mail_date'])->format('M d, Y');
                                                                        break;
                                                                    case 'INSTITUTE PAYMENT':
                                                                        if(isset($admissionInfo['institute_payment']['date'])) 
                                                                            $stepDetails = 'Payment processed on ' . \Carbon\Carbon::parse($admissionInfo['institute_payment']['date'])->format('M d, Y');
                                                                        break;
                                                                    case 'VISA STATUS':
                                                                        if(!empty($admissionInfo['visa_info']['visa_status'])) 
                                                                            $stepDetails = 'Status: ' . $admissionInfo['visa_info']['visa_status'];
                                                                        break;
                                                                }
                                                            @endphp
                                                            @if($stepDetails)
                                                                <p class="text-xs text-gray-500 mt-1">{{ $stepDetails }}</p>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <!-- Current Status Summary -->
                                    @if($completedSteps < $totalSteps)
                                        @php
                                            $nextStep = null;
                                            foreach($journeyFull as $index => $step) {
                                                if(!in_array($step, $journeyDone)) {
                                                    $nextStep = $step;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        @if($nextStep)
                                            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                                                <h5 class="font-semibold text-blue-900 mb-1">Next Step</h5>
                                                <p class="text-sm text-blue-700">{{ $nextStep }}</p>
                                            </div>
                                        @endif
                                    @else
                                        <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                                            <h5 class="font-semibold text-green-900 mb-1">Journey Complete</h5>
                                            <p class="text-sm text-green-700">All journey steps have been completed successfully!</p>
                                        </div>
                                    @endif
                                @else
                                    <p class="text-gray-500 text-sm">No journey data available for this admission.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Documents Section -->
    @if(!empty($details['documents']) && count($details['documents']) > 0)
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Documents ({{ $details['documents_count'] }})
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($details['documents'] as $document)
                    <div class="bg-white/50 rounded-xl p-5 border border-white/20 hover:bg-white/70 transition-all duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-gray-900 mb-2">{{ $document['document_type'] }}</div>
                                <div class="mb-3">
                                    @if($document['mandatory'])
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            Required
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">Optional</span>
                                    @endif
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="viewApiDocument('{{ $document['document'] }}')" 
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-100 text-blue-800 text-xs font-medium rounded-lg hover:bg-blue-200 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </button>
                                    <button onclick="downloadApiDocument('{{ $document['document'] }}', '{{ $document['document_type'] }}')" 
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-green-100 text-green-800 text-xs font-medium rounded-lg hover:bg-green-200 transition-colors">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Download
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if(empty($details['admissions']) && empty($details['documents']))
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Additional Details</h3>
            <p class="text-gray-500">This application doesn't have any admissions or documents yet.</p>
        </div>
    @endif
</div>

<script>
// Global variables for documents
let allDocuments = @json($details['documents'] ?? []);

// View API document
function viewApiDocument(documentUrl) {
    // Handle relative URLs by checking if it starts with "uploads/"
    if (documentUrl.startsWith('uploads/')) {
        // Construct full URL - you may need to adjust this based on your API's base URL
        documentUrl = 'https://tarundemo.innerxcrm.com/' + documentUrl;
    }
    window.open(documentUrl, '_blank');
}

// Download API document
function downloadApiDocument(documentUrl, documentName) {
    // Handle relative URLs by checking if it starts with "uploads/"
    if (documentUrl.startsWith('uploads/')) {
        // Construct full URL - you may need to adjust this based on your API's base URL
        documentUrl = 'https://tarundemo.innerxcrm.com/' + documentUrl;
    }
    
    const link = document.createElement('a');
    link.href = documentUrl;
    link.download = documentName || 'document';
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Download all API documents
function downloadAllApiDocuments() {
    if (allDocuments && allDocuments.length > 0) {
        allDocuments.forEach((doc, index) => {
            setTimeout(() => {
                downloadApiDocument(doc.document, doc.document_type);
            }, index * 500); // Stagger downloads
        });
    } else {
        alert('No documents available for download.');
    }
}

// Refresh page function
function refreshApplicationDetails() {
    window.location.reload();
}

// Toggle journey visibility
function toggleJourney(admissionId) {
    const journeySection = document.getElementById('journey-' + admissionId);
    const button = event.target.closest('button');
    
    if (journeySection.classList.contains('hidden')) {
        journeySection.classList.remove('hidden');
        button.innerHTML = `
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
            Hide Journey
        `;
    } else {
        journeySection.classList.add('hidden');
        button.innerHTML = `
            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            View Journey
        `;
    }
}
</script>
@endsection
