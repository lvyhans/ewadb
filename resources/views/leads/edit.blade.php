@extends('layouts.app')

@section('page-title', 'Edit Lead')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Edit Lead</h1>
                    <p class="text-gray-600 mt-2">Update lead information for {{ $lead->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('leads.show', $lead->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        View Lead
                    </a>
                    <a href="{{ route('leads.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Back to Leads
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="p-8">
                <form id="lead_form" action="{{ route('leads.update', $lead->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <!-- Country & College Selection -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">1</div>
                            Preferred Country & College
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Country <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="preferred_country" id="preferred_country" 
                                       value="{{ old('preferred_country', $lead->preferred_country) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter preferred country" required>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Preferred Province/City</label>
                                <input type="text" name="preferred_city" id="preferred_city"
                                       value="{{ old('preferred_city', $lead->preferred_city) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter preferred city/province">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Preferred College</label>
                                <input type="text" name="preferred_college" id="preferred_college"
                                       value="{{ old('preferred_college', $lead->preferred_college) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter preferred college">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Preferred Course</label>
                                <input type="text" name="preferred_course" id="preferred_course"
                                       value="{{ old('preferred_course', $lead->preferred_course) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter course name">
                            </div>
                        </div>
                    </div>

                    <!-- Personal Details -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">2</div>
                            Personal Details
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Reference Number</label>
                                <input type="text" class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl backdrop-blur-sm transition-all cursor-not-allowed" 
                                       value="{{ $lead->ref_no }}" readonly>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $lead->name) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter full name" required>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                <input type="date" name="dob" id="dob" 
                                       value="{{ old('dob', $lead->dob?->format('Y-m-d')) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Father's Name</label>
                                <input type="text" name="father_name" id="father_name" 
                                       value="{{ old('father_name', $lead->father_name) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter father's name">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">
                                    Primary Phone <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone" 
                                       value="{{ old('phone', $lead->phone) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter phone number" required>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Alternate Phone</label>
                                <input type="tel" name="alt_phone" id="alt_phone" 
                                       value="{{ old('alt_phone', $lead->alt_phone) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter alternate phone">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Email Address</label>
                                <input type="email" name="email" id="email" 
                                       value="{{ old('email', $lead->email) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter email address">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">City</label>
                                <input type="text" name="city" id="city" 
                                       value="{{ old('city', $lead->city) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter current city">
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea name="address" id="address" rows="3"
                                          class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                          placeholder="Enter complete address">{{ old('address', $lead->address) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Background Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">3</div>
                            Background Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Travel History</label>
                                <textarea name="travel_history" id="travel_history" rows="3"
                                          class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                          placeholder="Previous travel details">{{ old('travel_history', $lead->travel_history) }}</textarea>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Any Visa Refusal</label>
                                <textarea name="any_refusal" id="any_refusal" rows="3"
                                          class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                          placeholder="Previous refusal details">{{ old('any_refusal', $lead->any_refusal) }}</textarea>
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Spouse Name</label>
                                <input type="text" name="spouse_name" id="spouse_name" 
                                       value="{{ old('spouse_name', $lead->spouse_name) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Enter spouse name (if married)">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Study Gap</label>
                                <textarea name="any_gap" id="any_gap" rows="3"
                                          class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                          placeholder="Any gap in studies">{{ old('any_gap', $lead->any_gap) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- English Proficiency -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">4</div>
                            English Proficiency
                        </h3>
                        
                        <div class="space-y-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Test Type</label>
                                <select name="score_type" id="score_type" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
                                    <option value="">Select Test Type</option>
                                    <option value="ielts" {{ old('score_type', $lead->score_type) == 'ielts' ? 'selected' : '' }}>IELTS</option>
                                    <option value="toefl" {{ old('score_type', $lead->score_type) == 'toefl' ? 'selected' : '' }}>TOEFL</option>
                                    <option value="pte" {{ old('score_type', $lead->score_type) == 'pte' ? 'selected' : '' }}>PTE</option>
                                    <option value="duolingo" {{ old('score_type', $lead->score_type) == 'duolingo' ? 'selected' : '' }}>Duolingo</option>
                                    <option value="other" {{ old('score_type', $lead->score_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    <option value="none" {{ old('score_type', $lead->score_type) == 'none' ? 'selected' : '' }}>Not Taken</option>
                                </select>
                            </div>
                            
                            <div id="score_fields" class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Overall Score</label>
                                    <input type="number" step="0.1" name="ielts_overall" id="overall_score" 
                                           value="{{ old('ielts_overall', $lead->ielts_overall) }}"
                                           class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                           placeholder="0.0">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Listening</label>
                                    <input type="number" step="0.1" name="ielts_listening" id="listening_score" 
                                           value="{{ old('ielts_listening', $lead->ielts_listening) }}"
                                           class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                           placeholder="0.0">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Reading</label>
                                    <input type="number" step="0.1" name="ielts_reading" id="reading_score" 
                                           value="{{ old('ielts_reading', $lead->ielts_reading) }}"
                                           class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                           placeholder="0.0">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Writing</label>
                                    <input type="number" step="0.1" name="ielts_writing" id="writing_score" 
                                           value="{{ old('ielts_writing', $lead->ielts_writing) }}"
                                           class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                           placeholder="0.0">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Speaking</label>
                                    <input type="number" step="0.1" name="ielts_speaking" id="speaking_score" 
                                           value="{{ old('ielts_speaking', $lead->ielts_speaking) }}"
                                           class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                           placeholder="0.0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Work Experience -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">5</div>
                            Work Experience
                        </h3>
                        
                        <div class="bg-gray-50/50 rounded-xl p-6 backdrop-blur-sm">
                            <div id="employmentRows" class="space-y-4">
                                @if($lead->employmentHistory && $lead->employmentHistory->count() > 0)
                                    @foreach($lead->employmentHistory as $index => $employment)
                                        <div class="employment-row bg-white rounded-xl p-6 shadow-sm border border-gray-100" data-index="{{ $index }}">
                                            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">Join Date</label>
                                                    <input type="date" name="employementhistory[{{ $index }}][join_date]" 
                                                           value="{{ old('employementhistory.'.$index.'.join_date', $employment->join_date?->format('Y-m-d')) }}"
                                                           class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                                                    <input type="date" name="employementhistory[{{ $index }}][left_date]" 
                                                           value="{{ old('employementhistory.'.$index.'.left_date', $employment->left_date?->format('Y-m-d')) }}"
                                                           class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                                                    <input type="text" name="employementhistory[{{ $index }}][company_name]" 
                                                           value="{{ old('employementhistory.'.$index.'.company_name', $employment->company_name) }}"
                                                           class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                                           placeholder="Company name">
                                                </div>
                                                <div class="space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">Job Position</label>
                                                    <input type="text" name="employementhistory[{{ $index }}][job_position]" 
                                                           value="{{ old('employementhistory.'.$index.'.job_position', $employment->job_position) }}"
                                                           class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                                           placeholder="Job title">
                                                </div>
                                            </div>
                                            
                                            <div class="flex flex-col md:flex-row gap-4 items-end">
                                                <div class="flex-1 space-y-2">
                                                    <label class="block text-sm font-medium text-gray-700">City/Country</label>
                                                    <input type="text" name="employementhistory[{{ $index }}][job_city]" 
                                                           value="{{ old('employementhistory.'.$index.'.job_city', $employment->job_city) }}"
                                                           class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                                           placeholder="Location">
                                                </div>
                                                <div class="flex-shrink-0 flex gap-2">
                                                    @if($index == 0)
                                                        <button type="button" class="add-employment px-6 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors flex items-center gap-2" 
                                                               title="Add another work experience" 
                                                               onclick="addEmploymentRow();">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Add More</span>
                                                        </button>
                                                    @else
                                                        <button type="button" class="remove-employment px-6 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors flex items-center gap-2" 
                                                               onclick="removeEmploymentRow(this);">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                            </svg>
                                                            <span class="hidden sm:inline">Remove</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="employment-row bg-white rounded-xl p-6 shadow-sm border border-gray-100" data-index="0">
                                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">Join Date</label>
                                                <input type="date" name="employementhistory[0][join_date]" 
                                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
                                            </div>
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">End Date</label>
                                                <input type="date" name="employementhistory[0][left_date]" 
                                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
                                            </div>
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">Company Name</label>
                                                <input type="text" name="employementhistory[0][company_name]" 
                                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                                       placeholder="Company name">
                                            </div>
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">Job Position</label>
                                                <input type="text" name="employementhistory[0][job_position]" 
                                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                                       placeholder="Job title">
                                            </div>
                                        </div>
                                        
                                        <div class="flex flex-col md:flex-row gap-4 items-end">
                                            <div class="flex-1 space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">City/Country</label>
                                                <input type="text" name="employementhistory[0][job_city]" 
                                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                                       placeholder="Location">
                                            </div>
                                            <div class="flex-shrink-0">
                                                <button type="button" class="add-employment px-6 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors flex items-center gap-2" 
                                                       title="Add another work experience" 
                                                       onclick="addEmploymentRow();">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    <span class="hidden sm:inline">Add More</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Educational Qualifications -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">6</div>
                            Educational Qualifications
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Last Qualification</label>
                                <select name="last_qualification" id="last_qualification" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
                                    <option value="">Select Qualification</option>
                                    <option value="10th" {{ old('last_qualification', $lead->last_qualification) == '10th' ? 'selected' : '' }}>10th</option>
                                    <option value="12th" {{ old('last_qualification', $lead->last_qualification) == '12th' ? 'selected' : '' }}>12th</option>
                                    <option value="diploma" {{ old('last_qualification', $lead->last_qualification) == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                    <option value="bachelor" {{ old('last_qualification', $lead->last_qualification) == 'bachelor' ? 'selected' : '' }}>Bachelor</option>
                                    <option value="graduation" {{ old('last_qualification', $lead->last_qualification) == 'graduation' ? 'selected' : '' }}>Graduation</option>
                                    <option value="master" {{ old('last_qualification', $lead->last_qualification) == 'master' ? 'selected' : '' }}>Master</option>
                                    <option value="post graduation" {{ old('last_qualification', $lead->last_qualification) == 'post graduation' ? 'selected' : '' }}>Post Graduation</option>
                                    <option value="phd" {{ old('last_qualification', $lead->last_qualification) == 'phd' ? 'selected' : '' }}>PhD</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Previous Visa Application</label>
                                <input type="text" name="previous_visa_application" id="previous_visa_application" 
                                       value="{{ old('previous_visa_application', $lead->previous_visa_application) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Details if applied before">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                            <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">7</div>
                            Additional Information
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Source</label>
                                <input type="text" name="source" id="source" 
                                       value="{{ old('source', $lead->source) }}"
                                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                       placeholder="Lead source (e.g., Website, Referral, etc.)">
                            </div>
                            
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <select name="status" id="status" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
                                    <option value="new" {{ old('status', $lead->status) == 'new' ? 'selected' : '' }}>New</option>
                                    <option value="contacted" {{ old('status', $lead->status) == 'contacted' ? 'selected' : '' }}>Contacted</option>
                                    <option value="qualified" {{ old('status', $lead->status) == 'qualified' ? 'selected' : '' }}>Qualified</option>
                                    <option value="converted" {{ old('status', $lead->status) == 'converted' ? 'selected' : '' }}>Converted</option>
                                    <option value="rejected" {{ old('status', $lead->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Remarks</label>
                                <textarea name="remarks" id="remarks" rows="4"
                                          class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                                          placeholder="Any additional remarks or notes about this lead">{{ old('remarks', $lead->remarks) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <a href="{{ route('leads.show', $lead->id) }}" 
                           class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-colors font-medium">
                            Update Lead
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Employment Row Management Script -->
<script>
let employmentIndex = {{ $lead->employmentHistory ? $lead->employmentHistory->count() : 1 }};

function addEmploymentRow() {
    const container = document.getElementById('employmentRows');
    const newRow = document.createElement('div');
    newRow.className = 'employment-row bg-white rounded-xl p-6 shadow-sm border border-gray-100';
    newRow.setAttribute('data-index', employmentIndex);
    
    newRow.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Join Date</label>
                <input type="date" name="employementhistory[${employmentIndex}][join_date]" 
                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="employementhistory[${employmentIndex}][left_date]" 
                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Company Name</label>
                <input type="text" name="employementhistory[${employmentIndex}][company_name]" 
                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                       placeholder="Company name">
            </div>
            <div class="space-y-2">
                <label class="block text-sm font-medium text-gray-700">Job Position</label>
                <input type="text" name="employementhistory[${employmentIndex}][job_position]" 
                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                       placeholder="Job title">
            </div>
        </div>
        
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <div class="flex-1 space-y-2">
                <label class="block text-sm font-medium text-gray-700">City/Country</label>
                <input type="text" name="employementhistory[${employmentIndex}][job_city]" 
                       class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" 
                       placeholder="Location">
            </div>
            <div class="flex-shrink-0">
                <button type="button" class="remove-employment px-6 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors flex items-center gap-2" 
                       onclick="removeEmploymentRow(this);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span class="hidden sm:inline">Remove</span>
                </button>
            </div>
        </div>
    `;
    
    container.appendChild(newRow);
    employmentIndex++;
}

function removeEmploymentRow(button) {
    const row = button.closest('.employment-row');
    row.remove();
}
</script>
@endsection
