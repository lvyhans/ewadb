<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PhoneCheckController;
use App\Http\Controllers\Api\UserApprovalController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\LeadFollowupController;
use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public authentication routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Public Lead Revert API Routes (with token authentication)
Route::prefix('external/lead-reverts')->middleware('auth:sanctum')->group(function () {
    // Submit a new revert/remark
    Route::post('/submit', [\App\Http\Controllers\Api\LeadRevertApiController::class, 'submitRevert']);
    
    // Bulk submit multiple reverts
    Route::post('/bulk-submit', [\App\Http\Controllers\Api\LeadRevertApiController::class, 'bulkSubmitReverts']);
    
    // Get all reverts for a lead by reference number
    Route::get('/lead/{refNo}', [\App\Http\Controllers\Api\LeadRevertApiController::class, 'getRevertsByRefNo']);
    
    // Get status of a specific revert
    Route::get('/status/{revertId}', [\App\Http\Controllers\Api\LeadRevertApiController::class, 'getRevertStatus']);
});

// Protected routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    // Authentication routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('logout-all', [AuthController::class, 'logoutAll']);
        Route::get('profile', [AuthController::class, 'profile']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::put('change-password', [AuthController::class, 'changePassword']);
    });

    // User info route
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Example protected route
    Route::get('/dashboard', function (Request $request) {
        return response()->json([
            'message' => 'Welcome to your dashboard!',
            'user' => $request->user()->only(['id', 'name', 'email']),
            'account_type' => $request->user()->email === 'admin@example.com' ? 'admin' : 'user',
        ]);
    });

    // User Approval API Routes
    Route::prefix('user-approval')->group(function () {
        // Get users by approval status
        Route::get('/users', [UserApprovalController::class, 'getAllUsers']);
        Route::get('/users/pending', [UserApprovalController::class, 'getPendingUsers']);
        Route::get('/users/{userId}', [UserApprovalController::class, 'getUserDetails']);
        
        // Standard approval actions
        Route::post('/users/{userId}/approve', [UserApprovalController::class, 'approveUser']);
        Route::post('/users/{userId}/reject', [UserApprovalController::class, 'rejectUser']);
        Route::post('/users/{userId}/pending', [UserApprovalController::class, 'setPendingStatus']);
        
        // Enhanced approval actions for third-party integration
        Route::post('/users/{userId}/enhanced-approve', [UserApprovalController::class, 'enhancedApproveUser']);
        Route::post('/users/{userId}/enhanced-reject', [UserApprovalController::class, 'enhancedRejectUser']);
        
        // Bulk operations
        Route::post('/users/bulk-approve', [UserApprovalController::class, 'bulkApproveUsers']);
        Route::post('/users/bulk-actions', [UserApprovalController::class, 'bulkUserActions']);
        
        // Statistics
        Route::get('/stats', [UserApprovalController::class, 'getApprovalStats']);
    });

    // Admin Hierarchy API Routes
    Route::prefix('admin-hierarchy')->group(function () {
        // Get all admins with their members and complete details
        Route::get('/admins', [\App\Http\Controllers\Api\AdminHierarchyController::class, 'getAllAdmins']);
        
        // Get specific admin with members
        Route::get('/admins/{adminId}', [\App\Http\Controllers\Api\AdminHierarchyController::class, 'getAdminWithMembers']);
        
        // Get hierarchy statistics
        Route::get('/stats', [\App\Http\Controllers\Api\AdminHierarchyController::class, 'getHierarchyStats']);
    });

    // Lead Management API Routes
    Route::prefix('leads')->group(function () {
        // Lead CRUD operations
        Route::get('/', [LeadController::class, 'index']);
        Route::post('/', [LeadController::class, 'store']);
        Route::get('/{id}', [LeadController::class, 'show']);
        Route::put('/{id}', [LeadController::class, 'update']);
        Route::delete('/{id}', [LeadController::class, 'destroy']);
        
        // Lead statistics
        Route::get('/stats/dashboard', [LeadController::class, 'statistics']);
        
        // Lead followups
        Route::get('/{leadId}/followups', [LeadFollowupController::class, 'index']);
        Route::post('/{leadId}/followups', [LeadFollowupController::class, 'store']);
        Route::put('/{leadId}/followups/{followupId}/complete', [LeadFollowupController::class, 'complete']);
        Route::put('/{leadId}/followups/{followupId}/cancel', [LeadFollowupController::class, 'cancel']);
    });
    
    // Followup Management Routes
    Route::prefix('followups')->group(function () {
        Route::get('/my-followups', [LeadFollowupController::class, 'myFollowups']);
        Route::get('/today', [LeadFollowupController::class, 'todaysFollowups']);
        Route::get('/overdue', [LeadFollowupController::class, 'overdueFollowups']);
        
        // Individual followup management
        Route::get('/{id}', [\App\Http\Controllers\Api\FollowupApiController::class, 'show']);
        Route::post('/{id}', [\App\Http\Controllers\Api\FollowupApiController::class, 'update']);
        Route::post('/{id}/complete', [\App\Http\Controllers\Api\FollowupApiController::class, 'complete']);
    });

    // Application API routes
    Route::prefix('applications')->group(function () {
        Route::post('/get-lead-data', [ApplicationController::class, 'getLeadData']);
        Route::post('/get-country-qualification-data', [ApplicationController::class, 'getCountryQualificationData']);
        Route::post('/journey', [ApplicationController::class, 'getFormattedApplicationJourney']);
        Route::get('/journey/{applicationId}', [ApplicationController::class, 'getApplicationJourney']);
    });

    // Lead Revert Management API Routes (Internal)
    Route::prefix('lead-reverts')->group(function () {
        // Get all reverts for a specific lead
        Route::get('/lead/{leadId}', [\App\Http\Controllers\LeadRevertController::class, 'index']);
        
        // Get all active reverts (for dashboard)
        Route::get('/active', [\App\Http\Controllers\LeadRevertController::class, 'getAllActiveReverts']);
        
        // Revert management actions
        Route::post('/{revertId}/resolve', [\App\Http\Controllers\LeadRevertController::class, 'resolve']);
        Route::post('/{revertId}/reopen', [\App\Http\Controllers\LeadRevertController::class, 'reopen']);
        Route::post('/{revertId}/archive', [\App\Http\Controllers\LeadRevertController::class, 'archive']);
        
        // Statistics
        Route::get('/statistics', [\App\Http\Controllers\LeadRevertController::class, 'getStatistics']);
    });
});

// Public dropdown API routes for application form
Route::prefix('dropdown')->group(function () {
    Route::get('/countries', [ApplicationController::class, 'getCountries']);
    Route::get('/cities', [ApplicationController::class, 'getCities']);
    Route::get('/colleges', [ApplicationController::class, 'getColleges']);
    Route::get('/courses', [ApplicationController::class, 'getCourses']);
    
    // Unified College Filter API
    Route::post('/college-filter', [ApplicationController::class, 'getCollegeFilterData']);
});

// Public application data routes
Route::prefix('applications')->group(function () {
    Route::get('/get-lead-data', [ApplicationController::class, 'getLeadData']);
});

// Example admin routes
Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    Route::get('/users', function () {
        return response()->json([
            'message' => 'Admin only - Users list',
            'users' => \App\Models\User::all()
        ]);
    });
});

// Task Management API Routes
Route::prefix('task-management')->middleware('auth:sanctum')->group(function () {
    // Fetch tasks (generic endpoint)
    Route::post('/tasks', [\App\Http\Controllers\Api\TaskManagementController::class, 'fetchTasks']);
    
    // Get task count only
    Route::post('/tasks/count', [\App\Http\Controllers\Api\TaskManagementController::class, 'getTaskCount']);
    
    // Get full task details
    Route::post('/tasks/full', [\App\Http\Controllers\Api\TaskManagementController::class, 'getFullTasks']);
    
    // Get tasks with advanced filtering
    Route::post('/tasks/filtered', [\App\Http\Controllers\Api\TaskManagementController::class, 'getTasksFiltered']);
});

// External Task Webhook API - no auth:sanctum to allow external access
Route::prefix('webhooks')->group(function () {
    // Tarundemo task status webhook
    Route::post('/tasks', [\App\Http\Controllers\Api\TaskWebhookController::class, 'handleTaskWebhook']);
});

// Public Phone Number Check API (no authentication required for form validation)
Route::post('/check-phone', [\App\Http\Controllers\Api\PhoneCheckController::class, 'checkPhoneNumber']);