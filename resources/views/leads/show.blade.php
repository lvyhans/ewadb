@extends('layouts.app')

@section('page-title', 'Lead Details - ' . $lead->name)

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Lead Header -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600/10 to-purple-600/10 p-6 border-b border-white/20">
            <div class="flex justify-between items-start">
                <div>
                    <div class="flex items-center space-x-4 mb-2">
                        <h1 class="text-3xl font-bold text-gray-900">{{ $lead->name }}</h1>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            @switch($lead->status)
                                @case('new') bg-blue-100 text-blue-800 @break
                                @case('contacted') bg-yellow-100 text-yellow-800 @break
                                @case('qualified') bg-purple-100 text-purple-800 @break
                                @case('converted') bg-green-100 text-green-800 @break
                                @case('rejected') bg-red-100 text-red-800 @break
                                @default bg-gray-100 text-gray-800
                            @endswitch
                        ">
                            {{ ucfirst($lead->status) }}
                        </span>
                    </div>
                    <p class="text-gray-600">Reference: <span class="font-semibold">{{ $lead->ref_no }}</span></p>
                    <p class="text-gray-600">Created: {{ $lead->created_at->format('M d, Y H:i A') }}</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('leads.edit', $lead->id) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 text-white font-medium rounded-xl hover:bg-yellow-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Lead
                    </a>
                    <a href="{{ route('leads.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-xl hover:bg-gray-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Leads
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <!-- Personal Information -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Personal Information
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-white/20">
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600 w-1/3">Reference Number</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->ref_no }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Full Name</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->name }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Date of Birth</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->dob ? $lead->dob->format('M d, Y') : 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Father's Name</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->father_name ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Mobile Number</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->phone }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Alternative Phone</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->alt_phone ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Email Address</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->email ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Residential City</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->city ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Address</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->address ?: 'Not provided' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Preferred Destination -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                </svg>
                Preferred Destination & Course
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-white/20">
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600 w-1/3">Preferred Country</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->preferred_country ?: 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Preferred Province/City</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->preferred_city ?: 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Preferred College</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->preferred_college ?: 'Not specified' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Preferred Course</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->preferred_course ?: 'Not specified' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Background Information -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Background Information
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-white/20">
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600 w-1/3">Travel History</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->travel_history ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Any Visa Refusal</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->any_refusal ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Spouse Name</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->spouse_name ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Gap in Studies</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->any_gap ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Previous Visa Application</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->previous_visa_application ?: 'Not provided' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- English Proficiency -->
        @if($lead->score_type)
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m10 0H7"></path>
                </svg>
                English Proficiency Test ({{ strtoupper($lead->score_type) }})
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-white/20">
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600 w-1/3">Overall Score</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->{$lead->score_type . '_overall'} ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Listening</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->{$lead->score_type . '_listening'} ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Reading</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->{$lead->score_type . '_reading'} ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Writing</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->{$lead->score_type . '_writing'} ?: 'Not provided' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Speaking</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->{$lead->score_type . '_speaking'} ?: 'Not provided' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Educational Qualifications -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                </svg>
                Educational Qualifications
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-white/20">
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600 w-1/3">Last Qualification</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->last_qualification ?: 'Not provided' }}</td>
                        </tr>
                        @if($lead->tenth_year)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">10th Grade</td>
                            <td class="py-3 text-sm text-gray-900">
                                {{ $lead->tenth_year }} | {{ $lead->tenth_percentage }}% | {{ $lead->tenth_major }}
                            </td>
                        </tr>
                        @endif
                        @if($lead->twelfth_year)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">12th Grade</td>
                            <td class="py-3 text-sm text-gray-900">
                                {{ $lead->twelfth_year }} | {{ $lead->twelfth_percentage }}% | {{ $lead->twelfth_major }}
                            </td>
                        </tr>
                        @endif
                        @if($lead->diploma_year)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Diploma</td>
                            <td class="py-3 text-sm text-gray-900">
                                {{ $lead->diploma_year }} | {{ $lead->diploma_percentage }}% | {{ $lead->diploma_major }}
                            </td>
                        </tr>
                        @endif
                        @if($lead->graduation_year)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Graduation</td>
                            <td class="py-3 text-sm text-gray-900">
                                {{ $lead->graduation_year }} | {{ $lead->graduation_percentage }}% | {{ $lead->graduation_major }}
                            </td>
                        </tr>
                        @endif
                        @if($lead->post_graduation_year)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Post Graduation</td>
                            <td class="py-3 text-sm text-gray-900">
                                {{ $lead->post_graduation_year }} | {{ $lead->post_graduation_percentage }}% | {{ $lead->post_graduation_major }}
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Source & Management Information -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                </svg>
                Source & Management Information
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <tbody class="divide-y divide-white/20">
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600 w-1/3">Lead Source</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->source ?: 'Not provided' }}</td>
                        </tr>
                        @if($lead->reference_name)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Reference Name</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->reference_name }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Status</td>
                            <td class="py-3 text-sm">
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full 
                                    @switch($lead->status)
                                        @case('new') bg-blue-100 text-blue-800 @break
                                        @case('contacted') bg-yellow-100 text-yellow-800 @break
                                        @case('qualified') bg-purple-100 text-purple-800 @break
                                        @case('converted') bg-green-100 text-green-800 @break
                                        @case('rejected') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    {{ ucfirst($lead->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Created By</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->creator->name }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Assigned To</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->assignedUser->name ?? 'Unassigned' }}</td>
                        </tr>
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Created Date</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->created_at->format('M d, Y H:i A') }}</td>
                        </tr>
                        @if($lead->visa_counselor)
                        <tr>
                            <td class="py-3 text-sm font-medium text-gray-600">Visa Counselor</td>
                            <td class="py-3 text-sm text-gray-900">{{ $lead->visa_counselor }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Remarks -->
        @if($lead->remarks)
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m10 0H7"></path>
                </svg>
                Remarks
            </h2>
            <div class="bg-white/30 rounded-lg p-4">
                <p class="text-gray-900">{{ $lead->remarks }}</p>
            </div>
        </div>
        @endif
    </div>

    <!-- Employment History -->
    @if($lead->employmentHistory->count() > 0)
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
                    @foreach($lead->employmentHistory as $employment)
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

    <!-- Follow-ups -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mt-6">
        <div class="flex items-center gap-4 mb-4">
            <button onclick="addFollowup()" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Add Follow-up
            </button>
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-8 0h8m-8 0H3a2 2 0 00-2 2v7a2 2 0 002 2h18a2 2 0 002-2V9a2 2 0 00-2-2h-3"></path>
                </svg>
                Follow-ups
            </h2>
        </div>
        
        @if($lead->followups->count() > 0)
            <div class="space-y-4">
                @foreach($lead->followups->sortByDesc('created_at') as $followup)
                <div class="border border-white/20 rounded-xl p-4 bg-white/20">
                    <div class="flex items-start gap-4 mb-2">
                        <div class="flex-shrink-0">
                            <div class="flex space-x-2">
                                @if($followup->status === 'pending')
                                <button class="inline-flex items-center px-2 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors" 
                                        onclick="completeFollowup({{ $followup->id }})">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Complete
                                </button>
                                @endif
                                <button class="inline-flex items-center px-2 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors" 
                                        onclick="editFollowup({{ $followup->id }})">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">{{ $followup->subject }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ ucfirst($followup->type) }} • 
                                {{ $followup->scheduled_at->format('M d, Y H:i A') }} • 
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                    @switch($followup->status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('completed') bg-green-100 text-green-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                        @default bg-gray-100 text-gray-800
                                    @endswitch
                                ">
                                    {{ ucfirst($followup->status) }}
                                </span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <p class="text-sm text-gray-600">{{ $followup->user->name }}</p>
                        </div>
                    </div>
                    <p class="text-gray-700">{{ $followup->description }}</p>
                    @if($followup->notes)
                        <div class="mt-2 p-2 bg-white/30 rounded-lg">
                            <p class="text-sm text-gray-600"><strong>Notes:</strong> {{ $followup->notes }}</p>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a4 4 0 118 0v4m-8 0h8m-8 0H3a2 2 0 00-2 2v7a2 2 0 002 2h18a2 2 0 002-2V9a2 2 0 00-2-2h-3"></path>
                </svg>
                <p class="text-gray-500 mb-4">No follow-ups recorded yet</p>
                <button onclick="addFollowup()" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add First Follow-up
                </button>
            </div>
        @endif
    </div>

    <!-- Additional Information -->
    @if($lead->notes || $lead->additional_info)
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mt-6">
        <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10m0 0V6a2 2 0 00-2-2H9a2 2 0 00-2 2v2m10 0v10a2 2 0 01-2 2H9a2 2 0 01-2-2V8m10 0H7"></path>
            </svg>
            Additional Information
        </h2>
        @if($lead->notes)
            <div class="mb-4">
                <label class="text-sm font-medium text-gray-600">Notes</label>
                <div class="mt-1 p-3 bg-white/30 rounded-lg">
                    <p class="text-gray-900">{{ $lead->notes }}</p>
                </div>
            </div>
        @endif
        @if($lead->additional_info)
            <div>
                <label class="text-sm font-medium text-gray-600">Additional Information</label>
                <div class="mt-1 p-3 bg-white/30 rounded-lg">
                    <p class="text-gray-900">{{ $lead->additional_info }}</p>
                </div>
            </div>
        @endif
    </div>
    @endif
</div>

<!-- Add Follow-up Modal -->
<div id="followupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4" style="z-index: 9999 !important;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Add Follow-up</h3>
                <button onclick="closeFollowupModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="followupForm" class="p-6">
            @csrf
            <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Follow-up Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all" required>
                        <option value="">Select Type</option>
                        <option value="call">Phone Call</option>
                        <option value="email">Email</option>
                        <option value="meeting">Meeting</option>
                        <option value="visit">Site Visit</option>
                        <option value="document">Document Review</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all" required>
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6 space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all" placeholder="Enter follow-up subject" required>
            </div>
            
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Scheduled Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="scheduled_date" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all" required>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Scheduled Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="scheduled_time" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all" required>
                </div>
            </div>
            
            <div class="mt-6 space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" rows="4" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all resize-none" placeholder="Enter follow-up description..." required></textarea>
            </div>
            
            <div class="mt-6 space-y-2">
                <label class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all resize-none" placeholder="Additional notes..."></textarea>
            </div>
            
            <div class="mt-8 flex items-center justify-end space-x-4">
                <button type="button" onclick="closeFollowupModal()" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all font-medium shadow-lg">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Follow-up
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Follow-up Modal -->
<div id="editFollowupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4" style="z-index: 9999 !important;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Edit Follow-up</h3>
                <button onclick="closeEditFollowupModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <form id="editFollowupForm" class="p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="followup_id" id="edit_followup_id">
            
            <!-- Same form fields as add modal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Follow-up Type <span class="text-red-500">*</span>
                    </label>
                    <select name="type" id="edit_type" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all" required>
                        <option value="">Select Type</option>
                        <option value="call">Phone Call</option>
                        <option value="email">Email</option>
                        <option value="meeting">Meeting</option>
                        <option value="visit">Site Visit</option>
                        <option value="document">Document Review</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select name="priority" id="edit_priority" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all" required>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6 space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Subject <span class="text-red-500">*</span>
                </label>
                <input type="text" name="subject" id="edit_subject" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all" placeholder="Enter follow-up subject" required>
            </div>
            
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Scheduled Date <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="scheduled_date" id="edit_scheduled_date" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all" required>
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">
                        Scheduled Time <span class="text-red-500">*</span>
                    </label>
                    <input type="time" name="scheduled_time" id="edit_scheduled_time" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all" required>
                </div>
            </div>
            
            <div class="mt-6 space-y-2">
                <label class="block text-sm font-medium text-gray-700">
                    Description <span class="text-red-500">*</span>
                </label>
                <textarea name="description" id="edit_description" rows="4" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all resize-none" placeholder="Enter follow-up description..." required></textarea>
            </div>
            
            <div class="mt-6 space-y-2">
                <label class="block text-sm font-medium text-gray-700">Notes (Optional)</label>
                <textarea name="notes" id="edit_notes" rows="3" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all resize-none" placeholder="Additional notes..."></textarea>
            </div>
            
            <div class="mt-8 flex items-center justify-end space-x-4">
                <button type="button" onclick="closeEditFollowupModal()" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors font-medium">
                    Cancel
                </button>
                <button type="submit" class="px-8 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white rounded-xl hover:from-yellow-700 hover:to-orange-700 transition-all font-medium shadow-lg">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Update Follow-up
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="alertContainer" class="fixed top-4 right-4" style="z-index: 9998 !important;"></div>

<script>
console.log('Lead show page script loading...');

// Modal functions
function addFollowup() {
    console.log('addFollowup called');
    document.getElementById('followupModal').classList.remove('hidden');
    // Set today as default date
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="scheduled_date"]').value = today;
    // Set current time + 1 hour as default
    const now = new Date();
    now.setHours(now.getHours() + 1);
    const timeString = now.toTimeString().slice(0, 5);
    document.querySelector('input[name="scheduled_time"]').value = timeString;
}

function closeFollowupModal() {
    document.getElementById('followupModal').classList.add('hidden');
    document.getElementById('followupForm').reset();
}

function editFollowup(followupId) {
    console.log('editFollowup called with followupId:', followupId);
    // First, fetch the followup data
    fetch(`/api/followups/${followupId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const followup = data.followup;
                
                // Populate the edit form
                document.getElementById('edit_followup_id').value = followupId;
                document.getElementById('edit_type').value = followup.type;
                document.getElementById('edit_priority').value = followup.priority;
                document.getElementById('edit_subject').value = followup.subject;
                document.getElementById('edit_scheduled_date').value = followup.scheduled_date;
                document.getElementById('edit_scheduled_time').value = followup.scheduled_time;
                document.getElementById('edit_description').value = followup.description;
                document.getElementById('edit_notes').value = followup.notes || '';
                
                // Show the modal
                document.getElementById('editFollowupModal').classList.remove('hidden');
            } else {
                showAlert('Error loading follow-up data', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error loading follow-up data', 'error');
        });
}

function closeEditFollowupModal() {
    document.getElementById('editFollowupModal').classList.add('hidden');
    document.getElementById('editFollowupForm').reset();
}

function completeFollowup(followupId) {
    console.log('completeFollowup called with followupId:', followupId);
    if (confirm('Mark this follow-up as completed?')) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const headers = {
            'Content-Type': 'application/json'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
        }
        
        fetch(`/api/followups/${followupId}/complete`, {
            method: 'POST',
            headers: headers
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert('Follow-up marked as completed!', 'success');
                setTimeout(() => location.reload(), 1500);
            } else {
                showAlert('Error completing follow-up', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Error completing follow-up', 'error');
        });
    }
}

// Make functions globally available
window.addFollowup = addFollowup;
window.editFollowup = editFollowup;
window.completeFollowup = completeFollowup;
window.closeFollowupModal = closeFollowupModal;
window.closeEditFollowupModal = closeEditFollowupModal;

console.log('Follow-up functions defined and made globally available');

// Form submission handlers
document.addEventListener('DOMContentLoaded', function() {
    const followupForm = document.getElementById('followupForm');
    const editFollowupForm = document.getElementById('editFollowupForm');
    
    if (followupForm) {
        followupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Follow-up form submitted');
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                    <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
                </svg>
                Adding Follow-up...
            `;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            const headers = {};
            
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                console.log('CSRF token found and added to headers');
            } else {
                console.error('CSRF token not found!');
            }
            
            fetch('{{ route("leads.followups.store", $lead->id) }}', {
                method: 'POST',
                body: formData,
                headers: headers
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Follow-up added successfully!', 'success');
                    closeFollowupModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('Error adding follow-up: ' + (data.message || 'Please check all fields'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error adding follow-up', 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
    
    if (editFollowupForm) {
        editFollowupForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Edit follow-up form submitted');
            
            const formData = new FormData(this);
            const followupId = document.getElementById('edit_followup_id').value;
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
        <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
        </svg>
        Updating Follow-up...
    `;            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            const headers = {};
            
            if (csrfToken) {
                headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
                console.log('CSRF token found for edit form');
            } else {
                console.error('CSRF token not found for edit form!');
            }
            
            fetch(`/api/followups/${followupId}`, {
                method: 'POST',
                body: formData,
                headers: headers
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('Follow-up updated successfully!', 'success');
                    closeEditFollowupModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showAlert('Error updating follow-up: ' + (data.message || 'Please check all fields'), 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error updating follow-up', 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});

// Alert function
function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    const alertId = 'alert-' + Date.now();
    
    const alertColors = {
        success: 'bg-green-500',
        error: 'bg-red-500',
        info: 'bg-blue-500'
    };
    
    const alertHTML = `
        <div id="${alertId}" class="transform transition-all duration-300 translate-x-full">
            <div class="${alertColors[type]} text-white px-6 py-4 rounded-xl shadow-lg mb-4 max-w-sm">
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="closeAlert('${alertId}')" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    alertContainer.insertAdjacentHTML('beforeend', alertHTML);
    
    // Animate in
    setTimeout(() => {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.classList.remove('translate-x-full');
        }
    }, 100);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        closeAlert(alertId);
    }, 5000);
}

function closeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.classList.add('translate-x-full');
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
}

// Close modals when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'followupModal') {
        closeFollowupModal();
    }
    if (e.target.id === 'editFollowupModal') {
        closeEditFollowupModal();
    }
});

console.log('Lead show page script fully loaded');
</script>
@endsection
