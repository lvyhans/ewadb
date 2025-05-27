<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserApprovalController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Registration Routes (for guest users only)
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/registration-success', [RegisterController::class, 'registrationSuccess'])->name('registration.success');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // User Management Routes
    Route::resource('users', UserController::class);
    Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    
    // User Approval Routes (Admin only)
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/user-approvals', [UserApprovalController::class, 'index'])->name('user-approvals.index');
        Route::get('/user-approvals/{user}', [UserApprovalController::class, 'show'])->name('user-approvals.show');
        Route::patch('/user-approvals/{user}/approve', [UserApprovalController::class, 'approve'])->name('user-approvals.approve');
        Route::patch('/user-approvals/{user}/reject', [UserApprovalController::class, 'reject'])->name('user-approvals.reject');
        Route::get('/user-approvals/{user}/download/{document}', [UserApprovalController::class, 'downloadDocument'])->name('user-approvals.download-document');
    });
    
    // Settings Management Routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings', [SettingsController::class, 'store'])->name('settings.store');
    Route::delete('/settings/{setting}', [SettingsController::class, 'destroy'])->name('settings.destroy');
    Route::post('/settings/initialize', [SettingsController::class, 'initializeDefaults'])->name('settings.initialize');
    
    // Roles & Permissions Management Routes
    Route::get('/roles-permissions', [RolePermissionController::class, 'index'])->name('roles-permissions.index');
    Route::post('/roles-permissions/roles', [RolePermissionController::class, 'storeRole'])->name('roles-permissions.store-role');
    Route::post('/roles-permissions/permissions', [RolePermissionController::class, 'storePermission'])->name('roles-permissions.store-permission');
    Route::patch('/roles-permissions/roles/{role}', [RolePermissionController::class, 'updateRole'])->name('roles-permissions.update-role');
    Route::delete('/roles-permissions/roles/{role}', [RolePermissionController::class, 'destroyRole'])->name('roles-permissions.destroy-role');
    Route::delete('/roles-permissions/permissions/{permission}', [RolePermissionController::class, 'destroyPermission'])->name('roles-permissions.destroy-permission');
});

// Redirect authenticated users from login page to dashboard
Route::middleware('guest')->group(function () {
    Route::redirect('/home', '/dashboard');
});

require __DIR__.'/auth.php';
