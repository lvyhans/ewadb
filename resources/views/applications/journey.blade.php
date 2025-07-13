@extends('layouts.app')

@section('page-title', 'Application Journey')

@section('content')
<div class="max-w-7xl mx-auto">
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
                
                <div class="relative">
                    <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                    
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
                </div>
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
