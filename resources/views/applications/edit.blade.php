@extends('layouts.app')

@section('page-title', 'Edit Application')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold gradient-text mb-2">Edit Application</h1>
                <p class="text-gray-600">Update application information - {{ $application->application_number }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('applications.show', $application->id) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-500 text-white font-medium rounded-xl hover:bg-gray-600 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View Application
                </a>
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

    <!-- Application Form -->
    <form action="{{ route('applications.update', $application->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <!-- Hidden field for lead reference -->
        <input type="hidden" id="lead_ref_no" name="lead_ref_no" value="{{ old('lead_ref_no', $application->lead_ref_no) }}">

        <!-- Personal Information Section -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                Personal Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="md:col-span-2 lg:col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                    <input type="text" id="name" name="name" required
                           value="{{ old('name', $application->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200"
                           placeholder="Enter full name">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                    <input type="email" id="email" name="email" required
                           value="{{ old('email', $application->email) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200"
                           placeholder="Enter email address">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
                    <input type="text" id="phone" name="phone" required
                           value="{{ old('phone', $application->phone) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200"
                           placeholder="Enter phone number">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth"
                           value="{{ old('date_of_birth', $application->date_of_birth?->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200">
                </div>
                
                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select id="gender" name="gender" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200">
                        <option value="">Select gender</option>
                        <option value="male" {{ old('gender', $application->gender) == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $application->gender) == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $application->gender) == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
                
                <div>
                    <label for="nationality" class="block text-sm font-medium text-gray-700 mb-2">Nationality</label>
                    <input type="text" id="nationality" name="nationality"
                           value="{{ old('nationality', $application->nationality) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200"
                           placeholder="Enter nationality">
                </div>
            </div>
        </div>

        <!-- Study Information Section -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                Study Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div>
                    <label for="preferred_country" class="block text-sm font-medium text-gray-700 mb-2">Preferred Country</label>
                    <input type="text" id="preferred_country" name="preferred_country"
                           value="{{ old('preferred_country', $application->preferred_country) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200"
                           placeholder="Enter preferred country">
                </div>
                
                <div>
                    <label for="preferred_city" class="block text-sm font-medium text-gray-700 mb-2">Preferred City</label>
                    <input type="text" id="preferred_city" name="preferred_city"
                           value="{{ old('preferred_city', $application->preferred_city) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200"
                           placeholder="Enter preferred city">
                </div>
                
                <div>
                    <label for="preferred_college" class="block text-sm font-medium text-gray-700 mb-2">Preferred College</label>
                    <input type="text" id="preferred_college" name="preferred_college"
                           value="{{ old('preferred_college', $application->preferred_college) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200"
                           placeholder="Enter preferred college">
                </div>
                
                <div>
                    <label for="course_level" class="block text-sm font-medium text-gray-700 mb-2">Course Level</label>
                    <select id="course_level" name="course_level" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200">
                        <option value="">Select course level</option>
                        <option value="certificate" {{ old('course_level', $application->course_level) == 'certificate' ? 'selected' : '' }}>Certificate</option>
                        <option value="diploma" {{ old('course_level', $application->course_level) == 'diploma' ? 'selected' : '' }}>Diploma</option>
                        <option value="bachelor" {{ old('course_level', $application->course_level) == 'bachelor' ? 'selected' : '' }}>Bachelor's Degree</option>
                        <option value="master" {{ old('course_level', $application->course_level) == 'master' ? 'selected' : '' }}>Master's Degree</option>
                        <option value="phd" {{ old('course_level', $application->course_level) == 'phd' ? 'selected' : '' }}>PhD</option>
                    </select>
                </div>
                
                <div>
                    <label for="field_of_study" class="block text-sm font-medium text-gray-700 mb-2">Field of Study</label>
                    <input type="text" id="field_of_study" name="field_of_study"
                           value="{{ old('field_of_study', $application->field_of_study) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200"
                           placeholder="Enter field of study">
                </div>
                
                <div>
                    <label for="intake_year" class="block text-sm font-medium text-gray-700 mb-2">Intake Year</label>
                    <input type="number" id="intake_year" name="intake_year"
                           value="{{ old('intake_year', $application->intake_year) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200"
                           placeholder="Enter intake year">
                </div>
                
                <div>
                    <label for="intake_month" class="block text-sm font-medium text-gray-700 mb-2">Intake Month</label>
                    <select id="intake_month" name="intake_month" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200">
                        <option value="">Select Month</option>
                        <option value="jan" {{ old('intake_month', $application->intake_month) == 'jan' ? 'selected' : '' }}>Jan</option>
                        <option value="feb" {{ old('intake_month', $application->intake_month) == 'feb' ? 'selected' : '' }}>Feb</option>
                        <option value="mar" {{ old('intake_month', $application->intake_month) == 'mar' ? 'selected' : '' }}>Mar</option>
                        <option value="apr" {{ old('intake_month', $application->intake_month) == 'apr' ? 'selected' : '' }}>Apr</option>
                        <option value="may" {{ old('intake_month', $application->intake_month) == 'may' ? 'selected' : '' }}>May</option>
                        <option value="jun" {{ old('intake_month', $application->intake_month) == 'jun' ? 'selected' : '' }}>Jun</option>
                        <option value="jul" {{ old('intake_month', $application->intake_month) == 'jul' ? 'selected' : '' }}>Jul</option>
                        <option value="aug" {{ old('intake_month', $application->intake_month) == 'aug' ? 'selected' : '' }}>Aug</option>
                        <option value="sep" {{ old('intake_month', $application->intake_month) == 'sep' ? 'selected' : '' }}>Sep</option>
                        <option value="oct" {{ old('intake_month', $application->intake_month) == 'oct' ? 'selected' : '' }}>Oct</option>
                        <option value="nov" {{ old('intake_month', $application->intake_month) == 'nov' ? 'selected' : '' }}>Nov</option>
                        <option value="dec" {{ old('intake_month', $application->intake_month) == 'dec' ? 'selected' : '' }}>Dec</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Employment History Section -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2h8z"></path>
                    </svg>
                    Employment History
                </h2>
                <button type="button" onclick="addEmploymentRow()" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition-all duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Employment
                </button>
            </div>
            
            <div id="employment-container">
                @if($application->employmentHistory && count($application->employmentHistory) > 0)
                    @foreach($application->employmentHistory as $index => $employment)
                        <div class="employment-row border border-gray-200 rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-md font-semibold text-gray-700">Employment {{ $index + 1 }}</h4>
                                <button type="button" onclick="removeEmploymentRow(this)" 
                                        class="text-red-600 hover:text-red-800 transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                                    <input type="text" name="employment[{{ $index }}][company_name]" 
                                           value="{{ old('employment.'.$index.'.company_name', $employment->company_name) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent"
                                           placeholder="Enter company name">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                                    <input type="text" name="employment[{{ $index }}][position]" 
                                           value="{{ old('employment.'.$index.'.position', $employment->position) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent"
                                           placeholder="Enter position">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                    <input type="date" name="employment[{{ $index }}][start_date]" 
                                           value="{{ old('employment.'.$index.'.start_date', $employment->start_date?->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                                    <input type="date" name="employment[{{ $index }}][end_date]" 
                                           value="{{ old('employment.'.$index.'.end_date', $employment->end_date?->format('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                                    <input type="text" name="employment[{{ $index }}][location]" 
                                           value="{{ old('employment.'.$index.'.location', $employment->location) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent"
                                           placeholder="Enter work location">
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Status & Assignment Section -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Application Status & Management
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200">
                        <option value="pending" {{ old('status', $application->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="in_progress" {{ old('status', $application->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="approved" {{ old('status', $application->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status', $application->status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ old('status', $application->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
                
                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                    <select id="assigned_to" name="assigned_to" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200">
                        <option value="">Unassigned</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('assigned_to', $application->assigned_to) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">Remarks</label>
                    <textarea id="remarks" name="remarks" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all duration-200"
                              placeholder="Add any additional notes or remarks">{{ old('remarks', $application->remarks) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
            <div class="flex items-center justify-end space-x-4">
                <a href="{{ route('applications.show', $application->id) }}" 
                   class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-xl hover:bg-gray-50 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Cancel
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Application
                </button>
            </div>
        </div>
    </form>
</div>

<!-- JavaScript for dynamic employment -->
<script>
// Employment section management
let employmentIndex = {{ $application->employmentHistory ? $application->employmentHistory->count() : 0 }};

function addEmploymentRow(data = null) {
    const container = document.getElementById('employment-container');
    const index = employmentIndex++;
    
    const row = document.createElement('div');
    row.className = 'employment-row border border-gray-200 rounded-lg p-4 mb-4';
    row.innerHTML = `
        <div class="flex items-center justify-between mb-4">
            <h4 class="text-md font-semibold text-gray-700">Employment ${index + 1}</h4>
            <button type="button" onclick="removeEmploymentRow(this)" 
                    class="text-red-600 hover:text-red-800 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Company Name</label>
                <input type="text" name="employment[${index}][company_name]" 
                       value="${data?.company_name || ''}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent"
                       placeholder="Enter company name">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Position</label>
                <input type="text" name="employment[${index}][position]" 
                       value="${data?.position || ''}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent"
                       placeholder="Enter position">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                <input type="date" name="employment[${index}][start_date]" 
                       value="${data?.start_date || ''}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                <input type="date" name="employment[${index}][end_date]" 
                       value="${data?.end_date || ''}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                <input type="text" name="employment[${index}][location]" 
                       value="${data?.location || ''}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent"
                       placeholder="Enter work location">
            </div>
        </div>
    `;
    
    container.appendChild(row);
}

function removeEmploymentRow(button) {
    button.closest('.employment-row').remove();
}
</script>
@endsection
