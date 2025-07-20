@extends('layouts.app')

@section('page-title', 'Application Details')

@section('content')
<div class="mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold gradient-text mb-2">Application Details</h1>
                <p class="text-gray-600">{{ $application->application_number }}</p>
                @if($application->lead_ref_no)
                    <p class="text-sm text-blue-600">Related Lead: {{ $application->lead_ref_no }}</p>
                @endif
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('applications.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-medium rounded-xl hover:bg-gray-600 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Applications
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information -->
            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Personal Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Full Name</label>
                        <p class="text-gray-900 font-medium">{{ $application->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                        <p class="text-gray-900">{{ $application->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Phone</label>
                        <p class="text-gray-900">{{ $application->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Date of Birth</label>
                        <p class="text-gray-900">{{ $application->date_of_birth ? $application->date_of_birth->format('M d, Y') : 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Gender</label>
                        <p class="text-gray-900">{{ $application->gender ? ucfirst($application->gender) : 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Nationality</label>
                        <p class="text-gray-900">{{ $application->nationality ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Passport Number</label>
                        <p class="text-gray-900">{{ $application->passport_number ?: 'Not provided' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Passport Expiry</label>
                        <p class="text-gray-900">{{ $application->passport_expiry ? $application->passport_expiry->format('M d, Y') : 'Not provided' }}</p>
                    </div>
                </div>
            </div>

            <!-- Study Preferences -->
            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    Study Preferences
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Preferred Country</label>
                        <p class="text-gray-900">{{ $application->preferred_country ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Preferred City</label>
                        <p class="text-gray-900">{{ $application->preferred_city ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Preferred College</label>
                        <p class="text-gray-900">{{ $application->preferred_college ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Course Level</label>
                        <p class="text-gray-900">{{ $application->course_level ? ucfirst($application->course_level) : 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Field of Study</label>
                        <p class="text-gray-900">{{ $application->field_of_study ?: 'Not specified' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Intake</label>
                        <p class="text-gray-900">
                            @if($application->intake_month && $application->intake_year)
                                {{ ucfirst($application->intake_month) }} {{ $application->intake_year }}
                            @else
                                Not specified
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Course Options -->
            @if($application->courseOptions && $application->courseOptions->count() > 0)
            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    Course Options
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        {{ $application->courseOptions->count() }} Option{{ $application->courseOptions->count() > 1 ? 's' : '' }}
                    </span>
                </h2>
                <div class="space-y-4">
                    @foreach($application->courseOptions->sortBy('priority_order') as $courseOption)
                    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-xl p-4 {{ $courseOption->is_primary ? 'ring-2 ring-indigo-400' : '' }}">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-semibold text-gray-900 flex items-center">
                                @if($courseOption->is_primary)
                                    <svg class="w-5 h-5 text-indigo-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Primary Choice
                                @else
                                    Option {{ $courseOption->priority_order }}
                                @endif
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Country</label>
                                <p class="text-gray-900">{{ $courseOption->country }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">City/Province</label>
                                <p class="text-gray-900">{{ $courseOption->city ?: 'Not specified' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">College/Institution</label>
                                <p class="text-gray-900">{{ $courseOption->college ?: 'Not specified' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Course/Program</label>
                                <p class="text-gray-900">{{ $courseOption->course ?: 'Not specified' }}</p>
                            </div>
                            @if($courseOption->course_type)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Course Type</label>
                                <p class="text-gray-900">{{ $courseOption->course_type }}</p>
                            </div>
                            @endif
                            @if($courseOption->intake_year || $courseOption->intake_month)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Intake</label>
                                <p class="text-gray-900">
                                    @if($courseOption->intake_month && $courseOption->intake_year)
                                        {{ ucfirst($courseOption->intake_month) }} {{ $courseOption->intake_year }}
                                    @elseif($courseOption->intake_month)
                                        {{ ucfirst($courseOption->intake_month) }}
                                    @elseif($courseOption->intake_year)
                                        {{ $courseOption->intake_year }}
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                            @endif
                            @if($courseOption->fees)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Fees</label>
                                <p class="text-gray-900">{{ $courseOption->fees }}</p>
                            </div>
                            @endif
                            @if($courseOption->duration)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Duration</label>
                                <p class="text-gray-900">{{ $courseOption->duration }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Educational Qualifications -->
            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    </svg>
                    Educational Qualifications
                </h2>
                <div class="space-y-4">
                    @if($application->tenth_percentage || $application->tenth_year)
                    <div class="border-l-4 border-blue-500 pl-4">
                        <h3 class="font-medium text-gray-900">10th Grade</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            @if($application->tenth_percentage)
                            <div>
                                <span class="text-sm text-gray-600">Percentage:</span>
                                <span class="text-gray-900 font-medium">{{ $application->tenth_percentage }}%</span>
                            </div>
                            @endif
                            @if($application->tenth_year)
                            <div>
                                <span class="text-sm text-gray-600">Year:</span>
                                <span class="text-gray-900 font-medium">{{ $application->tenth_year }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($application->twelfth_percentage || $application->twelfth_year)
                    <div class="border-l-4 border-green-500 pl-4">
                        <h3 class="font-medium text-gray-900">12th Grade</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            @if($application->twelfth_percentage)
                            <div>
                                <span class="text-sm text-gray-600">Percentage:</span>
                                <span class="text-gray-900 font-medium">{{ $application->twelfth_percentage }}%</span>
                            </div>
                            @endif
                            @if($application->twelfth_year)
                            <div>
                                <span class="text-sm text-gray-600">Year:</span>
                                <span class="text-gray-900 font-medium">{{ $application->twelfth_year }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($application->bachelor_percentage || $application->bachelor_year)
                    <div class="border-l-4 border-purple-500 pl-4">
                        <h3 class="font-medium text-gray-900">Bachelor's Degree</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            @if($application->bachelor_percentage)
                            <div>
                                <span class="text-sm text-gray-600">Percentage:</span>
                                <span class="text-gray-900 font-medium">{{ $application->bachelor_percentage }}%</span>
                            </div>
                            @endif
                            @if($application->bachelor_year)
                            <div>
                                <span class="text-sm text-gray-600">Year:</span>
                                <span class="text-gray-900 font-medium">{{ $application->bachelor_year }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($application->master_percentage || $application->master_year)
                    <div class="border-l-4 border-red-500 pl-4">
                        <h3 class="font-medium text-gray-900">Master's Degree</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                            @if($application->master_percentage)
                            <div>
                                <span class="text-sm text-gray-600">Percentage:</span>
                                <span class="text-gray-900 font-medium">{{ $application->master_percentage }}%</span>
                            </div>
                            @endif
                            @if($application->master_year)
                            <div>
                                <span class="text-sm text-gray-600">Year:</span>
                                <span class="text-gray-900 font-medium">{{ $application->master_year }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- English Proficiency -->
            @if($application->english_proficiency)
            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"></path>
                    </svg>
                    English Proficiency
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Test Type</label>
                        <p class="text-gray-900 font-medium">{{ strtoupper($application->english_proficiency) }}</p>
                    </div>
                    @if($application->ielts_score)
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">IELTS Score</label>
                        <p class="text-gray-900 font-medium">{{ $application->ielts_score }}</p>
                    </div>
                    @endif
                    @if($application->pte_score)
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">PTE Score</label>
                        <p class="text-gray-900 font-medium">{{ $application->pte_score }}</p>
                    </div>
                    @endif
                    @if($application->toefl_score)
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">TOEFL Score</label>
                        <p class="text-gray-900 font-medium">{{ $application->toefl_score }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Background Information -->
            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Background Information
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Travel History</label>
                        <p class="text-gray-900">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $application->travel_history ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $application->travel_history ? 'Yes' : 'No' }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Visa Refusal History</label>
                        <p class="text-gray-900">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $application->visa_refusal_history ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $application->visa_refusal_history ? 'Yes' : 'No' }}
                            </span>
                        </p>
                    </div>
                    @if($application->refusal_details)
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-1">Refusal Details</label>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-red-900 text-sm">{{ $application->refusal_details }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Application Summary -->
            <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Application Summary
                </h2>
                <table class="w-full">
                    <tbody class="divide-y divide-white/20">
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Application Number</td>
                            <td class="py-3 text-sm text-gray-900 font-medium">{{ $application->application_number }}</td>
                        </tr>
                        @if($application->lead_ref_no)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Lead Reference</td>
                            <td class="py-3 text-sm text-blue-600 font-medium">{{ $application->lead_ref_no }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Status</td>
                            <td class="py-3">
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
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Created By</td>
                            <td class="py-3 text-sm text-gray-900">{{ $application->creator->name }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Assigned To</td>
                            <td class="py-3 text-sm text-gray-900">{{ $application->assignedUser->name ?? 'Unassigned' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Created Date</td>
                            <td class="py-3 text-sm text-gray-900">{{ $application->created_at->format('M d, Y H:i A') }}</td>
                        </tr>
                        @if($application->visa_counselor)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Visa Counselor</td>
                            <td class="py-3 text-sm text-gray-900">{{ $application->visa_counselor }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Employment History -->
    @if($application->employmentHistory->count() > 0)
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 2.5V6a2 2 0 00-2-2H10a2 2 0 00-2 2v.5m8 0V9a2 2 0 01-2 2H10a2 2 0 01-2-2v-.5m8 0h.5a2 2 0 012 2V16a2 2 0 01-2 2h-13a2 2 0 01-2-2V8.5a2 2 0 012-2H8"></path>
            </svg>
            Employment History
        </h2>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/20">
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Company</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Position</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Duration</th>
                        <th class="text-left py-3 text-sm font-medium text-gray-600">Location</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/20">
                    @foreach($application->employmentHistory as $employment)
                    <tr>
                        <td class="py-3 text-sm text-gray-900">{{ $employment->company_name }}</td>
                        <td class="py-3 text-sm text-gray-900">{{ $employment->position }}</td>
                        <td class="py-3 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($employment->start_date)->format('M Y') }} - 
                            {{ $employment->end_date ? \Carbon\Carbon::parse($employment->end_date)->format('M Y') : 'Present' }}
                        </td>
                        <td class="py-3 text-sm text-gray-900">{{ $employment->location }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    <!-- Documents -->
    @if($application->documents->count() > 0)
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Uploaded Documents
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($application->documents as $document)
            <div class="bg-white/30 rounded-lg p-4 border border-white/20">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900 mb-1">{{ $document->document_name }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ $document->original_filename }}</p>
                        <div class="flex items-center space-x-3 text-xs text-gray-500">
                            <span>{{ number_format($document->file_size / 1024, 1) }} KB</span>
                            <span>{{ strtoupper($document->mime_type) }}</span>
                            @if($document->is_mandatory)
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full">Mandatory</span>
                            @else
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Optional</span>
                            @endif
                        </div>
                    </div>
                    <div class="ml-4">
                        <a href="{{ $document->file_url }}" 
                           target="_blank"
                           class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-all duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            View
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Remarks -->
    @if($application->remarks)
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
            <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m10 0H7"></path>
            </svg>
            Remarks
        </h2>
        <div class="bg-white/30 rounded-lg p-4">
            <p class="text-gray-900">{{ $application->remarks }}</p>
        </div>
    </div>
    @endif
</div>
@endsection
