<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
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
});

// Redirect authenticated users from login page to dashboard
Route::middleware('guest')->group(function () {
    Route::redirect('/home', '/dashboard');
});

require __DIR__.'/auth.php';
