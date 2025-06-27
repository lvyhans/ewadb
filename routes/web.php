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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

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
        
        Route::get('/{id}', [ApplicationController::class, 'show'])->name('show');
        Route::delete('/{id}', [ApplicationController::class, 'destroy'])->name('destroy');
    });
    
    // Followup Management Routes
    Route::get('/followups/dashboard', [\App\Http\Controllers\FollowupController::class, 'dashboard'])->name('followups.dashboard');
    Route::get('/followups/today', [\App\Http\Controllers\FollowupController::class, 'todaysFollowups'])->name('followups.today');
    Route::get('/followups/overdue', [\App\Http\Controllers\FollowupController::class, 'overdueFollowups'])->name('followups.overdue');
    Route::patch('/followups/{followup}/complete', [\App\Http\Controllers\FollowupController::class, 'complete'])->name('followups.complete');
    Route::patch('/followups/{followup}/reschedule', [\App\Http\Controllers\FollowupController::class, 'reschedule'])->name('followups.reschedule');
    Route::resource('followups', \App\Http\Controllers\FollowupController::class);
});

// Redirect authenticated users from login page to dashboard
Route::middleware('guest')->group(function () {
    Route::redirect('/home', '/dashboard');
});

require __DIR__.'/auth.php';
