@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New Lead</h1>
                    <p class="text-gray-600 mt-2">Create a new lead to track potential visa applicants</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('courses.finder') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Course Finder
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

        <!-- Course Finder Auto-fill Banner (initially hidden) -->
        <div id="courseFinderBanner" class="hidden mb-6 bg-gradient-to-r from-purple-100 to-blue-100 border border-purple-200 rounded-xl p-4">
            <div class="flex items-center">
                <svg class="w-6 h-6 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h4 class="font-semibold text-purple-800">Course Details Auto-filled</h4>
                    <p class="text-purple-700 text-sm">The highlighted fields have been automatically filled from your course selection. You can modify them if needed.</p>
                </div>
                <button onclick="hideCourseFinderBanner()" class="ml-auto text-purple-600 hover:text-purple-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white/70 backdrop-blur-sm rounded-2xl shadow-xl border border-white/20 overflow-hidden">
            <div class="p-8">
                    <form id="lead_form" action="{{ route('leads.store') }}" method="POST">
                        @csrf
                        
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
                            <input type="text" name="country" id="country" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" placeholder="Enter preferred country" required>
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Preferred Province/City</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" id="state" name="city" placeholder="Enter preferred city/province">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Preferred College</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" id="college" name="college" placeholder="Enter preferred college">
                        </div>
                        
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Preferred Course</label>
                            <input type="text" class="w-full px-4 py-3 bg-white/50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent backdrop-blur-sm transition-all" name="course" placeholder="Enter course name">
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
                <!-- Remarks -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                        <div class="w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center text-white text-sm font-bold mr-3">8</div>
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
                        Save Lead
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
    
    // Check if coming from course finder and auto-fill form
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('from') === 'course-finder') {
        autoFillFromCourseSelection();
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
                Saving Lead...
            `;
            
            fetch('{{ route("leads.store") }}', {
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
                                showAlert('Lead created successfully!', 'success');
                                setTimeout(() => {
                                    window.location.href = '{{ route("leads.index") }}';
                                }, 1500);
                            } else {
                                throw new Error(data.message || 'An error occurred');
                            }
                        });
                    } else {
                        // Redirect response - success
                        showAlert('Lead created successfully!', 'success');
                        setTimeout(() => {
                            window.location.href = '{{ route("leads.index") }}';
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
                showAlert('Error creating lead: ' + error.message, 'error');
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

// Auto-fill form from course selection
function autoFillFromCourseSelection() {
    try {
        const selectedCourse = localStorage.getItem('selectedCourse');
        if (selectedCourse) {
            const courseData = JSON.parse(selectedCourse);
            console.log('Auto-filling form with course data:', courseData);
            
            // Show the banner
            const banner = document.getElementById('courseFinderBanner');
            if (banner) {
                banner.classList.remove('hidden');
            }
            
            // Fill the form fields
            if (courseData.country) {
                const countryField = document.getElementById('country');
                if (countryField) {
                    countryField.value = courseData.country;
                    countryField.classList.add('bg-yellow-50', 'border-yellow-300');
                }
            }
            
            if (courseData.city) {
                const cityField = document.getElementById('state');
                if (cityField) {
                    cityField.value = courseData.city;
                    cityField.classList.add('bg-yellow-50', 'border-yellow-300');
                }
            }
            
            if (courseData.college) {
                const collegeField = document.getElementById('college');
                if (collegeField) {
                    collegeField.value = courseData.college;
                    collegeField.classList.add('bg-yellow-50', 'border-yellow-300');
                }
            }
            
            if (courseData.course) {
                const courseField = document.querySelector('input[name="course"]');
                if (courseField) {
                    courseField.value = courseData.course;
                    courseField.classList.add('bg-yellow-50', 'border-yellow-300');
                }
            }
            
            // Show success message
            showAlert('Course details have been auto-filled from Course Finder!', 'success');
            
            // Clear the stored course data
            localStorage.removeItem('selectedCourse');
            
            // Scroll to the top of the form to show the filled fields
            document.querySelector('.max-w-6xl').scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    } catch (error) {
        console.error('Error auto-filling course data:', error);
        localStorage.removeItem('selectedCourse'); // Clean up on error
    }
}

// Hide course finder banner
function hideCourseFinderBanner() {
    const banner = document.getElementById('courseFinderBanner');
    if (banner) {
        banner.classList.add('hidden');
    }
}
</script>
@endsection
