<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function index()
    {
        Gate::authorize('manage settings');
        
        $settings = Setting::getAllGrouped();
        
        return view('settings.index', compact('settings'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        Gate::authorize('manage settings');

        $settingsData = $request->input('settings', []);
        
        foreach ($settingsData as $group => $groupSettings) {
            foreach ($groupSettings as $key => $value) {
                // Get the existing setting to preserve type and other metadata
                $existingSetting = Setting::where('key', $key)->first();
                
                if ($existingSetting) {
                    // Handle boolean values
                    if ($existingSetting->type === 'boolean') {
                        $value = $request->has("settings.{$group}.{$key}") ? true : false;
                    }
                    
                    Setting::set($key, $value, $existingSetting->type, $existingSetting->group, $existingSetting->description, $existingSetting->is_public);
                }
            }
        }

        return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
    }

    /**
     * Create or update a specific setting.
     */
    public function store(Request $request)
    {
        Gate::authorize('manage settings');

        $validator = Validator::make($request->all(), [
            'key' => 'required|string|max:255',
            'value' => 'nullable',
            'type' => 'required|in:string,integer,boolean,json,array',
            'group' => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'is_public' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        Setting::set(
            $request->key,
            $request->value,
            $request->type,
            $request->group,
            $request->description,
            $request->boolean('is_public')
        );

        return redirect()->route('settings.index')->with('success', 'Setting created successfully.');
    }

    /**
     * Delete a setting.
     */
    public function destroy($id)
    {
        Gate::authorize('manage settings');

        $setting = Setting::findOrFail($id);
        $setting->delete();
        
        Setting::clearCache();

        return redirect()->route('settings.index')->with('success', 'Setting deleted successfully.');
    }

    /**
     * Initialize default settings.
     */
    public function initializeDefaults()
    {
        Gate::authorize('manage settings');

        $defaultSettings = [
            // General Settings
            'app_name' => ['Laravel CRM', 'string', 'general', 'Application name displayed in headers and emails', true],
            'app_description' => ['Customer Relationship Management System', 'string', 'general', 'Brief description of the application', true],
            'timezone' => ['UTC', 'string', 'general', 'Default timezone for the application', false],
            'date_format' => ['Y-m-d', 'string', 'general', 'Default date format', false],
            'items_per_page' => [25, 'integer', 'general', 'Number of items to display per page', false],
            
            // Email Settings
            'mail_from_name' => ['Laravel CRM', 'string', 'email', 'Default sender name for emails', false],
            'mail_from_address' => ['noreply@example.com', 'string', 'email', 'Default sender email address', false],
            'enable_email_notifications' => [true, 'boolean', 'email', 'Enable email notifications', false],
            
            // Security Settings
            'password_min_length' => [8, 'integer', 'security', 'Minimum password length', false],
            'require_email_verification' => [false, 'boolean', 'security', 'Require email verification for new users', false],
            'session_timeout' => [120, 'integer', 'security', 'Session timeout in minutes', false],
            'max_login_attempts' => [5, 'integer', 'security', 'Maximum login attempts before lockout', false],
            
            // UI Settings
            'theme_color' => ['blue', 'string', 'ui', 'Primary theme color', true],
            'enable_dark_mode' => [false, 'boolean', 'ui', 'Enable dark mode option', true],
            'show_user_avatars' => [true, 'boolean', 'ui', 'Display user avatars in interface', true],
            
            // Features
            'enable_user_registration' => [true, 'boolean', 'features', 'Allow new user registration', false],
            'enable_api' => [true, 'boolean', 'features', 'Enable API endpoints', false],
            'enable_logging' => [true, 'boolean', 'features', 'Enable system logging', false],
        ];

        foreach ($defaultSettings as $key => [$value, $type, $group, $description, $isPublic]) {
            // Only create if doesn't exist
            if (!Setting::where('key', $key)->exists()) {
                Setting::set($key, $value, $type, $group, $description, $isPublic);
            }
        }

        return redirect()->route('settings.index')->with('success', 'Default settings initialized successfully.');
    }
}