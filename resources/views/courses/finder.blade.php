@extends('layouts.app')

@section('page-title', 'Course Finder')

@section('content')
<div class="mx-auto">
    <!-- Page Header -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600/10 to-purple-600/10 p-6 border-b border-white/20">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Course Finder
                    </h1>
                    <p class="text-gray-600 mt-2">Find the perfect course based on your preferences and qualifications</p>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Selected Courses Counter -->
                    <div id="selectedCoursesCounter" class="hidden inline-flex items-center px-4 py-2 bg-indigo-100 text-indigo-800 rounded-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        <span id="selectedCount">0</span> Selected
                    </div>
                    
                    <!-- Proceed to Application Button -->
                    <button id="proceedToApplicationBtn" onclick="proceedToApplication()" class="hidden inline-flex items-center px-4 py-2 bg-indigo-600 text-white font-medium rounded-xl hover:bg-indigo-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Proceed to Application
                    </button>
                    
                    <button onclick="resetFilters()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white font-medium rounded-xl hover:bg-gray-700 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-900 flex items-center">
                <svg class="w-6 h-6 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                </svg>
                Search Filters
            </h2>
            <button onclick="toggleFilters()" class="md:hidden inline-flex items-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>

        <form id="courseFinderForm" class="space-y-6">
            <!-- Basic Search -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Search Courses</label>
                    <input type="text" name="search" placeholder="Search by course name, college, or keyword..." 
                           class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                </div>
                
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Country</label>
                    <select name="country" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                        <option value="">Select Country</option>
                        <option value="Canada">Canada</option>
                        <option value="USA">USA</option>
                        <option value="Australia">Australia</option>
                        <option value="U.K">United Kingdom</option>
                        <option value="New Zealand">New Zealand</option>
                        <option value="Germany">Germany</option>
                        <option value="Singapore">Singapore</option>
                        <option value="France">France</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Course Type</label>
                    <select name="course_type" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                        <option value="">Select Course Type</option>
                        <option value="Bachelor">Bachelor</option>
                        <option value="Masters">Masters</option>
                        <option value="Post Graduate Diploma">Post Graduate Diploma</option>
                        <option value="Diploma">Diploma</option>
                        <option value="Certificate">Certificate</option>
                    </select>
                </div>
            </div>

            <!-- Academic Fields -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Study Area</label>
                    <select name="study_area" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                        <option value="">Select Study Area</option>
                        <option value="Business & Management">Business & Management</option>
                        <option value="Computer & IT">Computer & IT</option>
                        <option value="Engineering">Engineering</option>
                        <option value="Science">Science</option>
                        <option value="Health">Health</option>
                        <option value="Education">Education</option>
                        <option value="Arts">Arts</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Discipline Area</label>
                    <select name="discipline_area" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                        <option value="">Select Discipline</option>
                        <option value="Management">Management</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Data Science & Analytics">Data Science & Analytics</option>
                        <option value="Finance">Finance</option>
                        <option value="Marketing">Marketing</option>
                        <option value="MBA">MBA</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Duration</label>
                    <select name="duration" class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                        <option value="">Select Duration</option>
                        <option value="1 Year">1 Year</option>
                        <option value="2 Years">2 Years</option>
                        <option value="3 Years">3 Years</option>
                        <option value="4 Years">4 Years</option>
                    </select>
                </div>
            </div>

            <!-- Advanced Filters (Collapsible) -->
            <div id="advancedFilters" class="space-y-4 border-t border-gray-200 pt-6">
                <button type="button" onclick="toggleAdvancedFilters()" class="flex items-center text-blue-600 hover:text-blue-700 font-medium">
                    <svg id="advancedFiltersIcon" class="w-5 h-5 mr-2 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    Advanced Filters
                </button>

                <div id="advancedFiltersContent" class="hidden space-y-4">
                    <!-- English Proficiency -->
                    <div class="bg-blue-50 rounded-xl p-4">
                        <h3 class="font-semibold text-gray-900 mb-3">English Proficiency Requirements</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">IELTS Min</label>
                                <input type="number" name="ielts_min" step="0.5" min="0" max="9" placeholder="6.0"
                                       class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">IELTS Max</label>
                                <input type="number" name="ielts_max" step="0.5" min="0" max="9" placeholder="7.0"
                                       class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">TOEFL Min</label>
                                <input type="number" name="toefl_min" min="0" max="120" placeholder="80"
                                       class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">PTE Min</label>
                                <input type="number" name="pte_min" min="0" max="90" placeholder="58"
                                       class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Student Profile -->
                    <div class="bg-green-50 rounded-xl p-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Student Profile</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Backlogs</label>
                                <select name="backlogs" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                                    <option value="">Any</option>
                                    <option value="0">No Backlogs</option>
                                    <option value="3">Up to 3</option>
                                    <option value="5">Up to 5</option>
                                    <option value="10">Up to 10</option>
                                    <option value="No Limit">No Limit</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Gap Years</label>
                                <select name="gap" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                                    <option value="">Any</option>
                                    <option value="No Gap">No Gap</option>
                                    <option value="2 Years">Up to 2 Years</option>
                                    <option value="5 Years">Up to 5 Years</option>
                                    <option value="No Limit">No Limit</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">12+3 Pattern</label>
                                <select name="twelve_plus_three" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                                    <option value="">Any</option>
                                    <option value="Accepted">Accepted</option>
                                    <option value="Not Accepted">Not Accepted</option>
                                    <option value="Not Applicable">Not Applicable</option>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Conditional Admission</label>
                                <select name="conditional_admission" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                                    <option value="">Any</option>
                                    <option value="Yes">Yes</option>
                                    <option value="No">No</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Financial -->
                    <div class="bg-yellow-50 rounded-xl p-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Financial Requirements</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Minimum Fees (USD)</label>
                                <input type="number" name="fees_min" min="0" placeholder="10000"
                                       class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Maximum Fees (USD)</label>
                                <input type="number" name="fees_max" min="0" placeholder="50000"
                                       class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Intake -->
                    <div class="bg-purple-50 rounded-xl p-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Intake Preferences</h3>
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Preferred Intake</label>
                            <select name="intake" class="w-full px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                                <option value="">Any Intake</option>
                                <option value="january">January</option>
                                <option value="february">February</option>
                                <option value="march">March</option>
                                <option value="april">April</option>
                                <option value="may">May</option>
                                <option value="june">June</option>
                                <option value="july">July</option>
                                <option value="august">August</option>
                                <option value="september">September</option>
                                <option value="october">October</option>
                                <option value="november">November</option>
                                <option value="december">December</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Button -->
            <div class="flex items-center justify-center pt-4">
                <button type="submit" class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-medium rounded-xl hover:from-blue-700 hover:to-purple-700 transition-all duration-200 shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Search Courses
                </button>
            </div>
        </form>
    </div>

    <!-- Results Section -->
    <div class="glass-effect rounded-2xl shadow-xl border border-white/20 p-6">
        <!-- Results Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <h2 class="text-xl font-bold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Search Results
                </h2>
                <span id="resultsCount" class="ml-3 px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                    0 courses found
                </span>
            </div>
            
            <div class="flex items-center space-x-3">
                <select id="resultsPerPage" class="px-3 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500/50 focus:border-transparent transition-all">
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                </select>
                
                <button onclick="exportResults()" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-medium rounded-xl hover:bg-green-700 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
                    </svg>
                    Export
                </button>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden text-center py-12">
            <svg class="animate-spin w-12 h-12 text-blue-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
            </svg>
            <p class="text-gray-600">Searching for courses...</p>
        </div>

        <!-- Results Table -->
        <div id="resultsTable" class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-white/20">
                        <th class="text-left py-4 px-2 text-sm font-semibold text-gray-700">Course</th>
                        <th class="text-left py-4 px-2 text-sm font-semibold text-gray-700">College</th>
                        <th class="text-left py-4 px-2 text-sm font-semibold text-gray-700">Location</th>
                        <th class="text-left py-4 px-2 text-sm font-semibold text-gray-700">Duration</th>
                        <th class="text-left py-4 px-2 text-sm font-semibold text-gray-700">Fees</th>
                        <th class="text-left py-4 px-2 text-sm font-semibold text-gray-700">Requirements</th>
                        <th class="text-left py-4 px-2 text-sm font-semibold text-gray-700">Intakes</th>
                        <th class="text-left py-4 px-2 text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody id="resultsTableBody" class="divide-y divide-white/20">
                    <!-- Results will be populated here -->
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
            <p class="text-gray-500 mb-4">No courses found matching your criteria</p>
            <p class="text-gray-400 text-sm">Try adjusting your filters or search terms</p>
        </div>

        <!-- Pagination -->
        <div id="pagination" class="flex items-center justify-between mt-6 pt-6 border-t border-white/20">
            <div class="text-sm text-gray-600">
                Showing <span id="showingFrom">0</span> to <span id="showingTo">0</span> of <span id="totalResults">0</span> results
            </div>
            <div class="flex items-center space-x-2" id="paginationButtons">
                <!-- Pagination buttons will be populated here -->
            </div>
        </div>
    </div>
</div>

<!-- Course Details Modal -->
<div id="courseModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4" style="z-index: 9999 !important;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold text-gray-900">Course Details</h3>
                <button onclick="closeCourseModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <div id="courseModalContent" class="p-6">
            <!-- Course details will be populated here -->
        </div>
    </div>
</div>

<!-- Success/Error Messages -->
<div id="alertContainer" class="fixed top-4 right-4" style="z-index: 9998 !important;"></div>

<script>
console.log('Course finder page script loading...');

let currentPage = 1;
let currentLimit = 10;
let currentResults = [];
let totalPages = 0;
let totalResults = 0;

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Set up form submission
    const form = document.getElementById('courseFinderForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            searchCourses();
        });
    }

    // Set up results per page change
    const resultsPerPage = document.getElementById('resultsPerPage');
    if (resultsPerPage) {
        resultsPerPage.addEventListener('change', function() {
            currentLimit = parseInt(this.value);
            currentPage = 1;
            searchCourses();
        });
    }

    // Load available filters
    loadFilters();

    // Initial empty state
    showEmptyState();
});

// Load available filters from the API
async function loadFilters() {
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const headers = {
            'Accept': 'application/json'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
        }
        
        const response = await fetch('{{ route("courses.filters") }}', {
            method: 'GET',
            headers: headers
        });
        
        const data = await response.json();
        
        if (data.status === 'success' && data.filters) {
            populateFilterOptions(data.filters);
        }
    } catch (error) {
        console.error('Error loading filters:', error);
        // Continue with static options if filter loading fails
    }
}

// Populate filter dropdowns with dynamic options
function populateFilterOptions(filters) {
    filters.forEach(filter => {
        if (filter.type === 'select' && filter.options) {
            const selectElement = document.querySelector(`select[name="${filter.name}"]`);
            if (selectElement) {
                // Keep the first "Select..." option
                const firstOption = selectElement.firstElementChild;
                selectElement.innerHTML = '';
                if (firstOption) {
                    selectElement.appendChild(firstOption);
                }
                
                // Add dynamic options
                filter.options.forEach(option => {
                    const optionElement = document.createElement('option');
                    optionElement.value = option;
                    optionElement.textContent = option;
                    selectElement.appendChild(optionElement);
                });
            }
        }
    });
}

// Search function
async function searchCourses() {
    showLoadingState();
    
    const formData = new FormData(document.getElementById('courseFinderForm'));
    const searchParams = {};
    
    // Convert FormData to object
    for (let [key, value] of formData.entries()) {
        if (value.trim() !== '') {
            searchParams[key] = value;
        }
    }
    
    // Add pagination
    searchParams.limit = currentLimit;
    searchParams.page = currentPage;
    
    console.log('Search parameters:', searchParams);
    
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        
        if (csrfToken) {
            headers['X-CSRF-TOKEN'] = csrfToken.getAttribute('content');
        }
        
        const response = await fetch('{{ route("courses.search") }}', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(searchParams)
        });
        
        const data = await response.json();
        console.log('API Response:', data);
        
        if (data.status === 'success') {
            currentResults = data.data;
            totalResults = data.total;
            totalPages = data.total_pages;
            
            displayResults(data.data);
            updatePagination();
            updateResultsCount();
        } else {
            showAlert(data.message || 'Error searching courses', 'error');
            showEmptyState();
        }
    } catch (error) {
        console.error('Error:', error);
        showAlert('Error connecting to course database', 'error');
        showEmptyState();
    }
}

// Display results in table
function displayResults(courses) {
    const tbody = document.getElementById('resultsTableBody');
    const emptyState = document.getElementById('emptyState');
    const resultsTable = document.getElementById('resultsTable');
    const loadingState = document.getElementById('loadingState');
    
    // Hide loading and empty states
    loadingState.classList.add('hidden');
    emptyState.classList.add('hidden');
    
    if (courses.length === 0) {
        showEmptyState();
        return;
    }
    
    // Show results table
    resultsTable.classList.remove('hidden');
    
    // Clear existing results
    tbody.innerHTML = '';
    
    courses.forEach(course => {
        const row = createCourseRow(course);
        tbody.appendChild(row);
    });
    
    // Update button states for selected courses
    updateCourseRowButtons();
}

// Create course row
function createCourseRow(course) {
    const row = document.createElement('tr');
    row.className = 'hover:bg-white/10 transition-colors';
    
    const intakes = getAvailableIntakes(course);
    const requirements = getRequirements(course);
    
    row.innerHTML = `
        <td class="py-4 px-2">
            <div>
                <div class="font-semibold text-gray-900">${course.course}</div>
                <div class="text-sm text-gray-600">${course.course_type}</div>
                <div class="text-xs text-gray-500">${course.study_area}</div>
            </div>
        </td>
        <td class="py-4 px-2">
            <div>
                <div class="font-medium text-gray-900">${course.college}</div>
                <div class="text-sm text-gray-600">${course.city}, ${course.country}</div>
            </div>
        </td>
        <td class="py-4 px-2">
            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                ${course.city}
            </span>
        </td>
        <td class="py-4 px-2">
            <span class="font-medium text-gray-900">${course.duration}</span>
        </td>
        <td class="py-4 px-2">
            <div class="font-semibold text-green-600">${course.fees}</div>
            <div class="text-xs text-gray-500">Application: ${course.app_fees}</div>
        </td>
        <td class="py-4 px-2">
            <div class="space-y-1">
                ${requirements.map(req => `<div class="text-xs text-gray-600">${req}</div>`).join('')}
            </div>
        </td>
        <td class="py-4 px-2">
            <div class="flex flex-wrap gap-1">
                ${intakes.map(intake => `
                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded bg-purple-100 text-purple-800">
                        ${intake}
                    </span>
                `).join('')}
            </div>
        </td>
        <td class="py-4 px-2">
            <div class="flex space-x-2">
                <button onclick="viewCourseDetails(${course.college_detail_id})" 
                        class="inline-flex items-center px-3 py-1 bg-blue-600 text-white text-xs rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                </button>
                <button onclick="shortlistCourse(${course.college_detail_id})" 
                        class="inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs rounded-lg hover:bg-green-700 transition-colors">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    Save
                </button>
                <button onclick="selectForLead(${course.college_detail_id})" 
                        class="inline-flex items-center px-3 py-1 bg-purple-600 text-white text-xs rounded-lg hover:bg-purple-700 transition-colors">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    Select for Lead
                </button>
                <button onclick="addToApplicationSelection(${course.college_detail_id})" 
                        class="inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-xs rounded-lg hover:bg-indigo-700 transition-colors">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add to Application
                </button>
            </div>
        </td>
    `;
    
    return row;
}

// Get available intakes
function getAvailableIntakes(course) {
    const intakes = [];
    const intakeMapping = {
        'intake_jan': 'Jan',
        'intake_feb': 'Feb',
        'intake_march': 'Mar',
        'intake_april': 'Apr',
        'intake_may': 'May',
        'intake_june': 'Jun',
        'intake_jul': 'Jul',
        'intake_aug': 'Aug',
        'intake_sep': 'Sep',
        'intake_oct': 'Oct',
        'intake_nov': 'Nov',
        'intake_dec': 'Dec'
    };
    
    Object.keys(intakeMapping).forEach(key => {
        if (course[key] == 1) {
            intakes.push(intakeMapping[key]);
        }
    });
    
    return intakes.length > 0 ? intakes : ['Contact College'];
}

// Get requirements
function getRequirements(course) {
    const requirements = [];
    
    if (course.ielts_min) {
        requirements.push(`IELTS: ${course.ielts_min} - ${course.ielts_max || course.ielts_min}`);
    }
    if (course.min_percent) {
        requirements.push(`Min %: ${course.min_percent}%`);
    }
    if (course.backlogs && course.backlogs !== 'Not Applicable') {
        requirements.push(`Backlogs: ${course.backlogs}`);
    }
    
    return requirements.length > 0 ? requirements : ['Contact for details'];
}

// View course details
function viewCourseDetails(courseId) {
    const course = currentResults.find(c => c.college_detail_id == courseId);
    if (!course) return;
    
    const modalContent = document.getElementById('courseModalContent');
    modalContent.innerHTML = createCourseDetailsHTML(course);
    
    document.getElementById('courseModal').classList.remove('hidden');
}

// Create course details HTML
function createCourseDetailsHTML(course) {
    const intakes = getAvailableIntakes(course);
    
    return `
        <div class="space-y-6">
            <!-- Course Header -->
            <div class="border-b border-gray-200 pb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">${course.course}</h2>
                <div class="flex items-center space-x-4 text-sm text-gray-600">
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h4m6 0h2M7 15h1m3 0h1"></path>
                        </svg>
                        ${course.college}
                    </span>
                    <span class="inline-flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        ${course.city}, ${course.country}
                    </span>
                </div>
            </div>

            <!-- Course Info Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="bg-blue-50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Course Information</h3>
                    <div class="space-y-2 text-sm">
                        <div><span class="font-medium">Type:</span> ${course.course_type}</div>
                        <div><span class="font-medium">Duration:</span> ${course.duration}</div>
                        <div><span class="font-medium">Study Area:</span> ${course.study_area}</div>
                        <div><span class="font-medium">Discipline:</span> ${course.discipline_area}</div>
                        <div><span class="font-medium">STEM Program:</span> ${course.stem_program}</div>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="bg-green-50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Financial Information</h3>
                    <div class="space-y-2 text-sm">
                        <div><span class="font-medium">Tuition Fees:</span> ${course.fees}</div>
                        <div><span class="font-medium">Application Fees:</span> ${course.app_fees}</div>
                        ${course.scholarship ? `<div><span class="font-medium">Scholarship:</span> ${course.scholarship}</div>` : ''}
                    </div>
                </div>

                <!-- Admission Requirements -->
                <div class="bg-yellow-50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Admission Requirements</h3>
                    <div class="space-y-2 text-sm">
                        <div><span class="font-medium">Min Percentage:</span> ${course.min_percent}%</div>
                        <div><span class="font-medium">Last Qualification:</span> ${course.last_qualification}</div>
                        ${course.ielts_min ? `<div><span class="font-medium">IELTS:</span> ${course.ielts_min} - ${course.ielts_max}</div>` : ''}
                        ${course.toefl_overall ? `<div><span class="font-medium">TOEFL:</span> ${course.toefl_overall}</div>` : ''}
                        ${course.pte_overall ? `<div><span class="font-medium">PTE:</span> ${course.pte_overall}</div>` : ''}
                        <div><span class="font-medium">Backlogs:</span> ${course.backlogs}</div>
                        <div><span class="font-medium">Gap:</span> ${course.gap}</div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="bg-purple-50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Additional Information</h3>
                    <div class="space-y-2 text-sm">
                        <div><span class="font-medium">Conditional Admission:</span> ${course.conditional_admission}</div>
                        <div><span class="font-medium">12+3 Pattern:</span> ${course.twelve_plus_three}</div>
                        <div><span class="font-medium">Without English:</span> ${course.without_english_proficiency}</div>
                        <div><span class="font-medium">Processing Time:</span> ${course.ol_tat} ${course.ol_tat_type}</div>
                        <div><span class="font-medium">Application Deadline:</span> ${course.application_deadline}</div>
                    </div>
                </div>
            </div>

            <!-- Available Intakes -->
            <div class="bg-gray-50 rounded-xl p-4">
                <h3 class="font-semibold text-gray-900 mb-3">Available Intakes</h3>
                <div class="flex flex-wrap gap-2">
                    ${intakes.map(intake => `
                        <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-purple-100 text-purple-800">
                            ${intake}
                        </span>
                    `).join('')}
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-indigo-50 rounded-xl p-4">
                <h3 class="font-semibold text-gray-900 mb-3">Contact Information</h3>
                <div class="space-y-2 text-sm">
                    <div><span class="font-medium">Contact Person:</span> ${course.c_person}</div>
                    <div><span class="font-medium">Email:</span> ${course.email1}</div>
                    ${course.email2 ? `<div><span class="font-medium">Email 2:</span> ${course.email2}</div>` : ''}
                    <div><span class="font-medium">Website:</span> <a href="${course.web_address}" target="_blank" class="text-blue-600 hover:underline">${course.web_address}</a></div>
                    <div><span class="font-medium">Address:</span> ${course.post_address}</div>
                </div>
            </div>

            <!-- Course Description -->
            ${course.course_description ? `
                <div class="bg-teal-50 rounded-xl p-4">
                    <h3 class="font-semibold text-gray-900 mb-3">Course Description</h3>
                    <a href="${course.course_description}" target="_blank" class="text-blue-600 hover:underline text-sm">
                        View Course Details
                    </a>
                </div>
            ` : ''}

            <!-- Action Buttons -->
            <div class="space-y-4 pt-4 border-t border-gray-200">
                <div class="flex space-x-4">
                    <button onclick="shortlistCourse(${course.college_detail_id})" class="flex-1 bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition-colors font-medium">
                        Shortlist Course
                    </button>
                    <button onclick="contactCollege('${course.email1}')" class="flex-1 bg-blue-600 text-white py-3 rounded-xl hover:bg-blue-700 transition-colors font-medium">
                        Contact College
                    </button>
                </div>
                <div class="flex space-x-4">
                    <button onclick="selectForLead(${course.college_detail_id}); closeCourseModal();" class="flex-1 bg-purple-600 text-white py-3 rounded-xl hover:bg-purple-700 transition-colors font-medium">
                        Select for Lead
                    </button>
                    <button onclick="addToApplicationSelection(${course.college_detail_id}); closeCourseModal();" class="flex-1 bg-indigo-600 text-white py-3 rounded-xl hover:bg-indigo-700 transition-colors font-medium">
                        Add to Application
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Close course modal
function closeCourseModal() {
    document.getElementById('courseModal').classList.add('hidden');
}

// Shortlist course
function shortlistCourse(courseId) {
    // Implementation for shortlisting
    showAlert('Course added to shortlist!', 'success');
}

// Contact college
function contactCollege(email) {
    window.open(`mailto:${email}`, '_blank');
}

// Show loading state
function showLoadingState() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('resultsTable').classList.add('hidden');
    document.getElementById('emptyState').classList.add('hidden');
}

// Show empty state
function showEmptyState() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('resultsTable').classList.add('hidden');
    document.getElementById('emptyState').classList.remove('hidden');
}

// Update results count
function updateResultsCount() {
    document.getElementById('resultsCount').textContent = `${totalResults} courses found`;
    
    const from = (currentPage - 1) * currentLimit + 1;
    const to = Math.min(currentPage * currentLimit, totalResults);
    
    document.getElementById('showingFrom').textContent = from;
    document.getElementById('showingTo').textContent = to;
    document.getElementById('totalResults').textContent = totalResults;
}

// Update pagination
function updatePagination() {
    const paginationButtons = document.getElementById('paginationButtons');
    paginationButtons.innerHTML = '';
    
    // Previous button
    const prevButton = document.createElement('button');
    prevButton.className = `px-3 py-2 text-sm font-medium rounded-lg transition-colors ${
        currentPage === 1 
            ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
            : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
    }`;
    prevButton.textContent = 'Previous';
    prevButton.disabled = currentPage === 1;
    prevButton.onclick = () => {
        if (currentPage > 1) {
            currentPage--;
            searchCourses();
        }
    };
    paginationButtons.appendChild(prevButton);
    
    // Page numbers
    const maxVisiblePages = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    for (let page = startPage; page <= endPage; page++) {
        const pageButton = document.createElement('button');
        pageButton.className = `px-3 py-2 text-sm font-medium rounded-lg transition-colors ${
            page === currentPage
                ? 'bg-blue-600 text-white'
                : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
        }`;
        pageButton.textContent = page;
        pageButton.onclick = () => {
            currentPage = page;
            searchCourses();
        };
        paginationButtons.appendChild(pageButton);
    }
    
    // Next button
    const nextButton = document.createElement('button');
    nextButton.className = `px-3 py-2 text-sm font-medium rounded-lg transition-colors ${
        currentPage === totalPages 
            ? 'bg-gray-100 text-gray-400 cursor-not-allowed' 
            : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300'
    }`;
    nextButton.textContent = 'Next';
    nextButton.disabled = currentPage === totalPages;
    nextButton.onclick = () => {
        if (currentPage < totalPages) {
            currentPage++;
            searchCourses();
        }
    };
    paginationButtons.appendChild(nextButton);
}

// Toggle advanced filters
function toggleAdvancedFilters() {
    const content = document.getElementById('advancedFiltersContent');
    const icon = document.getElementById('advancedFiltersIcon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}

// Reset filters
function resetFilters() {
    document.getElementById('courseFinderForm').reset();
    currentPage = 1;
    showEmptyState();
    document.getElementById('resultsCount').textContent = '0 courses found';
}

// Export results
function exportResults() {
    if (currentResults.length === 0) {
        showAlert('No results to export', 'error');
        return;
    }
    
    showAlert('Export functionality coming soon!', 'info');
}

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

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    if (e.target.id === 'courseModal') {
        closeCourseModal();
    }
});

// Select course for lead creation
function selectForLead(courseId) {
    // Find the course in current results
    const course = currentResults.find(c => c.college_detail_id == courseId);
    if (!course) {
        showAlert('Course data not found', 'error');
        return;
    }
    
    // Store course data in localStorage for the lead creation page
    const courseData = {
        country: course.country || '',
        city: course.city || course.state || '',
        college: course.college || '',
        course: course.course || '',
        // Additional data that might be useful
        course_type: course.course_type || '',
        study_area: course.study_area || '',
        college_detail_id: course.college_detail_id || ''
    };
    
    localStorage.setItem('selectedCourse', JSON.stringify(courseData));
    
    // Show success message
    showAlert('Course selected! Redirecting to lead creation...', 'success');
    
    // Redirect to lead creation page after a short delay
    setTimeout(() => {
        window.location.href = '{{ route("leads.create") }}?from=course-finder';
    }, 1500);
}

// Select course for application creation
function selectForApplication(courseId) {
    // Find the course in current results
    const course = currentResults.find(c => c.college_detail_id == courseId);
    if (!course) {
        showAlert('Course data not found', 'error');
        return;
    }
    
    // Store course data in localStorage for the application creation page
    const courseData = {
        country: course.country || '',
        city: course.city || course.state || '',
        college: course.college || '',
        course: course.course || '',
        // Additional data that might be useful
        course_type: course.course_type || '',
        study_area: course.study_area || '',
        college_detail_id: course.college_detail_id || ''
    };
    
    localStorage.setItem('selectedCourseForApplication', JSON.stringify(courseData));
    
    // Show success message
    showAlert('Course selected! Redirecting to application form...', 'success');
    
    // Redirect to application creation page after a short delay
    setTimeout(() => {
        window.location.href = '{{ route("applications.create") }}?from=course-finder';
    }, 1500);
}

// Multiple course selection for applications
let selectedCoursesForApplication = [];

// Add course to application selection
function addToApplicationSelection(courseId) {
    const course = currentResults.find(c => c.college_detail_id == courseId);
    if (!course) {
        showAlert('Course data not found', 'error');
        return;
    }
    
    // Check if course is already selected
    const isAlreadySelected = selectedCoursesForApplication.some(c => c.college_detail_id == courseId);
    if (isAlreadySelected) {
        showAlert('Course already added to selection', 'info');
        return;
    }
    
    // Validate country consistency (all courses must be from same country)
    if (selectedCoursesForApplication.length > 0) {
        const firstCourseCountry = selectedCoursesForApplication[0].country;
        if (course.country !== firstCourseCountry) {
            showAlert(`All courses must be from the same country. First selected: ${firstCourseCountry}`, 'error');
            return;
        }
    }
    
    // Add course to selection
    const courseData = {
        country: course.country || '',
        city: course.city || course.state || '',
        college: course.college || '',
        course: course.course || '',
        course_type: course.course_type || '',
        study_area: course.study_area || '',
        college_detail_id: course.college_detail_id || '',
        fees: course.fees || '',
        duration: course.duration || '',
        intake_year: '', // Users can select this in the application form
        intake_month: ''  // Users can select this in the application form
    };
    
    selectedCoursesForApplication.push(courseData);
    updateSelectionUI();
    showAlert(`${course.course} added to application selection`, 'success');
}

// Remove course from application selection
function removeFromApplicationSelection(courseId) {
    selectedCoursesForApplication = selectedCoursesForApplication.filter(c => c.college_detail_id != courseId);
    updateSelectionUI();
    showAlert('Course removed from selection', 'info');
}

// Update the selection UI
function updateSelectionUI() {
    const counter = document.getElementById('selectedCoursesCounter');
    const countSpan = document.getElementById('selectedCount');
    const proceedBtn = document.getElementById('proceedToApplicationBtn');
    
    const count = selectedCoursesForApplication.length;
    
    if (count > 0) {
        counter.classList.remove('hidden');
        proceedBtn.classList.remove('hidden');
        countSpan.textContent = count;
    } else {
        counter.classList.add('hidden');
        proceedBtn.classList.add('hidden');
    }
    
    // Update button states in the table
    updateCourseRowButtons();
}

// Update course row buttons to show selection state
function updateCourseRowButtons() {
    const rows = document.querySelectorAll('#resultsTableBody tr');
    rows.forEach(row => {
        const buttons = row.querySelectorAll('button[onclick*="addToApplicationSelection"]');
        buttons.forEach(button => {
            const onclickAttr = button.getAttribute('onclick');
            const courseId = onclickAttr.match(/addToApplicationSelection\((\d+)\)/)?.[1];
            
            if (courseId) {
                const isSelected = selectedCoursesForApplication.some(c => c.college_detail_id == courseId);
                
                if (isSelected) {
                    button.innerHTML = `
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Added
                    `;
                    button.className = "inline-flex items-center px-3 py-1 bg-green-600 text-white text-xs rounded-lg transition-colors";
                    button.setAttribute('onclick', `removeFromApplicationSelection(${courseId})`);
                } else {
                    button.innerHTML = `
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Add to Application
                    `;
                    button.className = "inline-flex items-center px-3 py-1 bg-indigo-600 text-white text-xs rounded-lg hover:bg-indigo-700 transition-colors";
                    button.setAttribute('onclick', `addToApplicationSelection(${courseId})`);
                }
            }
        });
    });
}

// Proceed to application form with multiple courses
function proceedToApplication() {
    if (selectedCoursesForApplication.length === 0) {
        showAlert('Please select at least one course', 'error');
        return;
    }
    
    // Store multiple courses data in localStorage
    localStorage.setItem('selectedCoursesForApplication', JSON.stringify(selectedCoursesForApplication));
    
    // Show success message
    showAlert(`Proceeding to application with ${selectedCoursesForApplication.length} courses...`, 'success');
    
    // Redirect to application creation page
    setTimeout(() => {
        window.location.href = '{{ route("applications.create") }}?from=course-finder&multiple=true';
    }, 1500);
}

console.log('Course finder page script fully loaded');
</script>
@endsection
