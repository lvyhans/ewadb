<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\FollowupController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CourseFinderController;
use App\Http\Controllers\TaskManagementController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Test route for debugging application form
Route::get('/test-application-form', function () {
    return view('test_application_form');
})->middleware('auth')->name('test.application.form');

// Registration Routes (for guest users only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/registration-success', [RegisterController::class, 'registrationSuccess'])->name('registration.success');
});

// Admin Registration Routes (for authenticated super admin only)
Route::middleware('auth')->group(function () {
    Route::get('/admin/register', [RegisterController::class, 'showAdminRegistrationForm'])->name('admin.register.form');
    Route::post('/admin/register', [RegisterController::class, 'registerAdmin'])->name('admin.register');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead'])->name('read');
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-read');
        Route::delete('/{id}', [NotificationController::class, 'destroy'])->name('destroy');
    });
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    // Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy'); // DISABLED: Account deletion disabled for all users
    
    // User Management Routes with Admin Hierarchy
    Route::resource('users', UserController::class)->middleware('admin.hierarchy');
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status')->middleware('admin.hierarchy');
    
    // Settings Management Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');
    Route::delete('/settings/{setting}', [SettingsController::class, 'destroy'])->name('settings.destroy');
    Route::post('/settings/initialize', [SettingsController::class, 'initializeDefaults'])->name('settings.initialize');
    Route::post('/settings/clear-cache', [SettingsController::class, 'clearCache'])->name('settings.clear-cache');
    
    // Lead Management Web Routes
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [LeadController::class, 'webIndex'])->name('index');
        Route::get('/create', function () {
            return view('leads.create');
        })->name('create');
        Route::post('/store', [LeadController::class, 'store'])->name('store');
        Route::get('/{id}', [LeadController::class, 'webShow'])->name('show');
        Route::get('/{id}/edit', function ($id) {
            return view('leads.edit', compact('id'));
        })->name('edit');
        
        // Follow-up Routes for a specific lead
        Route::post('/{lead}/followups', [FollowupController::class, 'store'])->name('followups.store');
        Route::put('/{lead}/followups/{followup}', [FollowupController::class, 'update'])->name('followups.update');
        Route::delete('/{lead}/followups/{followup}', [FollowupController::class, 'destroy'])->name('followups.destroy');

        // External API test route
        Route::get('/test-external-api', [LeadController::class, 'testExternalApi'])->name('test-external-api');
    });
    
    // Application Management Routes
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [ApplicationController::class, 'index'])->name('index');
        Route::get('/create', [ApplicationController::class, 'create'])->name('create');
        Route::post('/store', [ApplicationController::class, 'store'])->name('store');
        
        // AJAX endpoint for getting lead data - MUST BE BEFORE {id} routes to avoid conflicts
        Route::match(['get', 'post'], '/get-lead-data', [ApplicationController::class, 'getLeadData'])->name('get-lead-data');
        
        // AJAX endpoint for getting document checklist
        Route::post('/get-document-checklist', [ApplicationController::class, 'getDocumentChecklist'])->name('get-document-checklist');
        
        // Debug routes
        Route::post('/debug-submission', [ApplicationController::class, 'debugApplicationSubmission'])->name('debug-submission');
        Route::post('/test-simple-creation', [ApplicationController::class, 'testSimpleApplicationCreation'])->name('test-simple-creation');
        
        // External API test route
        Route::get('/test-external-api', [ApplicationController::class, 'testExternalApiApplication'])->name('test-external-api');
        
        // Test application creation
        Route::get('/test-creation', [ApplicationController::class, 'testApplicationCreation'])->name('test-creation');
        
        // Test API payload for specific application
        Route::get('/{id}/test-api-payload', [ApplicationController::class, 'testApplicationApiPayload'])->name('test-api-payload');
        
        // Document management routes
        Route::get('/{id}/documents', [ApplicationController::class, 'getDocuments'])->name('documents');
        Route::get('/{id}/documents/download-all', [ApplicationController::class, 'downloadAllDocuments'])->name('documents.download-all');
        Route::get('/documents/{id}/view', [ApplicationController::class, 'viewDocument'])->name('documents.view');
        Route::get('/documents/{id}/download', [ApplicationController::class, 'downloadDocument'])->name('documents.download');
        
        // API application view - single detailed view only
        Route::get('/api/{visa_form_id}', [ApplicationController::class, 'showApiApplicationDetails'])->name('show-api');
        Route::get('/api/{visa_form_id}/details-json', [ApplicationController::class, 'getApplicationDetails'])->name('api-details-json');
        
        // Test routes for API responses
        Route::get('/test-application-details', function() {
            return response()->json([
                "status" => "success",
                "form_id" => 388,
                "admissions_count" => 4,
                "documents_count" => 5,
                "admissions" => [
                    [
                        "admissions_id" => 191,
                        "branch" => "Uttrakhand",
                        "country" => "USA",
                        "city" => "Mohali",
                        "institute" => "test",
                        "intake_year" => "2025",
                        "intake_month" => "Sept",
                        "created" => "2025-07-09 12:44:25"
                    ],
                    [
                        "admissions_id" => 189,
                        "branch" => "Uttrakhand",
                        "country" => "U.K",
                        "city" => "Mohali",
                        "institute" => "test",
                        "intake_year" => "2025",
                        "intake_month" => "Sept",
                        "created" => "2025-07-07 12:19:46"
                    ],
                    [
                        "admissions_id" => 188,
                        "branch" => "Uttrakhand",
                        "country" => "U.K",
                        "city" => "test",
                        "institute" => "test",
                        "intake_year" => "2024",
                        "intake_month" => "Feb",
                        "created" => "2025-07-03 15:59:58"
                    ],
                    [
                        "admissions_id" => 187,
                        "branch" => null,
                        "country" => "Canada",
                        "city" => "Rerum aperiam accusa",
                        "institute" => "Est vero nemo suscip",
                        "intake_year" => "2025",
                        "intake_month" => "jan",
                        "created" => "2025-07-03 09:49:18"
                    ]
                ],
                "documents" => [
                    [
                        "document_id" => 371,
                        "document_type" => "LOR 1",
                        "document" => "uploads/documents/801374.pdf",
                        "mandatory" => 1
                    ],
                    [
                        "document_id" => 370,
                        "document_type" => "LOR 2",
                        "document" => "uploads/documents/906505.pdf",
                        "mandatory" => 1
                    ],
                    [
                        "document_id" => 369,
                        "document_type" => "Contract sign",
                        "document" => "https://b2b.innerxlite.com/storage/application_documents/1751020483_3_dummy.pdf",
                        "mandatory" => 1
                    ],
                    [
                        "document_id" => 368,
                        "document_type" => "10th",
                        "document" => "https://b2b.innerxlite.com/storage/application_documents/1751020483_2_dummy.pdf",
                        "mandatory" => 1
                    ],
                    [
                        "document_id" => 367,
                        "document_type" => "12th",
                        "document" => "https://b2b.innerxlite.com/storage/application_documents/1751020483_1_dummy.pdf",
                        "mandatory" => 1
                    ]
                ]
            ]);
        })->name('test.application.details');

        Route::get('/{id}', [ApplicationController::class, 'show'])->name('show');
        Route::delete('/{id}', [ApplicationController::class, 'destroy'])->name('destroy');
    });
    
    // Course Finder Routes
    Route::prefix('courses')->name('courses.')->group(function () {
        Route::get('/finder', [CourseFinderController::class, 'index'])->name('finder');
        Route::post('/search', [CourseFinderController::class, 'search'])->name('search');
        Route::get('/filters', [CourseFinderController::class, 'getFilters'])->name('filters');
        Route::get('/details/{courseId}', [CourseFinderController::class, 'getCourseDetails'])->name('details');
    });
    
    // Followup Management Routes
    Route::get('/followups/dashboard', [\App\Http\Controllers\FollowupController::class, 'dashboard'])->name('followups.dashboard');
    Route::get('/followups/today', [\App\Http\Controllers\FollowupController::class, 'todaysFollowups'])->name('followups.today');
    Route::get('/followups/overdue', [\App\Http\Controllers\FollowupController::class, 'overdueFollowups'])->name('followups.overdue');
    Route::patch('/followups/{followup}/complete', [\App\Http\Controllers\FollowupController::class, 'complete'])->name('followups.complete');
    Route::patch('/followups/{followup}/reschedule', [\App\Http\Controllers\FollowupController::class, 'reschedule'])->name('followups.reschedule');
    Route::resource('followups', \App\Http\Controllers\FollowupController::class);
    
    // Task Management Routes
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [TaskManagementController::class, 'index'])->name('index');
        Route::get('/create', [TaskManagementController::class, 'create'])->name('create');
        Route::post('/store', [TaskManagementController::class, 'store'])->name('store');
        Route::get('/{id}', [TaskManagementController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [TaskManagementController::class, 'edit'])->name('edit');
        Route::patch('/{id}', [TaskManagementController::class, 'update'])->name('update');
        Route::delete('/{id}', [TaskManagementController::class, 'destroy'])->name('destroy');
    });

    // External Task Management Routes (API Integration)
    Route::prefix('task-management')->name('task-management.')->group(function () {
        Route::get('/', [TaskManagementController::class, 'index'])->name('index');
        Route::get('/count', [TaskManagementController::class, 'getTaskCount'])->name('count');
        Route::get('/tasks', [TaskManagementController::class, 'getTasks'])->name('tasks');
        Route::get('/export', [TaskManagementController::class, 'exportTasks'])->name('export');
        Route::get('/show', [TaskManagementController::class, 'showTask'])->name('show');
        Route::post('/complete', [TaskManagementController::class, 'completeTask'])->name('complete');
    });
});

// Redirect authenticated users from login page to dashboard
Route::middleware('guest')->group(function () {
    Route::redirect('/home', '/dashboard');
});

// Test API integration route (temporary)
Route::get('/test-api/{visa_form_id?}', [ApplicationController::class, 'testApiIntegration'])->name('test-api');

// Debug route to test journey integration
Route::get('/debug-journey-integration/{visa_form_id?}', function($visaFormId = 388) {
    $controller = new \App\Http\Controllers\ApplicationController();
    
    // Simulate the same calls as showApiApplicationDetails
    try {
        $basicApiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application';
        $basicPayload = ['b2b_admin_id' => 1, 'visa_form_id' => $visaFormId];
        
        $detailsApiUrl = 'https://tarundemo.innerxcrm.com/b2bapi/application_details';
        $detailsPayload = ['b2b_admin_id' => 1, 'visa_form_id' => $visaFormId];
        
        // Use reflection to access private methods
        $reflection = new \ReflectionClass($controller);
        $makeApiCall = $reflection->getMethod('makeApiCall');
        $makeApiCall->setAccessible(true);
        $getJourneyData = $reflection->getMethod('getJourneyData');
        $getJourneyData->setAccessible(true);
        
        $basicResponse = $makeApiCall->invoke($controller, $basicApiUrl, $basicPayload);
        $detailsResponse = $makeApiCall->invoke($controller, $detailsApiUrl, $detailsPayload);
        
        $journeyTests = [];
        if ($detailsResponse['status'] === 'success' && isset($detailsResponse['admissions'])) {
            foreach ($detailsResponse['admissions'] as $admission) {
                if (isset($admission['admissions_id'])) {
                    $admissionId = $admission['admissions_id'];
                    $journey = $getJourneyData->invoke($controller, $admissionId);
                    $journeyTests[$admissionId] = [
                        'admission_info' => $admission,
                        'journey_status' => $journey ? $journey['status'] : 'failed',
                        'journey_data' => $journey
                    ];
                }
            }
        }
        
        return response()->json([
            'visa_form_id' => $visaFormId,
            'basic_api_status' => $basicResponse['status'],
            'details_api_status' => $detailsResponse['status'],
            'admissions_count' => count($detailsResponse['admissions'] ?? []),
            'journey_tests' => $journeyTests
        ], 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->name('debug-journey-integration');

require __DIR__.'/auth.php';
