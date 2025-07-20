@extends('layouts.app')

@section('page-title', 'Application Journey')

@section('content')
<div class="mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <a href="{{ route('applications.index') }}" 
                       class="inline-flex items-center px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-all duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                        Back to Applications
                    </a>
                    <h1 class="text-3xl font-bold gradient-text">Application Journey</h1>
                </div>
                
                @if(isset($applicationInfo) && $applicationInfo)
                    <div class="text-gray-600">
                        <div class="flex items-center space-x-4">
                            <span><strong>Visa Form ID:</strong> {{ $applicationInfo['visa_form_id'] ?? $visaFormId }}</span>
                            <span><strong>Applicant:</strong> {{ $applicationInfo['name'] ?? 'N/A' }}</span>
                            @if(!empty($applicationInfo['application_number']))
                                <span><strong>App Number:</strong> {{ $applicationInfo['application_number'] }}</span>
                            @endif
                            @if(isset($admissionId))
                                <span><strong>Admission ID:</strong> {{ $admissionId }}</span>
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-gray-600">Visa Form ID: {{ $visaFormId }}</p>
                @endif
            </div>
        </div>
    </div>

    {{-- Debug Information --}}
    @if(config('app.debug'))
        <div class="glass-effect rounded-2xl shadow-xl border border-yellow-200/50 p-4 mb-6 bg-yellow-50">
            <h3 class="text-lg font-bold text-yellow-900 mb-2">Debug Information</h3>
            <div class="text-sm text-yellow-800 space-y-1">
                <div><strong>Journey Data Status:</strong> {{ isset($journeyData) ? ($journeyData['status'] ?? 'no_status') : 'not_set' }}</div>
                <div><strong>Journey Data Keys:</strong> {{ isset($journeyData) ? implode(', ', array_keys($journeyData)) : 'N/A' }}</div>
                <div><strong>Has Admission Data:</strong> {{ isset($journeyData['admission']) ? 'Yes' : 'No' }}</div>
                <div><strong>Has Journey Data:</strong> {{ isset($journeyData['journey']) ? 'Yes' : 'No' }}</div>
                <div><strong>Has Processed Journey:</strong> {{ isset($journeyData['processed_journey']) ? 'Yes' : 'No' }}</div>
                @if(isset($admissionId))
                    <div><strong>Admission ID:</strong> {{ $admissionId }}</div>
                @endif
            </div>
        </div>
    @endif

    @if(isset($journeyData) && $journeyData['status'] === 'success')
        @php
            $admission = $journeyData['admission'];
            $journey = $journeyData['journey'] ?? [];
        @endphp
        
        <!-- Admission Overview -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Admission Details</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Basic Info -->
                <div class="bg-white/50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Admission Info</h3>
                    <div class="space-y-1 text-sm">
                        <div><strong>ID:</strong> {{ $admission['admissions_id'] }}</div>
                        <div><strong>App Number:</strong> {{ $admission['application_number'] }}</div>
                        <div><strong>Created:</strong> {{ \Carbon\Carbon::parse($admission['created'])->format('M d, Y H:i') }}</div>
                    </div>
                </div>

                <!-- Destination -->
                <div class="bg-white/50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Destination</h3>
                    <div class="space-y-1 text-sm">
                        <div><strong>Country:</strong> {{ $admission['country'] }}</div>
                        <div><strong>City:</strong> {{ $admission['city'] }}</div>
                        <div><strong>Institute:</strong> {{ $admission['institute'] }}</div>
                    </div>
                </div>

                <!-- Intake -->
                <div class="bg-white/50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Intake</h3>
                    <div class="space-y-1 text-sm">
                        <div><strong>Year:</strong> {{ $admission['intake_year'] }}</div>
                        <div><strong>Month:</strong> {{ ucfirst($admission['intake_month']) }}</div>
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white/50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Status</h3>
                    <div class="space-y-1 text-sm">
                        <div class="flex items-center">
                            @if($admission['mark_complete'])
                                <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                <span class="text-green-700">Completed</span>
                            @else
                                <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                                <span class="text-yellow-700">In Progress</span>
                            @endif
                        </div>
                        @if($admission['mark_tt'])
                            <div class="text-blue-600">✓ TT Marked</div>
                        @endif
                        @if($admission['mark_interview'])
                            <div class="text-purple-600">✓ Interview Marked</div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Agent & Payment Info -->
            @if($admission['agent_name'] || $admission['payment_status'])
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    @if($admission['agent_name'])
                        <div class="bg-white/50 rounded-xl p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Agent Information</h3>
                            <div class="space-y-1 text-sm">
                                <div><strong>Agent:</strong> {{ $admission['agent_name'] }}</div>
                                @if($admission['admission_user_location'])
                                    <div><strong>Location:</strong> {{ $admission['admission_user_location'] }}</div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($admission['payment_status'])
                        <div class="bg-white/50 rounded-xl p-4">
                            <h3 class="font-semibold text-gray-900 mb-2">Payment Information</h3>
                            <div class="space-y-1 text-sm">
                                <div><strong>Status:</strong> {{ $admission['payment_status'] }}</div>
                                @if($admission['payment_date'])
                                    <div><strong>Date:</strong> {{ \Carbon\Carbon::parse($admission['payment_date'])->format('M d, Y') }}</div>
                                @endif
                                @if($admission['invoice_no'])
                                    <div><strong>Invoice:</strong> {{ $admission['invoice_no'] }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Journey Steps -->
        @if(!empty($journey))
            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Journey Steps</h2>
                
                @if(isset($journeyData['processed_journey']))
                    @php
                        $processedJourney = $journeyData['processed_journey'];
                    @endphp
                    
                    <!-- Progress Overview -->
                    <div class="bg-white/50 rounded-xl p-4 mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-medium text-gray-600">Overall Progress</span>
                            <span class="text-sm font-medium text-gray-900">{{ $processedJourney['overall_progress'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                 style="width: {{ $processedJourney['overall_progress'] }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>{{ $processedJourney['completed_steps'] }} of {{ $processedJourney['total_steps'] }} completed</span>
                            @if($processedJourney['current_step'])
                                <span>Current: {{ $processedJourney['current_step']['step'] }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Journey Timeline -->
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        
                        @foreach($processedJourney['steps'] as $step)
                            <div class="relative flex items-start mb-8 last:mb-0">
                                <!-- Step Icon -->
                                <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold z-10 
                                    @if($step['status'] === 'completed') bg-green-500
                                    @elseif($step['status'] === 'active') bg-blue-500 ring-4 ring-blue-200
                                    @else bg-gray-400
                                    @endif">
                                    @if($step['status'] === 'completed')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    @elseif($step['status'] === 'active')
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                        </svg>
                                    @else
                                        {{ $step['order'] }}
                                    @endif
                                </div>
                                
                                <!-- Step Content -->
                                <div class="ml-6 flex-1">
                                    <div class="bg-white/50 rounded-xl p-4 
                                        @if($step['status'] === 'completed') border-l-4 border-green-500
                                        @elseif($step['status'] === 'active') border-l-4 border-blue-500 shadow-md
                                        @else border-l-4 border-gray-300
                                        @endif">
                                        
                                        <div class="flex items-center justify-between mb-2">
                                            <h3 class="font-semibold text-gray-900">{{ $step['step'] }}</h3>
                                            <div class="flex items-center space-x-2">
                                                @if($step['progress_percentage'] > 0)
                                                    <div class="text-xs bg-gray-100 px-2 py-1 rounded">
                                                        {{ $step['progress_percentage'] }}%
                                                    </div>
                                                @endif
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($step['status'] === 'completed') bg-green-100 text-green-800
                                                    @elseif($step['status'] === 'active') bg-blue-100 text-blue-800
                                                    @else bg-gray-100 text-gray-800
                                                    @endif">
                                                    @if($step['status'] === 'completed') Completed
                                                    @elseif($step['status'] === 'active') In Progress
                                                    @else Pending
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <p class="text-sm text-gray-600">
                                            Step {{ $step['order'] }} of {{ $processedJourney['total_steps'] }}
                                            @if($step['status'] === 'active')
                                                - Currently in progress
                                            @elseif($step['status'] === 'completed')
                                                - Completed successfully
                                            @else
                                                - Waiting to start
                                            @endif
                                        </p>
                                        
                                        @if($step['progress_percentage'] > 0 && $step['progress_percentage'] < 100)
                                            <div class="mt-2">
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-blue-600 h-1.5 rounded-full transition-all duration-300" 
                                                         style="width: {{ $step['progress_percentage'] }}%"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Current Status Summary -->
                    @if($processedJourney['current_step'] || $processedJourney['next_step'])
                        <div class="mt-6 bg-blue-50 rounded-xl p-4">
                            <h4 class="font-semibold text-blue-900 mb-2">Current Status</h4>
                            @if($processedJourney['current_step'])
                                <p class="text-sm text-blue-700 mb-1">
                                    <strong>Current Step:</strong> {{ $processedJourney['current_step']['step'] }}
                                </p>
                            @endif
                            @if($processedJourney['next_step'])
                                <p class="text-sm text-blue-700">
                                    <strong>Next Step:</strong> {{ $processedJourney['next_step']['step'] }}
                                </p>
                            @endif
                        </div>
                    @endif
                @else
                    <!-- Fallback to simple journey display -->
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        
                        @if(isset($journey['journey_full']))
                            @foreach($journey['journey_full'] as $index => $step)
                                <div class="relative flex items-center mb-8 last:mb-0">
                                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-semibold z-10
                                        @if(in_array($step, $journey['journey_done'] ?? [])) bg-green-500
                                        @else bg-gray-400
                                        @endif">
                                        @if(in_array($step, $journey['journey_done'] ?? []))
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </div>
                                    <div class="ml-6 bg-white/50 rounded-xl p-4 flex-1
                                        @if(in_array($step, $journey['journey_done'] ?? [])) border-l-4 border-green-500
                                        @else border-l-4 border-gray-300
                                        @endif">
                                        <h3 class="font-semibold text-gray-900">{{ $step }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Step {{ $index + 1 }} of {{ count($journey['journey_full']) }}
                                            @if(in_array($step, $journey['journey_done'] ?? []))
                                                - Completed
                                            @else
                                                - Pending
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @foreach($journey as $index => $step)
                                <div class="relative flex items-center mb-8 last:mb-0">
                                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold z-10">
                                        {{ $index + 1 }}
                                    </div>
                                    <div class="ml-6 bg-white/50 rounded-xl p-4 flex-1">
                                        <h3 class="font-semibold text-gray-900">{{ $step }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">Step {{ $index + 1 }} of {{ count($journey) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <!-- Detailed Information Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Visa Information -->
            @if($admission['visa_info'] && ($admission['visa_info']['visa'] || $admission['visa_info']['visa_status']))
                <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Visa Information</h3>
                    <div class="space-y-3">
                        @if($admission['visa_info']['visa_status'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium">{{ $admission['visa_info']['visa_status'] }}</span>
                            </div>
                        @endif
                        @if($admission['visa_info']['visa_date'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($admission['visa_info']['visa_date'])->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($admission['visa_info']['visa_remarks'])
                            <div>
                                <span class="text-gray-600">Remarks:</span>
                                <p class="mt-1 text-sm">{{ $admission['visa_info']['visa_remarks'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Interview Information -->
            @if($admission['interview_info'] && $admission['interview_info']['interview'])
                <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Interview Information</h3>
                    <div class="space-y-3">
                        @if($admission['interview_info']['interview_status'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span class="font-medium">{{ $admission['interview_info']['interview_status'] ? 'Scheduled' : 'Not Scheduled' }}</span>
                            </div>
                        @endif
                        @if($admission['interview_info']['interview_place'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Place:</span>
                                <span class="font-medium">{{ $admission['interview_info']['interview_place'] }}</span>
                            </div>
                        @endif
                        @if($admission['interview_info']['interview_date'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($admission['interview_info']['interview_date'])->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($admission['interview_info']['interview_result'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Result:</span>
                                <span class="font-medium">{{ $admission['interview_info']['interview_result'] ? 'Passed' : 'Pending' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Biometric Information -->
            @if($admission['biometric_info'] && $admission['biometric_info']['biometric'])
                <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Biometric Information</h3>
                    <div class="space-y-3">
                        @if($admission['biometric_info']['biometric_place'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Place:</span>
                                <span class="font-medium">{{ $admission['biometric_info']['biometric_place'] }}</span>
                            </div>
                        @endif
                        @if($admission['biometric_info']['biometric_date'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($admission['biometric_info']['biometric_date'])->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($admission['biometric_info']['biometric_result'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Result:</span>
                                <span class="font-medium">{{ $admission['biometric_info']['biometric_result'] ? 'Completed' : 'Pending' }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- TT Information -->
            @if($admission['tt_info'] && ($admission['tt_info']['tt_receipt_date'] || $admission['tt_info']['tt_receipt_mail_date']))
                <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">TT Information</h3>
                    <div class="space-y-3">
                        @if($admission['tt_info']['tt_receipt_date'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Receipt Date:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($admission['tt_info']['tt_receipt_date'])->format('M d, Y') }}</span>
                            </div>
                        @endif
                        @if($admission['tt_info']['tt_receipt_mail_date'])
                            <div class="flex justify-between">
                                <span class="text-gray-600">Mail Date:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($admission['tt_info']['tt_receipt_mail_date'])->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

    @elseif(isset($journeyData) && isset($journeyData['admission']))
        <!-- Fallback: Show admission data even if journey processing failed -->
        @php
            $admission = $journeyData['admission'];
        @endphp
        
        <div class="glass-effect rounded-2xl shadow-xl border border-orange-200/50 p-6 mb-6">
            <div class="flex items-center mb-4">
                <svg class="w-6 h-6 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <h2 class="text-2xl font-bold text-gray-900">Journey Data Unavailable</h2>
            </div>
            <p class="text-gray-600 mb-6">Journey tracking is not available for this application, but admission details are shown below.</p>
            
            <!-- Admission Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Basic Info -->
                <div class="bg-white/50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Admission Info</h3>
                    <div class="space-y-1 text-sm">
                        <div><strong>ID:</strong> {{ $admission['admissions_id'] ?? 'N/A' }}</div>
                        <div><strong>App Number:</strong> {{ $admission['application_number'] ?? 'N/A' }}</div>
                        @if(isset($admission['created']))
                            <div><strong>Created:</strong> {{ \Carbon\Carbon::parse($admission['created'])->format('M d, Y H:i') }}</div>
                        @endif
                    </div>
                </div>

                <!-- Destination -->
                <div class="bg-white/50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Destination</h3>
                    <div class="space-y-1 text-sm">
                        <div><strong>Country:</strong> {{ $admission['country'] ?? 'N/A' }}</div>
                        <div><strong>City:</strong> {{ $admission['city'] ?? 'N/A' }}</div>
                        <div><strong>Institute:</strong> {{ $admission['institute'] ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Intake -->
                <div class="bg-white/50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Intake</h3>
                    <div class="space-y-1 text-sm">
                        <div><strong>Year:</strong> {{ $admission['intake_year'] ?? 'N/A' }}</div>
                        <div><strong>Month:</strong> {{ isset($admission['intake_month']) ? ucfirst($admission['intake_month']) : 'N/A' }}</div>
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white/50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Status</h3>
                    <div class="space-y-1 text-sm">
                        <div class="flex items-center">
                            <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></span>
                            <span class="text-yellow-700">Journey Unavailable</span>
                        </div>
                        @if(isset($admission['mark_complete']) && $admission['mark_complete'])
                            <div class="text-green-600">✓ Application Completed</div>
                        @endif
                        @if(isset($admission['mark_tt']) && $admission['mark_tt'])
                            <div class="text-blue-600">✓ TT Marked</div>
                        @endif
                        @if(isset($admission['mark_interview']) && $admission['mark_interview'])
                            <div class="text-purple-600">✓ Interview Marked</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Message about journey data -->
        <div class="glass-effect rounded-2xl shadow-xl border border-blue-200/50 p-6 mb-6 bg-blue-50">
            <h3 class="text-lg font-bold text-blue-900 mb-2">About Journey Tracking</h3>
            <p class="text-blue-700 text-sm">
                Journey tracking shows the step-by-step progress of your application through various stages like offer application, 
                document submission, visa lodgment, etc. Currently, journey data is not available for this application, 
                but the admission details above show the current status.
            </p>
        </div>

    @else
        <!-- Error State -->
        <div class="glass-effect rounded-2xl shadow-xl border border-red-200/50 p-12 text-center">
            <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Journey Not Available</h3>
            <p class="text-gray-500">Unable to load the application journey at this time.</p>
        </div>
    @endif
</div>

<script>
// Add any JavaScript for interactive features here
document.addEventListener('DOMContentLoaded', function() {
    // You can add journey step animations or interactions here
    console.log('Application Journey page loaded');
});
</script>
@endsection
