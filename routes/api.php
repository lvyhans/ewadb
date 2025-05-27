<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserApprovalApiController;
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
    Route::post('get-registration-token', [AuthController::class, 'getRegistrationToken']);
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
        return $request->user()->load('roles', 'permissions');
    });

    // Example protected route
    Route::get('/dashboard', function (Request $request) {
        return response()->json([
            'message' => 'Welcome to your dashboard!',
            'user' => $request->user()->only(['id', 'name', 'email']),
            'roles' => $request->user()->getRoleNames(),
            'permissions' => $request->user()->getAllPermissions()->pluck('name'),
        ]);
    });
});

// Example role-based routes
Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/users', function () {
        return response()->json([
            'message' => 'Admin only - Users list',
            'users' => \App\Models\User::with('roles')->get()
        ]);
    });
    
    // User Approval Management API Routes
    Route::prefix('user-approvals')->group(function () {
        Route::get('pending', [UserApprovalApiController::class, 'getPendingUsers']);
        Route::get('statistics', [UserApprovalApiController::class, 'getApprovalStats']);
        Route::get('{userId}', [UserApprovalApiController::class, 'getUserDetails']);
        Route::post('{userId}/approve', [UserApprovalApiController::class, 'approveUser']);
        Route::post('{userId}/reject', [UserApprovalApiController::class, 'rejectUser']);
        Route::post('bulk-update', [UserApprovalApiController::class, 'bulkUpdateUsers']);
    });
});

// Public User Approval API Routes (for external systems integration)
Route::prefix('external/user-approvals')->middleware('auth:sanctum')->group(function () {
    Route::get('pending', [UserApprovalApiController::class, 'getPendingUsers']);
    Route::get('statistics', [UserApprovalApiController::class, 'getApprovalStats']);
    Route::get('{userId}', [UserApprovalApiController::class, 'getUserDetails']);
    Route::post('{userId}/approve', [UserApprovalApiController::class, 'approveUser']);
    Route::post('{userId}/reject', [UserApprovalApiController::class, 'rejectUser']);
    Route::post('bulk-update', [UserApprovalApiController::class, 'bulkUpdateUsers']);
});