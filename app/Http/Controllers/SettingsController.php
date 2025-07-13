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
            'app_name' => ['B2B CRM System', 'string', 'general', 'Application name displayed in headers and emails', true],
            'app_description' => ['Advanced Customer Relationship Management System for B2B Operations', 'string', 'general', 'Brief description of the application', true],
            'timezone' => ['UTC', 'string', 'general', 'Default timezone for the application', false],
            'date_format' => ['Y-m-d', 'string', 'general', 'Default date format', false],
            'datetime_format' => ['Y-m-d H:i:s', 'string', 'general', 'Default datetime format', false],
            'items_per_page' => [25, 'integer', 'general', 'Number of items to display per page', false],
            'currency' => ['USD', 'string', 'general', 'Default currency for financial operations', false],
            'language' => ['en', 'string', 'general', 'Default system language', false],
            
            // Email Settings
            'mail_from_name' => ['B2B CRM System', 'string', 'email', 'Default sender name for emails', false],
            'mail_from_address' => ['noreply@b2bcrm.com', 'string', 'email', 'Default sender email address', false],
            'enable_email_notifications' => [true, 'boolean', 'email', 'Enable email notifications', false],
            'email_signature' => ['Best regards,\nB2B CRM Team', 'string', 'email', 'Default email signature', false],
            'enable_email_queue' => [true, 'boolean', 'email', 'Queue emails for better performance', false],
            'email_daily_limit' => [1000, 'integer', 'email', 'Maximum emails per day', false],
            
            // Security Settings
            'password_min_length' => [8, 'integer', 'security', 'Minimum password length', false],
            'require_email_verification' => [false, 'boolean', 'security', 'Require email verification for new users', false],
            'session_timeout' => [120, 'integer', 'security', 'Session timeout in minutes', false],
            'max_login_attempts' => [5, 'integer', 'security', 'Maximum login attempts before lockout', false],
            'lockout_duration' => [60, 'integer', 'security', 'Account lockout duration in minutes', false],
            'require_strong_passwords' => [true, 'boolean', 'security', 'Require uppercase, lowercase, numbers and special characters', false],
            'enable_two_factor' => [false, 'boolean', 'security', 'Enable two-factor authentication', false],
            'password_expiry_days' => [90, 'integer', 'security', 'Days before password expires (0 = never)', false],
            
            // UI Settings
            'theme_color' => ['blue', 'string', 'ui', 'Primary theme color', true],
            'enable_dark_mode' => [false, 'boolean', 'ui', 'Enable dark mode option', true],
            'show_user_avatars' => [true, 'boolean', 'ui', 'Display user avatars in interface', true],
            'sidebar_collapsed' => [false, 'boolean', 'ui', 'Default sidebar state', true],
            'show_tooltips' => [true, 'boolean', 'ui', 'Show helpful tooltips', true],
            'items_per_row' => [4, 'integer', 'ui', 'Number of items per row in grid view', true],
            
            // Features
            'enable_user_registration' => [true, 'boolean', 'features', 'Allow new user registration', false],
            'enable_api' => [true, 'boolean', 'features', 'Enable API endpoints', false],
            'enable_logging' => [true, 'boolean', 'features', 'Enable system logging', false],
            'enable_backups' => [true, 'boolean', 'features', 'Enable automatic backups', false],
            'enable_notifications' => [true, 'boolean', 'features', 'Enable system notifications', false],
            'enable_analytics' => [true, 'boolean', 'features', 'Enable usage analytics', false],
            'enable_export' => [true, 'boolean', 'features', 'Allow data export functionality', false],
            'enable_import' => [true, 'boolean', 'features', 'Allow data import functionality', false],
            
            // Business Settings
            'company_name' => ['Your Company Ltd.', 'string', 'business', 'Official company name', true],
            'company_address' => ['123 Business Street, City, Country', 'string', 'business', 'Company address', true],
            'company_phone' => ['+1-234-567-8900', 'string', 'business', 'Company phone number', true],
            'company_email' => ['contact@company.com', 'string', 'business', 'Company contact email', true],
            'business_hours' => ['Monday-Friday: 9:00 AM - 6:00 PM', 'string', 'business', 'Business operating hours', true],
            'fiscal_year_start' => ['01-01', 'string', 'business', 'Fiscal year start date (MM-DD)', false],
            
            // Notifications Settings
            'notify_new_leads' => [true, 'boolean', 'notifications', 'Notify when new leads are created', false],
            'notify_task_deadlines' => [true, 'boolean', 'notifications', 'Notify about approaching task deadlines', false],
            'notify_application_updates' => [true, 'boolean', 'notifications', 'Notify about application status updates', false],
            'notification_frequency' => ['realtime', 'string', 'notifications', 'Notification frequency (realtime, hourly, daily)', false],
            'email_digest_frequency' => ['weekly', 'string', 'notifications', 'Email digest frequency (daily, weekly, monthly)', false],
            
            // Integration Settings
            'enable_webhook' => [false, 'boolean', 'integrations', 'Enable webhook functionality', false],
            'webhook_url' => ['', 'string', 'integrations', 'Webhook endpoint URL', false],
            'api_rate_limit' => [100, 'integer', 'integrations', 'API requests per minute', false],
            'enable_slack_integration' => [false, 'boolean', 'integrations', 'Enable Slack notifications', false],
            'slack_webhook_url' => ['', 'string', 'integrations', 'Slack webhook URL', false],
            
            // Performance Settings
            'cache_duration' => [3600, 'integer', 'performance', 'Default cache duration in seconds', false],
            'enable_query_cache' => [true, 'boolean', 'performance', 'Enable database query caching', false],
            'max_file_upload_size' => [10, 'integer', 'performance', 'Maximum file upload size in MB', false],
            'session_driver' => ['database', 'string', 'performance', 'Session storage driver', false],
            'queue_driver' => ['database', 'string', 'performance', 'Queue driver for background jobs', false],
        ];

        $created = 0;
        foreach ($defaultSettings as $key => [$value, $type, $group, $description, $isPublic]) {
            // Only create if doesn't exist
            if (!Setting::where('key', $key)->exists()) {
                Setting::set($key, $value, $type, $group, $description, $isPublic);
                $created++;
            }
        }

        return redirect()->route('settings.index')->with('success', "Default settings initialized successfully. Created {$created} new settings.");
    }

    /**
     * Clear settings cache.
     */
    public function clearCache()
    {
        Setting::clearCache();
        
        return response()->json(['message' => 'Settings cache cleared successfully.']);
    }
}