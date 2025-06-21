<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserApprovalController;
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