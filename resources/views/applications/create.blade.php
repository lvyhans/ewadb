@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Apply Application</h1>
                    <p class="text-gray-600 mt-2">Submit a new visa application form</p>
                </div>
                <a href="{{ route('applications.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Applications
                </a>
            </div>
        </div>

        <!-- Auto-fill Card -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-8">
            <div class="p-8 bg-gradient-to-r from-blue-500/10 to-purple-500/10">
                <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    Auto-fill from Lead
                </h3>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-blue-800 text-sm flex items-start">
                        <svg class="w-5 h-5 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        <span>If you have a lead reference number, enter it below to automatically fill the form with existing lead data.</span>
                    </p>
                </div>
                
                <div class="flex gap-4">
                    <div class="flex-1">
                        <input type="text" id="leadRefNumber" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/50 focus:border-transparent backdrop-blur-sm transition-all" placeholder="Enter lead reference number (e.g., LD001)">
                    </div>
                    <button type="button" onclick="fetchLeadData()" class="px-6 py-3 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors font-medium">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Auto-Fill
                    </button>
                </div>
            </div>
        </div>

        <!-- Multiple Course Selection Banner -->
        <div id="multipleCourseBanner" class="hidden bg-gradient-to-r from-green-500/20 to-blue-500/20 backdrop-blur-sm rounded-2xl shadow-xl border border-green-200/50 overflow-hidden mb-8">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white mr-4">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Multiple Courses Selected</h3>
                            <p class="text-gray-600">You have selected <span id="selectedCourseCount" class="font-medium text-green-600">0</span> courses from the course finder</p>
                        </div>
                    </div>
                    <button onclick="hideMultipleCourseBanner()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors text-sm">
                        Hide Banner
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="p-8">
                    <form id="lead_form" action="{{ route('applications.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <h4 class="text-red-800 font-medium">Please correct the following errors:</h4>
                                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
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
                            <select name="country" id="country" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" required>
                                <option value="">Select a country</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Preferred Province/City</label>
                            <select class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" id="state" name="city" disabled>
                                <option value="">Select a city/province</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Preferred College</label>
                            <select class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" id="college" name="college" disabled>
                                <option value="">Select a college</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Preferred Course</label>
                            <select class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="course" id="course" disabled>
                                <option value="">Select a course</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Multiple Course Selection Section -->
                <div id="multipleCourseSelection" class="hidden mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">📚</div>
                        Selected Course Options
                    </h3>
                    
                    <div class="bg-blue-50/50 rounded-xl p-6 backdrop-blur-sm">
                        <div class="flex justify-between items-center mb-4">
                            <p class="text-gray-700">Review and edit your selected course options below:</p>
                            <button type="button" onclick="addNewCourseOption()" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors text-sm">
                                + Add Option
                            </button>
                        </div>
                        
                        <div id="selectedCoursesContainer" class="space-y-4">
                            <!-- Selected courses will be displayed here -->
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
                            <input type="text" class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl backdrop-blur-sm transition-all cursor-not-allowed" name="ref_no" placeholder="Auto generated" readonly>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Full Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="name" placeholder="Enter full name" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="dob">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Father's Name</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="father" placeholder="Enter father's name">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Mobile <span class="text-red-500">*</span>
                            </label>
                            <input class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" type="tel" name="phone" placeholder="Phone number" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Alternative Phone</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="rphone" placeholder="Alternative number">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Email Address</label>
                            <input type="email" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="email" placeholder="Enter email address">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Residential City</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="f_city" placeholder="Enter city">
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="address" placeholder="Enter full address">
                        </div>
                    </div>
                </div>
                <!-- Background Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">3</div>
                        Background Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Travel History</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="travel_history" placeholder="Previous travel details">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Any Refusal</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="any_refusal" placeholder="Visa refusal history">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Spouse Name</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="spouse_name" placeholder="Spouse's name">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Any Gap in Studies</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="any_gap" placeholder="Study gap details">
                        </div>
                    </div>
                </div>
                <!-- English Proficiency -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">4</div>
                        English Proficiency Test
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex flex-wrap gap-6">
                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-blue-50 p-3 rounded-lg transition-colors">
                                <input type="radio" name="score_type" value="ielts" id="ielts_radio" class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-sm font-medium text-gray-700">IELTS</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-blue-50 p-3 rounded-lg transition-colors">
                                <input type="radio" name="score_type" value="pte" id="pte_radio" class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-sm font-medium text-gray-700">PTE</span>
                            </label>
                            <label class="flex items-center space-x-3 cursor-pointer hover:bg-blue-50 p-3 rounded-lg transition-colors">
                                <input type="radio" name="score_type" value="duolingo" id="duolingo_radio" class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                <span class="text-sm font-medium text-gray-700">DUOLINGO</span>
                            </label>
                        </div>
                        
                        <!-- Score Fields (will be shown/hidden based on selection) -->
                        <div id="score_fields" class="hidden mt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Overall Score</label>
                                    <input type="number" step="0.1" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" id="overall_score" placeholder="0.0">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Listening</label>
                                    <input type="number" step="0.1" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" id="listening_score" placeholder="0.0">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Reading</label>
                                    <input type="number" step="0.1" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" id="reading_score" placeholder="0.0">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Writing</label>
                                    <input type="number" step="0.1" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" id="writing_score" placeholder="0.0">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Speaking</label>
                                    <input type="number" step="0.1" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" id="speaking_score" placeholder="0.0">
                                </div>
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
                            <div class="employment-row bg-white rounded-xl p-6 shadow-sm border border-gray-100" data-index="0">
                                <!-- Work Experience Fields Container -->
                                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Join Date</label>
                                        <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[0][join_date]">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[0][left_date]">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Company Name</label>
                                        <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[0][company_name]" placeholder="Company name">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">Job Position</label>
                                        <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[0][job_position]" placeholder="Job title">
                                    </div>
                                </div>
                                
                                <!-- Location and Action Button Row -->
                                <div class="flex flex-col md:flex-row gap-4 items-end">
                                    <div class="flex-1 space-y-2">
                                        <label class="block text-sm font-medium text-gray-700">City/Country</label>
                                        <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[0][job_city]" placeholder="Location">
                                    </div>
                                    <div class="flex-shrink-0">
                                        <button type="button" class="add-employment px-6 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors cursor-pointer flex items-center gap-2" 
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
                        </div>
                    </div>
                </div>

                <!-- Work experience section is ready! -->

                <!-- Qualifications -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">6</div>
                        Educational Qualifications
                    </h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Last Qualification</label>
                                <select name="last_qual" id="last_qual" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all">
                                    <option value="">Select Qualification</option>
                                    <option value="12th">12th</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="Graduation">Graduation</option>
                                    <option value="Post Graduation">Post Graduation</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Previous Visa Application</label>
                                <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="already_applied" placeholder="Details if applied before">
                            </div>
                        </div>

                        <!-- 12th Grade -->
                        <div id="qual_12th" class="hidden qualification-section bg-blue-50/50 rounded-xl p-4">
                            <h4 class="font-medium text-gray-900 mb-4">12th Grade Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Year</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="twelveyear" placeholder="Year">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Percentage</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="twelvemarks" placeholder="Percentage">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Major/Stream</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="twelvemajor" placeholder="Major">
                                </div>
                            </div>
                        </div>

                        <!-- 10th Grade (will show with 12th) -->
                        <div id="qual_10th" class="hidden qualification-section bg-green-50/50 rounded-xl p-4">
                            <h4 class="font-medium text-gray-900 mb-4">10th Grade Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Year</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="tenyear" placeholder="Year">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Percentage</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="tenmarks" placeholder="Percentage">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Major/Stream</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="ten_major" placeholder="Major">
                                </div>
                            </div>
                        </div>

                        <!-- Diploma -->
                        <div id="qual_diploma" class="hidden qualification-section bg-purple-50/50 rounded-xl p-4">
                            <h4 class="font-medium text-gray-900 mb-4">Diploma Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Year</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="ugdiplomayear" placeholder="Year">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Percentage</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="ugdiplomamarks" placeholder="Percentage">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Major/Stream</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="ugdiplomamajor" placeholder="Major">
                                </div>
                            </div>
                        </div>

                        <!-- Graduation -->
                        <div id="qual_graduation" class="hidden qualification-section bg-indigo-50/50 rounded-xl p-4">
                            <h4 class="font-medium text-gray-900 mb-4">Graduation Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Year</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="byear" placeholder="Year">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Percentage</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="bmarks" placeholder="Percentage">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Major/Stream</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="gra_major" placeholder="Major">
                                </div>
                            </div>
                        </div>

                        <!-- Post Graduation -->
                        <div id="qual_postgrad" class="hidden qualification-section bg-orange-50/50 rounded-xl p-4">
                            <h4 class="font-medium text-gray-900 mb-4">Post Graduation Details</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Year</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="pgr_year" placeholder="Year">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Percentage</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="pgr_per" placeholder="Percentage">
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-sm font-medium text-gray-700">Major/Stream</label>
                                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="pgr_major" placeholder="Major">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Source Information -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">7</div>
                        How Did You Hear About Us?
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">
                                Source <span class="text-red-500">*</span>
                            </label>
                            <select name="source" id="lead_source" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" required>
                                <option value="">Select Source</option>
                                <option value="Google">Google</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Reference">Reference</option>
                                <option value="Website">Website</option>
                                <option value="Advertisement">Advertisement</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        
                        <div class="space-y-2 hidden" id="reference_name">
                            <label class="block text-sm font-medium text-gray-700">Reference Name</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="r_name" placeholder="Enter reference name">
                        </div>
                    </div>
                </div>
                
                <!-- Document Checklist -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">8</div>
                        Document Checklist
                    </h3>
                    
                    <div id="documentChecklistContainer" class="space-y-4">
                        <div id="documentChecklistLoading" class="hidden">
                            <div class="flex items-center justify-center py-8">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                <span class="ml-3 text-gray-600">Loading document requirements...</span>
                            </div>
                        </div>
                        
                        <div id="documentChecklistEmpty" class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p>Select a country and qualification to view document requirements</p>
                        </div>
                        
                        <div id="documentChecklistContent" class="hidden space-y-4">
                            <!-- Documents will be loaded here dynamically -->
                        </div>
                    </div>
                </div>
                
                <!-- Remarks -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">9</div>
                        Additional Information
                    </h3>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">
                            Remarks <span class="text-red-500">*</span>
                        </label>
                        <textarea class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all h-32 resize-none" name="remarks" placeholder="Enter any additional remarks or notes..." required></textarea>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <button type="button" class="px-6 py-3 bg-gray-500 text-white rounded-xl hover:bg-gray-600 transition-colors font-medium" onclick="resetForm()">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v8a8 8 0 008 8h8"></path>
                        </svg>
                        Reset Form
                    </button>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all font-medium shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Save Application
                    </button>
                </div>

                <input type="hidden" value="visa" name="form_type">
                    </form>
            </div>
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="alertContainer" class="fixed top-4 right-4 z-50"></div>

<script>
// Script loaded successfully

// Global variable for employment count
let employmentCount = 1;
console.log('Employment count initialized:', employmentCount);

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Form JavaScript initialized');
    console.log('Current employment count:', employmentCount);
    
    // Initialize cascading dropdowns
    initializeCascadingDropdowns();
    
    // Check for multiple course selection from course finder
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('from') === 'course-finder' && urlParams.get('multiple') === 'true') {
        handleMultipleCourseSelection();
    }
    
    // Show reference name field when source is Reference
    const leadSource = document.getElementById('lead_source');
    const referenceName = document.getElementById('reference_name');
    
    if (leadSource && referenceName) {
        leadSource.addEventListener('change', function() {
            console.log('Source changed to:', this.value);
            if (this.value === 'Reference') {
                referenceName.classList.remove('hidden');
            } else {
                referenceName.classList.add('hidden');
            }
        });
    }

    // Handle English proficiency test type selection
    const scoreTypeRadios = document.querySelectorAll('input[name="score_type"]');
    const scoreFields = document.getElementById('score_fields');
    
    if (scoreTypeRadios.length > 0 && scoreFields) {
        scoreTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                console.log('Score type changed to:', this.value);
                if (this.checked) {
                    scoreFields.classList.remove('hidden');
                    updateScoreFieldNames(this.value);
                }
            });
        });
    }

    // Handle qualification selection
    const lastQualSelect = document.getElementById('last_qual');
    if (lastQualSelect) {
        lastQualSelect.addEventListener('change', function() {
            console.log('Qualification changed to:', this.value);
            handleQualificationChange(this.value);
        });
    }

    // Handle employment history addition with event delegation
    document.addEventListener('click', function(e) {
        console.log('Click detected on:', e.target);
        
        // Check if clicked element or its parent is an add-employment button
        if (e.target.classList.contains('add-employment') || e.target.closest('.add-employment')) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Add employment button clicked');
            addEmploymentRow();
            return;
        }
        
        // Check if clicked element or its parent is a remove-employment button
        if (e.target.classList.contains('remove-employment') || e.target.closest('.remove-employment')) {
            e.preventDefault();
            e.stopPropagation();
            console.log('Remove employment button clicked');
            const row = e.target.closest('.employment-row');
            if (row) {
                row.style.transform = 'translateX(100%)';
                row.style.opacity = '0';
                setTimeout(() => {
                    row.remove();
                    showAlert('Work experience removed', 'info');
                }, 300);
            }
            return;
        }
    });

    // Enhanced form submission with better error handling
    const form = document.getElementById('lead_form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Form submitted');
            
            const formData = new FormData(this);
            const submitBtn = document.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                    <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
                </svg>
                Saving Application...
            `;
            
            fetch('{{ route("applications.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('Response received:', response.status);
                if (response.ok) {
                    // Check if response is JSON or redirect
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json().then(data => {
                            if (data.success) {
                                showAlert('Application created successfully!', 'success');
                                setTimeout(() => {
                                    window.location.href = '{{ route("applications.index") }}';
                                }, 1500);
                            } else {
                                throw new Error(data.message || 'An error occurred');
                            }
                        });
                    } else {
                        // Redirect response - success
                        showAlert('Application created successfully!', 'success');
                        setTimeout(() => {
                            window.location.href = '{{ route("applications.index") }}';
                        }, 1500);
                    }
                } else {
                    return response.json().then(data => {
                        throw new Error(data.message || 'An error occurred');
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('Error creating application: ' + error.message, 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});

// Global backup functions
window.addEmploymentRow = function() {
    console.log('Global addEmploymentRow called, current count:', employmentCount);
    
    const employmentRows = document.getElementById('employmentRows');
    if (!employmentRows) {
        console.error('Employment rows container not found');
        alert('Error: Employment container not found');
        return;
    }
    
    console.log('Employment container found, adding new row...');
    
    const newRowHTML = `
        <div class="employment-row bg-white rounded-xl p-6 shadow-sm border border-gray-100 transform translate-x-full opacity-0 transition-all duration-300" data-index="${employmentCount}">
            <!-- Work Experience Fields Container -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Join Date</label>
                    <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${employmentCount}][join_date]">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${employmentCount}][left_date]">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${employmentCount}][company_name]" placeholder="Company name">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Job Position</label>
                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${employmentCount}][job_position]" placeholder="Job title">
                </div>
            </div>
            
            <!-- Location and Action Button Row -->
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">City/Country</label>
                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${employmentCount}][job_city]" placeholder="Location">
                </div>
                <div class="flex-shrink-0">
                    <button type="button" class="remove-employment px-6 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors flex items-center gap-2" title="Remove this work experience" onclick="console.log('Remove clicked'); this.closest('.employment-row').remove();">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="hidden sm:inline">Remove</span>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    try {
        employmentRows.insertAdjacentHTML('beforeend', newRowHTML);
        console.log('HTML inserted successfully');
        
        // Animate the new row in
        const newRow = employmentRows.lastElementChild;
        if (newRow) {
            setTimeout(() => {
                newRow.classList.remove('translate-x-full', 'opacity-0');
                console.log('Animation applied to new row');
            }, 10);
        }
        
        employmentCount++;
        console.log('Employment count incremented to:', employmentCount);
        
        // Show success message
        const message = document.createElement('div');
        message.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg z-50';
        message.textContent = 'Work experience added successfully!';
        document.body.appendChild(message);
        setTimeout(() => message.remove(), 3000);
        
    } catch (error) {
        console.error('Error adding employment row:', error);
        alert('Error adding work experience: ' + error.message);
    }
};

// Test functions when page loads completely
window.addEventListener('load', function() {
    console.log('Window loaded! Testing employment functionality...');
    console.log('employmentCount:', employmentCount);
    console.log('addEmploymentRow function available:', typeof window.addEmploymentRow);
    console.log('Employment rows container:', document.getElementById('employmentRows'));
});

function updateScoreFieldNames(scoreType) {
    const overallScore = document.getElementById('overall_score');
    const listeningScore = document.getElementById('listening_score');
    const readingScore = document.getElementById('reading_score');
    const writingScore = document.getElementById('writing_score');
    const speakingScore = document.getElementById('speaking_score');
    
    // Clear previous names
    [overallScore, listeningScore, readingScore, writingScore, speakingScore].forEach(field => {
        if (field) {
            field.name = '';
        }
    });
    
    // Set new names based on score type
    if (overallScore) overallScore.name = scoreType + '_overall';
    if (listeningScore) listeningScore.name = scoreType + '_listening';
    if (readingScore) readingScore.name = scoreType + '_reading';
    if (writingScore) writingScore.name = scoreType + '_writing';
    if (speakingScore) speakingScore.name = scoreType + '_speaking';
    
    console.log('Score field names updated for:', scoreType);
}

function handleQualificationChange(qualification) {
    // Hide all qualification sections
    document.querySelectorAll('.qualification-section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Show relevant sections based on selection
    switch(qualification) {
        case '12th':
            document.getElementById('qual_12th')?.classList.remove('hidden');
            document.getElementById('qual_10th')?.classList.remove('hidden');
            break;
        case 'Diploma':
            document.getElementById('qual_diploma')?.classList.remove('hidden');
            document.getElementById('qual_12th')?.classList.remove('hidden');
            document.getElementById('qual_10th')?.classList.remove('hidden');
            break;
        case 'Graduation':
            document.getElementById('qual_graduation')?.classList.remove('hidden');
            document.getElementById('qual_12th')?.classList.remove('hidden');
            document.getElementById('qual_10th')?.classList.remove('hidden');
            break;
        case 'Post Graduation':
            document.getElementById('qual_postgrad')?.classList.remove('hidden');
            document.getElementById('qual_graduation')?.classList.remove('hidden');
            document.getElementById('qual_12th')?.classList.remove('hidden');
            document.getElementById('qual_10th')?.classList.remove('hidden');
            break;
    }
}

function addEmploymentRow() {
    console.log('addEmploymentRow function called, current count:', employmentCount);
    
    const employmentRows = document.getElementById('employmentRows');
    if (!employmentRows) {
        console.error('Employment rows container not found');
        showAlert('Error: Employment container not found', 'error');
        return;
    }
    
    console.log('Employment container found, adding new row...');
    
    const newRowHTML = `
        <div class="employment-row bg-white rounded-xl p-6 shadow-sm border border-gray-100 transform translate-x-full opacity-0 transition-all duration-300" data-index="${employmentCount}">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Join Date</label>
                    <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${employmentCount}][join_date]">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${employmentCount}][left_date]">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${employmentCount}][company_name]" placeholder="Company name">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Job Position</label>
                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${employmentCount}][job_position]" placeholder="Job title">
                </div>
            </div>
            
            <!-- Location and Action Button Row -->
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">City/Country</label>
                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${employmentCount}][job_city]" placeholder="Location">
                </div>
                <div class="flex-shrink-0">
                    <button type="button" class="remove-employment px-6 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors flex items-center gap-2" title="Remove this work experience" onclick="console.log('Remove clicked'); this.closest('.employment-row').remove();">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <span class="hidden sm:inline">Remove</span>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    try {
        employmentRows.insertAdjacentHTML('beforeend', newRowHTML);
        console.log('HTML inserted successfully');
        
        // Animate the new row in
        const newRow = employmentRows.lastElementChild;
        if (newRow) {
            setTimeout(() => {
                newRow.classList.remove('translate-x-full', 'opacity-0');
                console.log('Animation applied to new row');
            }, 10);
        }
        
        employmentCount++;
        console.log('Employment count incremented to:', employmentCount);
        showAlert('Work experience added successfully', 'success');
        
    } catch (error) {
        console.error('Error adding employment row:', error);
        showAlert('Error adding work experience', 'error');
    }
}

function resetForm() {
    const form = document.getElementById('lead_form');
    const referenceName = document.getElementById('reference_name');
    const scoreFields = document.getElementById('score_fields');
    
    if (form) {
        form.reset();
    }
    
    if (referenceName) {
        referenceName.classList.add('hidden');
    }
    
    if (scoreFields) {
        scoreFields.classList.add('hidden');
    }
    
    // Reset qualification sections
    document.querySelectorAll('.qualification-section').forEach(section => {
        section.classList.add('hidden');
    });
    
    // Reset employment rows to just one
    const employmentRows = document.getElementById('employmentRows');
    if (employmentRows) {
        // Create the original first row HTML
        const originalRowHTML = `
            <div class="employment-row bg-white rounded-xl p-6 shadow-sm border border-gray-100" data-index="0">
                <!-- Work Experience Fields Container -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Join Date</label>
                        <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[0][join_date]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">End Date</label>
                        <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[0][left_date]">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Company Name</label>
                        <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[0][company_name]" placeholder="Company name">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Job Position</label>
                        <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[0][job_position]" placeholder="Job title">
                    </div>
                </div>
                
                <!-- Location and Action Button Row -->
                <div class="flex flex-col md:flex-row gap-4 items-end">
                    <div class="flex-1 space-y-2">
                        <label class="block text-sm font-medium text-gray-700">City/Country</label>
                        <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[0][job_city]" placeholder="Location">
                    </div>
                    <div class="flex-shrink-0">
                        <button type="button" class="add-employment px-6 py-3 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors cursor-pointer flex items-center gap-2" 
                               title="Add another work experience" 
                               onclick="console.log('Inline onclick fired'); addEmploymentRow();">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="hidden sm:inline">Add More</span>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        employmentRows.innerHTML = originalRowHTML;
        employmentCount = 1; // Reset counter
    }
    
    showAlert('Form has been reset', 'info');
}

window.showAlert = showAlert;
function showAlert(message, type) {
    const alertContainer = document.getElementById('alertContainer');
    if (!alertContainer) {
        console.error('Alert container not found');
        return;
    }
    
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
                    <span class="font-medium">${message}</span>
                    <button onclick="closeAlert('${alertId}')" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
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

// Auto-fill from lead data function - COMPLETE VERSION
function fetchLeadData() {
    const refNumber = document.getElementById('leadRefNumber').value.trim();
    if (!refNumber) {
        showAlert('Please enter a lead reference number', 'error');
        return;
    }
    
    console.log('Fetching lead data for reference:', refNumber);
    
    // Show loading state
    const autoFillBtn = document.querySelector('button[onclick="fetchLeadData()"]');
    const originalText = autoFillBtn.innerHTML;
    autoFillBtn.disabled = true;
    autoFillBtn.innerHTML = `
        <svg class="animate-spin w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
        </svg>
        Loading...
    `;
    
    fetch(`/applications/get-lead-data?ref_no=${encodeURIComponent(refNumber)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Lead data received:', data);
        if (data.success && data.lead) {
            fillFormWithLeadData(data.lead, data.employment_history || []);
            showAlert('Form auto-filled successfully from lead data!', 'success');
        } else {
            showAlert(data.message || 'Lead not found with this reference number', 'error');
        }
    })
    .catch(error => {
        console.error('Error fetching lead data:', error);
        showAlert('Error fetching lead data: ' + error.message, 'error');
    })
    .finally(() => {
        // Reset button state
        autoFillBtn.disabled = false;
        autoFillBtn.innerHTML = originalText;
    });
}

function fillFormWithLeadData(leadData, employmentHistory) {
    console.log('Filling form with lead data:', leadData);
    console.log('Employment history:', employmentHistory);
    
    try {
        // Basic form fields mapping
        const fieldMappings = {
            // Personal Details
            'name': leadData.name,
            'dob': leadData.dob,
            'father': leadData.father,
            'phone': leadData.phone,
            'rphone': leadData.rphone,
            'email': leadData.email,
            'f_city': leadData.f_city,
            'address': leadData.address,
            
            // Country & College
            'country': leadData.country,
            'city': leadData.city,
            'college': leadData.college,
            'course': leadData.course,
            
            // Background Information
            'travel_history': leadData.travel_history,
            'any_refusal': leadData.any_refusal,
            'spouse_name': leadData.spouse_name,
            'any_gap': leadData.any_gap,
            
            // Educational Qualifications
            'last_qual': leadData.last_qual,
            'already_applied': leadData.already_applied,
            
            // 10th Grade
            'tenyear': leadData.tenyear,
            'tenmarks': leadData.tenmarks,
            'ten_major': leadData.ten_major,
            
            // 12th Grade
            'twelveyear': leadData.twelveyear,
            'twelvemarks': leadData.twelvemarks,
            'twelvemajor': leadData.twelvemajor,
            
            // Diploma
            'ugdiplomayear': leadData.ugdiplomayear,
            'ugdiplomamarks': leadData.ugdiplomamarks,
            'ugdiplomamajor': leadData.ugdiplomamajor,
            
            // Graduation
            'byear': leadData.byear,
            'bmarks': leadData.bmarks,
            'gra_major': leadData.gra_major,
            
            // Post Graduation
            'pgr_year': leadData.pgr_year,
            'pgr_per': leadData.pgr_per,
            'pgr_major': leadData.pgr_major,
            
            // Source Information
            'source': leadData.source,
            'r_name': leadData.r_name,
            
            // Additional Information
            'remarks': leadData.remarks
        };
        
        // Fill form fields (excluding dropdown fields that need special handling)
        const dropdownFields = ['country', 'city', 'college', 'course'];
        Object.entries(fieldMappings).forEach(([fieldName, value]) => {
            if (dropdownFields.includes(fieldName)) return; // Skip dropdown fields for now
            
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field && value !== null && value !== undefined && value !== '') {
                field.value = value;
                console.log(`Filled ${fieldName} with value:`, value);
                
                // Trigger change event for fields that need it
                field.dispatchEvent(new Event('change'));
            }
        });
        
        // Handle dropdown fields with cascading logic
        setTimeout(() => {
            handleDropdownAutoFill(leadData);
        }, 500); // Allow time for countries to load
        
        // Handle English proficiency scores
        if (leadData.score_type) {
            const scoreTypeRadio = document.querySelector(`input[name="score_type"][value="${leadData.score_type}"]`);
            if (scoreTypeRadio) {
                scoreTypeRadio.checked = true;
                scoreTypeRadio.dispatchEvent(new Event('change'));
                
                // Fill score fields
                const scores = {
                    'overall_score': leadData[leadData.score_type + '_overall'],
                    'listening_score': leadData[leadData.score_type + '_listening'],
                    'reading_score': leadData[leadData.score_type + '_reading'],
                    'writing_score': leadData[leadData.score_type + '_writing'],
                    'speaking_score': leadData[leadData.score_type + '_speaking']
                };
                
                Object.entries(scores).forEach(([fieldId, value]) => {
                    const field = document.getElementById(fieldId);
                    if (field && value) {
                        field.value = value;
                        console.log(`Filled ${fieldId} with score:`, value);
                    }
                });
            }
        }
        
        // Handle qualification change
        if (leadData.last_qual) {
            const qualSelect = document.getElementById('last_qual');
            if (qualSelect) {
                qualSelect.value = leadData.last_qual;
                qualSelect.dispatchEvent(new Event('change'));
            }
        }
        
        // Handle source change
        if (leadData.source) {
            const sourceSelect = document.getElementById('lead_source');
            if (sourceSelect) {
                sourceSelect.value = leadData.source;
                sourceSelect.dispatchEvent(new Event('change'));
            }
        }
        
        // Fill employment history
        if (employmentHistory && employmentHistory.length > 0) {
            console.log('Processing employment history:', employmentHistory);
            
            // Clear existing employment rows except the first one
            const employmentRows = document.getElementById('employmentRows');
            if (employmentRows) {
                const rows = employmentRows.querySelectorAll('.employment-row');
                for (let i = 1; i < rows.length; i++) {
                    rows[i].remove();
                }
                
                // Fill employment data
                employmentHistory.forEach((employment, index) => {
                    if (index === 0) {
                        // Fill first row
                        fillEmploymentRow(0, employment);
                    } else {
                        // Add new row with data
                        addEmploymentRowWithData(employment, index);
                    }
                });
            }
        }
        
        console.log('Form filling completed successfully');
        
    } catch (error) {
        console.error('Error filling form with lead data:', error);
        showAlert('Error filling form: ' + error.message, 'error');
    }
}

// Handle dropdown auto-fill with cascading logic
function handleDropdownAutoFill(leadData) {
    console.log('Handling dropdown auto-fill:', leadData);
    
    const countrySelect = document.getElementById('country');
    const citySelect = document.getElementById('state');
    const collegeSelect = document.getElementById('college');
    const courseSelect = document.getElementById('course');
    
    // Auto-fill country first
    if (leadData.country && countrySelect) {
        if (setDropdownValue(countrySelect, leadData.country)) {
            console.log('Country set successfully:', leadData.country);
            
            // Wait for cities to load, then set city
            if (leadData.city) {
                setTimeout(() => {
                    if (setDropdownValue(citySelect, leadData.city)) {
                        console.log('City set successfully:', leadData.city);
                        
                        // Wait for colleges to load, then set college
                        if (leadData.college) {
                            setTimeout(() => {
                                if (setDropdownValue(collegeSelect, leadData.college)) {
                                    console.log('College set successfully:', leadData.college);
                                    
                                    // Wait for courses to load, then set course
                                    if (leadData.course) {
                                        setTimeout(() => {
                                            if (setDropdownValue(courseSelect, leadData.course)) {
                                                console.log('Course set successfully:', leadData.course);
                                            } else {
                                                console.log('Course not found in dropdown:', leadData.course);
                                            }
                                        }, 1500);
                                    }
                                } else {
                                    console.log('College not found in dropdown:', leadData.college);
                                }
                            }, 1500);
                        }
                    } else {
                        console.log('City not found in dropdown:', leadData.city);
                    }
                }, 1500);
            }
        } else {
            console.log('Country not found in dropdown:', leadData.country);
        }
    }
}
function fillEmploymentRow(index, employment) {
    console.log(`Filling employment row ${index} with:`, employment);
    
    const fields = [
        { name: `employementhistory[${index}][join_date]`, value: employment.join_date },
        { name: `employementhistory[${index}][left_date]`, value: employment.left_date },
        { name: `employementhistory[${index}][company_name]`, value: employment.company_name },
        { name: `employementhistory[${index}][job_position]`, value: employment.job_position },
        { name: `employementhistory[${index}][job_city]`, value: employment.job_city }
    ];
    
    fields.forEach(({name, value}) => {
        const field = document.querySelector(`[name="${name}"]`);
        if (field && value) {
            field.value = value;
            console.log(`Filled ${name} with:`, value);
        }
    });
}

function addEmploymentRowWithData(employment, index) {
    console.log(`Adding employment row ${index} with data:`, employment);
    
    const employmentRows = document.getElementById('employmentRows');
    if (!employmentRows) {
        console.error('Employment rows container not found');
        return;
    }
    
    const newRowHTML = `
        <div class="employment-row bg-white rounded-xl p-6 shadow-sm border border-gray-100 transform translate-x-full opacity-0 transition-all duration-300" data-index="${index}">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Join Date</label>
                    <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${index}][join_date]" value="${employment.join_date || ''}">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">End Date</label>
                    <input type="date" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${index}][left_date]" value="${employment.left_date || ''}">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Company Name</label>
                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${index}][company_name]" placeholder="Company name" value="${employment.company_name || ''}">
                </div>
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Job Position</label>
                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${index}][job_position]" placeholder="Job title" value="${employment.job_position || ''}">
                </div>
            </div>
            
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">City/Country</label>
                    <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="employementhistory[${index}][job_city]" placeholder="Location" value="${employment.job_city || ''}">
                </div>
                <div class="flex-shrink-0">
                    <button type="button" class="remove-employment px-6 py-3 bg-red-500 text-white rounded-xl hover:bg-red-600 transition-colors flex items-center gap-2" title="Remove this work experience">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        <span class="hidden sm:inline">Remove</span>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    try {
        employmentRows.insertAdjacentHTML('beforeend', newRowHTML);
        
        // Animate the new row in
        const newRow = employmentRows.lastElementChild;
        if (newRow) {
            setTimeout(() => {
                newRow.classList.remove('translate-x-full', 'opacity-0');
            }, 10);
        }
        
        console.log(`Employment row ${index} added successfully with data`);
        
    } catch (error) {
        console.error('Error adding employment row with data:', error);
    }
}

// Document Checklist Functionality
function loadDocumentChecklist() {
    const country = document.getElementById('country')?.value;
    const qualification = document.querySelector('select[name="last_qual"]')?.value;
    
    console.log('Loading document checklist for:', { country, qualification });
    
    if (!country) {
        showEmptyChecklist();
        return;
    }
    
    showChecklistLoading();
    
    // Make API call to get document checklist
    fetch('{{ route("applications.get-document-checklist") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            country: country,
            qualification: qualification
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Document checklist response:', data);
        
        if (data.success && data.data) {
            displayDocumentChecklist(data.data);
        } else {
            showChecklistError(data.message || 'Failed to load document checklist');
        }
    })
    .catch(error => {
        console.error('Error loading document checklist:', error);
        showChecklistError('Error loading document checklist: ' + error.message);
    });
}

function showChecklistLoading() {
    document.getElementById('documentChecklistLoading').classList.remove('hidden');
    document.getElementById('documentChecklistEmpty').classList.add('hidden');
    document.getElementById('documentChecklistContent').classList.add('hidden');
}

function showEmptyChecklist() {
    document.getElementById('documentChecklistLoading').classList.add('hidden');
    document.getElementById('documentChecklistEmpty').classList.remove('hidden');
    document.getElementById('documentChecklistContent').classList.add('hidden');
}

function showChecklistError(message) {
    document.getElementById('documentChecklistLoading').classList.add('hidden');
    document.getElementById('documentChecklistEmpty').classList.remove('hidden');
    document.getElementById('documentChecklistContent').classList.add('hidden');
    
    const emptyDiv = document.getElementById('documentChecklistEmpty');
    emptyDiv.innerHTML = `
        <svg class="w-12 h-12 mx-auto mb-4 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-red-600">${message}</p>
    `;
}

function displayDocumentChecklist(documents) {
    console.log('Displaying documents:', documents);
    
    const content = document.getElementById('documentChecklistContent');
    const loading = document.getElementById('documentChecklistLoading');
    const empty = document.getElementById('documentChecklistEmpty');
    
    loading.classList.add('hidden');
    empty.classList.add('hidden');
    content.classList.remove('hidden');
    
    if (!Array.isArray(documents) || documents.length === 0) {
        content.innerHTML = '<p class="text-gray-500 text-center py-4">No document requirements found for the selected criteria.</p>';
        return;
    }
    
    let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
    
    documents.forEach((doc, index) => {
        const isMandatory = doc.mandatory === true || doc.mandatory === 'true' || doc.mandatory === 1;
        const mandatoryClass = isMandatory ? 'border-red-300 bg-red-50' : 'border-blue-200 bg-blue-50';
        const mandatoryIcon = isMandatory ? 
            '<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>' :
            '<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>';
        const mandatoryText = isMandatory ? 'Mandatory' : 'Optional';
        const mandatoryBadgeClass = isMandatory ? 'bg-red-100 text-red-800 border-red-200' : 'bg-blue-100 text-blue-800 border-blue-200';
        
        html += `
            <div class="border-2 rounded-xl p-4 ${mandatoryClass} transition-all hover:shadow-md">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center gap-2">
                        ${mandatoryIcon}
                        <h4 class="font-medium text-gray-900">${doc.document || doc.name || 'Document'}</h4>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border ${mandatoryBadgeClass}">
                        ${mandatoryText}
                    </span>
                </div>
                
                <div class="space-y-3">
                    <div class="relative">
                        <input type="file" 
                               name="documents[${index}][file]" 
                               class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 ${isMandatory ? 'required' : ''}"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                               ${isMandatory ? 'required' : ''}>
                        ${isMandatory ? '<div class="text-xs text-red-600 mt-1 font-medium">* This document is required</div>' : ''}
                    </div>
                    
                    <input type="hidden" name="documents[${index}][document_name]" value="${doc.document || doc.document_name || doc.name || 'Unknown Document'}">
                    <input type="hidden" name="documents[${index}][document_type]" value="${doc.document_type || doc.type || 'general'}">
                    <input type="hidden" name="documents[${index}][is_mandatory]" value="${doc.mandatory === 1 || doc.mandatory === '1' || doc.is_mandatory === '1' ? '1' : '0'}">
                    
                    <div class="text-xs text-gray-600">
                        <p>Accepted formats: PDF, JPG, PNG, DOC, DOCX</p>
                        <p>Maximum size: 10MB</p>
                    </div>
                </div>
            </div>
        `;
    });
    
    html += '</div>';
    content.innerHTML = html;
}

// Add event listeners for country and qualification changes
document.addEventListener('DOMContentLoaded', function() {
    // Listen for country changes
    const countryInput = document.querySelector('input[name="country"]');
    if (countryInput) {
        countryInput.addEventListener('blur', loadDocumentChecklist);
        countryInput.addEventListener('change', loadDocumentChecklist);
    }
    
    // Listen for qualification changes
    const qualificationSelect = document.querySelector('select[name="last_qual"]');
    if (qualificationSelect) {
        qualificationSelect.addEventListener('change', loadDocumentChecklist);
    }
    
    console.log('Document checklist event listeners added');
    
    // Add form submission validation - DISABLED FOR DEBUGGING
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            console.log('Form submission started...');
            
            // Completely disable client-side validation for debugging
            const skipClientValidation = true; // ALWAYS SKIP FOR NOW
            
            if (!skipClientValidation && !validateMandatoryDocuments()) {
                console.log('Client-side validation failed');
                e.preventDefault();
                return false;
            }
            
            console.log('Form submission proceeding without client-side validation...');
            // Let form submit normally
        });
    }
});

// Form validation function
function validateMandatoryDocuments() {
    console.log('Starting mandatory document validation...');
    
    const documentInputs = document.querySelectorAll('input[name^="documents["][name$="][file]"]');
    const mandatoryInputs = document.querySelectorAll('input[name^="documents["][name$="][is_mandatory]"][value="1"]');
    
    console.log('Found document inputs:', documentInputs.length);
    console.log('Found mandatory inputs:', mandatoryInputs.length);
    
    let missingDocuments = [];
    
    mandatoryInputs.forEach(function(mandatoryInput) {
        // Extract index from name attribute
        const match = mandatoryInput.name.match(/documents\[(\d+)\]/);
        if (match) {
            const index = match[1];
            const fileInput = document.querySelector(`input[name="documents[${index}][file]"]`);
            const documentNameInput = document.querySelector(`input[name="documents[${index}][document_name]"]`);
            
            console.log(`Checking mandatory document ${index}:`, {
                has_file_input: !!fileInput,
                has_files: fileInput ? fileInput.files.length : 0,
                document_name: documentNameInput ? documentNameInput.value : 'unknown'
            });
            
            if (fileInput && (!fileInput.files || fileInput.files.length === 0)) {
                const documentName = documentNameInput ? documentNameInput.value : `Document ${index}`;
                missingDocuments.push(documentName);
                console.log(`Missing mandatory document: ${documentName}`);
            }
        }
    });
    
    if (missingDocuments.length > 0) {
        console.log('Validation failed. Missing documents:', missingDocuments);
        showValidationError('Please upload the following mandatory documents:', missingDocuments);
        return false;
    }
    
    console.log('Validation passed!');
    return true;
}

function showValidationError(message, documents) {
    // Remove existing error messages
    const existingError = document.getElementById('document-validation-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Create error message
    const errorDiv = document.createElement('div');
    errorDiv.id = 'document-validation-error';
    errorDiv.className = 'fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50 max-w-md';
    
    let errorHTML = `
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <div>
                <strong class="font-medium">Validation Error</strong>
                <p class="text-sm mt-1">${message}</p>
                <ul class="text-sm mt-2 list-disc list-inside">
    `;
    
    documents.forEach(function(doc) {
        errorHTML += `<li>${doc}</li>`;
    });
    
    errorHTML += `
                </ul>
                <button onclick="this.parentElement.parentElement.parentElement.remove()" class="mt-2 text-xs text-red-600 hover:text-red-800 underline">
                    Dismiss
                </button>
            </div>
        </div>
    `;
    
    errorDiv.innerHTML = errorHTML;
    document.body.appendChild(errorDiv);
    
    // Auto-remove after 10 seconds
    setTimeout(() => {
        if (errorDiv && errorDiv.parentNode) {
            errorDiv.remove();
        }
    }, 10000);
    
    // Scroll to document checklist section
    const documentSection = document.getElementById('documentChecklistContainer');
    if (documentSection) {
        documentSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// Multiple course selection functionality
let selectedCoursesData = [];
let courseCounter = 0;

function handleMultipleCourseSelection() {
    try {
        const selectedCourses = localStorage.getItem('selectedCoursesForApplication');
        if (selectedCourses) {
            selectedCoursesData = JSON.parse(selectedCourses);
            console.log('Multiple courses loaded:', selectedCoursesData);
            
            if (selectedCoursesData.length > 0) {
                showMultipleCourseSection();
                displaySelectedCourses();
                
                // Pre-select the common country in the main form
                if (selectedCoursesData[0].country) {
                    const countryField = document.querySelector('input[name="country"]');
                    if (countryField) {
                        countryField.value = selectedCoursesData[0].country;
                        countryField.setAttribute('readonly', true);
                        countryField.style.backgroundColor = '#f3f4f6';
                    }
                }
                
                // Clear localStorage
                localStorage.removeItem('selectedCoursesForApplication');
            }
        }
    } catch (error) {
        console.error('Error handling multiple course selection:', error);
    }
}

function showMultipleCourseSection() {
    const banner = document.getElementById('multipleCourseBanner');
    const section = document.getElementById('multipleCourseSelection');
    const countSpan = document.getElementById('selectedCourseCount');
    
    if (banner && section && countSpan) {
        banner.classList.remove('hidden');
        section.classList.remove('hidden');
        countSpan.textContent = selectedCoursesData.length;
    }
}

function displaySelectedCourses() {
    const container = document.getElementById('selectedCoursesContainer');
    if (!container) return;
    
    container.innerHTML = '';
    
    selectedCoursesData.forEach((course, index) => {
        const courseDiv = createCourseOptionElement(course, index);
        container.appendChild(courseDiv);
    });
}

function createCourseOptionElement(courseData, index) {
    const div = document.createElement('div');
    div.className = 'bg-white/70 border border-gray-200 rounded-xl p-4';
    div.id = `course-option-${index}`;
    
    div.innerHTML = `
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-semibold text-gray-900">Course Option ${index + 1}</h4>
            <button onclick="removeCourseOption(${index})" class="text-red-600 hover:text-red-800 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                <input type="text" value="${courseData.country || ''}" readonly class="w-full px-3 py-2 bg-gray-100 border border-gray-200 rounded-lg text-sm cursor-not-allowed">
                <input type="hidden" name="course_options[${index}][country]" value="${courseData.country || ''}">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                <input type="text" value="${courseData.city || ''}" name="course_options[${index}][city]" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">College</label>
                <input type="text" value="${courseData.college || ''}" name="course_options[${index}][college]" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                <input type="text" value="${courseData.course || ''}" name="course_options[${index}][course]" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Course Type</label>
                <input type="text" value="${courseData.course_type || ''}" name="course_options[${index}][course_type]" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fees</label>
                <input type="text" value="${courseData.fees || ''}" name="course_options[${index}][fees]" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Duration</label>
                <input type="text" value="${courseData.duration || ''}" name="course_options[${index}][duration]" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
            </div>
            <div>
                <input type="hidden" name="course_options[${index}][college_detail_id]" value="${courseData.college_detail_id || ''}">
            </div>
        </div>
    `;
    
    return div;
}

function addNewCourseOption() {
    if (selectedCoursesData.length === 0) {
        showAlert('Please add at least one course option first', 'error');
        return;
    }
    
    const baseCountry = selectedCoursesData[0].country;
    const newCourse = {
        country: baseCountry,
        city: '',
        college: '',
        course: '',
        course_type: '',
        fees: '',
        duration: '',
        college_detail_id: ''
    };
    
    selectedCoursesData.push(newCourse);
    displaySelectedCourses();
    updateCourseCount();
    
    showAlert('New course option added', 'success');
}

function removeCourseOption(index) {
    if (selectedCoursesData.length <= 1) {
        showAlert('At least one course option is required', 'error');
        return;
    }
    
    selectedCoursesData.splice(index, 1);
    displaySelectedCourses();
    updateCourseCount();
    
    showAlert('Course option removed', 'info');
}

function updateCourseCount() {
    const countSpan = document.getElementById('selectedCourseCount');
    if (countSpan) {
        countSpan.textContent = selectedCoursesData.length;
    }
}

function hideMultipleCourseBanner() {
    const banner = document.getElementById('multipleCourseBanner');
    if (banner) {
        banner.classList.add('hidden');
    }
}

// Cascading Dropdown Functions
function initializeCascadingDropdowns() {
    console.log('Initializing cascading dropdowns');
    
    // Load countries on page load
    loadCountries();
    
    // Set up event listeners for cascading dropdowns
    const countrySelect = document.getElementById('country');
    const citySelect = document.getElementById('state');
    const collegeSelect = document.getElementById('college');
    const courseSelect = document.getElementById('course');
    const qualificationSelect = document.querySelector('select[name="last_qual"]');
    
    if (countrySelect) {
        countrySelect.addEventListener('change', function() {
            const selectedCountry = this.value;
            console.log('Country changed to:', selectedCountry);
            
            // Reset dependent dropdowns
            resetDropdown(citySelect, 'Select a city/province');
            resetDropdown(collegeSelect, 'Select a college');
            resetDropdown(courseSelect, 'Select a course');
            
            if (selectedCountry) {
                loadCities(selectedCountry);
                citySelect.disabled = false;
            } else {
                citySelect.disabled = true;
                collegeSelect.disabled = true;
                courseSelect.disabled = true;
            }
            
            // Trigger document checklist update
            loadDocumentChecklist();
        });
    }
    
    if (citySelect) {
        citySelect.addEventListener('change', function() {
            const selectedCity = this.value;
            const selectedCountry = countrySelect.value;
            console.log('City changed to:', selectedCity);
            
            // Reset dependent dropdowns
            resetDropdown(collegeSelect, 'Select a college');
            resetDropdown(courseSelect, 'Select a course');
            
            if (selectedCity && selectedCountry) {
                loadColleges(selectedCountry, selectedCity);
                collegeSelect.disabled = false;
            } else {
                collegeSelect.disabled = true;
                courseSelect.disabled = true;
            }
        });
    }
    
    if (collegeSelect) {
        collegeSelect.addEventListener('change', function() {
            const selectedCollege = this.value;
            const selectedCountry = countrySelect.value;
            const selectedCity = citySelect.value;
            console.log('College changed to:', selectedCollege);
            
            // Reset dependent dropdown
            resetDropdown(courseSelect, 'Select a course');
            
            if (selectedCollege && selectedCountry && selectedCity) {
                loadCourses(selectedCountry, selectedCity, selectedCollege);
                courseSelect.disabled = false;
            } else {
                courseSelect.disabled = true;
            }
        });
    }
    
    // Trigger document checklist on qualification change
    if (qualificationSelect) {
        qualificationSelect.addEventListener('change', function() {
            console.log('Qualification changed to:', this.value);
            loadDocumentChecklist();
        });
    }
}

function loadCountries() {
    console.log('Loading countries...');
    
    fetch('/api/dropdown/countries', {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Countries loaded:', data);
        
        if (Array.isArray(data) && data.length > 0) {
            populateDropdown(document.getElementById('country'), data, 'country_name', 'country_name', 'Select a country');
        } else {
            console.error('Failed to load countries: No data received');
            showAlert('Failed to load countries', 'error');
        }
    })
    .catch(error => {
        console.error('Error loading countries:', error);
        showAlert('Error loading countries: ' + error.message, 'error');
    });
}

function loadCities(country) {
    console.log('Loading cities for country:', country);
    
    const citySelect = document.getElementById('state');
    citySelect.innerHTML = '<option value="">Loading cities...</option>';
    
    fetch(`/api/dropdown/cities?country=${encodeURIComponent(country)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Cities loaded:', data);
        
        if (Array.isArray(data) && data.length > 0) {
            populateDropdown(citySelect, data, 'city_name', 'city_name', 'Select a city/province');
        } else {
            console.error('Failed to load cities: No data received');
            resetDropdown(citySelect, 'No cities available');
        }
    })
    .catch(error => {
        console.error('Error loading cities:', error);
        resetDropdown(citySelect, 'Error loading cities');
    });
}

function loadColleges(country, city) {
    console.log('Loading colleges for:', { country, city });
    
    const collegeSelect = document.getElementById('college');
    collegeSelect.innerHTML = '<option value="">Loading colleges...</option>';
    
    fetch(`/api/dropdown/colleges?country=${encodeURIComponent(country)}&city=${encodeURIComponent(city)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Colleges loaded:', data);
        
        if (Array.isArray(data) && data.length > 0) {
            populateDropdown(collegeSelect, data, 'college_name', 'college_name', 'Select a college');
        } else {
            console.error('Failed to load colleges: No data received');
            resetDropdown(collegeSelect, 'No colleges available');
        }
    })
    .catch(error => {
        console.error('Error loading colleges:', error);
        resetDropdown(collegeSelect, 'Error loading colleges');
    });
}

function loadCourses(country, city, college) {
    console.log('Loading courses for:', { country, city, college });
    
    const courseSelect = document.getElementById('course');
    courseSelect.innerHTML = '<option value="">Loading courses...</option>';
    
    fetch(`/api/dropdown/courses?country=${encodeURIComponent(country)}&city=${encodeURIComponent(city)}&college=${encodeURIComponent(college)}`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        console.log('Courses loaded:', data);
        
        if (Array.isArray(data) && data.length > 0) {
            populateDropdown(courseSelect, data, 'course_name', 'course_name', 'Select a course');
        } else {
            console.error('Failed to load courses: No data received');
            resetDropdown(courseSelect, 'No courses available');
        }
    })
    .catch(error => {
        console.error('Error loading courses:', error);
        resetDropdown(courseSelect, 'Error loading courses');
    });
}

function populateDropdown(selectElement, data, valueField, textField, placeholder) {
    if (!selectElement) return;
    
    selectElement.innerHTML = `<option value="">${placeholder}</option>`;
    
    if (Array.isArray(data) && data.length > 0) {
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueField];
            option.textContent = item[textField];
            selectElement.appendChild(option);
        });
    }
}

function resetDropdown(selectElement, placeholder) {
    if (!selectElement) return;
    
    selectElement.innerHTML = `<option value="">${placeholder}</option>`;
    selectElement.disabled = true;
}

// Helper function to set dropdown values (for auto-fill functionality)
function setDropdownValue(selectElement, value) {
    if (!selectElement || !value) return false;
    
    // Check if the option exists
    const option = Array.from(selectElement.options).find(opt => opt.value === value);
    if (option) {
        selectElement.value = value;
        // Trigger change event to cascade
        selectElement.dispatchEvent(new Event('change'));
        return true;
    }
    return false;
}
</script>
@endsection
