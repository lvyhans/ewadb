@extends('layouts.app')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@php
    // Define which settings to show for each group - only working/functional ones
    $allowedSettings = [
        'general' => ['app_name', 'app_description', 'timezone', 'items_per_page', 'currency', 'language', 'date_format'],
        'email' => ['mail_from_name', 'mail_from_address', 'enable_email_notifications', 'email_signature'],
        'security' => ['password_min_length', 'session_timeout', 'max_login_attempts', 'lockout_duration', 'require_strong_passwords'],
        'ui' => ['theme_color', 'show_user_avatars', 'sidebar_collapsed', 'show_tooltips'],
        'business' => ['company_name', 'company_address', 'company_phone', 'company_email', 'business_hours'],
        'features' => ['enable_user_registration', 'enable_logging', 'enable_notifications', 'enable_export']
    ];
    
    // Filter settings to only show allowed ones
    $filteredSettings = collect($settings)->map(function($groupSettings, $groupName) use ($allowedSettings) {
        if (!isset($allowedSettings[$groupName])) {
            return collect(); // Skip groups not in allowed list
        }
        
        return $groupSettings->filter(function($setting) use ($allowedSettings, $groupName) {
            return in_array($setting->key, $allowedSettings[$groupName]);
        });
    })->filter(function($groupSettings) {
        return $groupSettings->isNotEmpty(); // Only keep groups that have settings
    });
    
    $totalSettings = collect($allowedSettings)->flatten()->count();
@endphp

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="settingsApp()">
        
        <!-- Header Section -->
        <div class="mb-8">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-200 p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-slate-400/10 to-gray-600/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-blue-400/10 to-indigo-600/10 rounded-full blur-3xl"></div>
                <div class="relative z-10">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between">
                        <div class="mb-6 lg:mb-0">
                            <div class="flex items-center mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-slate-500 to-slate-700 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                            <div>
                                <h1 class="text-4xl font-bold text-gray-900 mb-2">System Settings</h1>
                                <p class="text-lg text-gray-600">Configure essential settings for your B2B system</p>
                                <div class="mt-3 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Showing only functional settings
                                </div>
                            </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Settings Cache: Active
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $totalSettings }} Working Settings
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    {{ count($allowedSettings) }} Setting Groups
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <form action="{{ route('settings.initialize') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="bg-blue-500 border border-blue-500 text-white hover:bg-blue-600 px-6 py-3 rounded-xl transition-all duration-200 flex items-center font-medium shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Initialize Default Settings
                                </button>
                            </form>
                            <button @click="clearCache()" class="bg-gradient-to-r from-slate-500 to-slate-700 hover:from-slate-600 hover:to-slate-800 text-white px-6 py-3 rounded-xl transition-all duration-200 flex items-center font-medium shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Clear Cache
                            </button>
                            <button @click="window.location.reload()" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-xl transition-all duration-200 flex items-center font-medium shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Refresh
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    Please fix the following errors:
                </div>
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Settings Form -->
        <form action="{{ route('settings.update') }}" method="POST" class="space-y-8" x-data="{ autoSave: false }">
            @csrf
            @method('PATCH')

            @forelse($filteredSettings as $groupName => $groupSettings)
                <!-- Settings Group -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r {{ $groupName === 'general' ? 'from-blue-500 to-blue-600' : ($groupName === 'security' ? 'from-red-500 to-red-600' : ($groupName === 'email' ? 'from-green-500 to-green-600' : ($groupName === 'ui' ? 'from-purple-500 to-purple-600' : ($groupName === 'business' ? 'from-indigo-500 to-indigo-600' : 'from-gray-500 to-gray-600')))) }} px-6 py-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                @if($groupName === 'general')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                @elseif($groupName === 'security')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                @elseif($groupName === 'email')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                @elseif($groupName === 'ui')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"></path>
                                    </svg>
                                @elseif($groupName === 'business')
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                @endif
                            </div>
                            <h2 class="text-xl font-bold text-white capitalize">{{ ucfirst($groupName) }} Settings</h2>
                            <div class="ml-auto">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                    {{ $groupSettings->count() }} {{ Str::plural('setting', $groupSettings->count()) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        @foreach($groupSettings as $setting)
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex-1 mb-4 lg:mb-0 lg:mr-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <label for="setting_{{ $setting->key }}" class="text-lg font-semibold text-gray-900 capitalize">
                                            {{ str_replace(['_', 'app ', 'mail ', 'enable '], ['', 'App ', 'Email ', 'Enable '], ucwords(str_replace('_', ' ', $setting->key))) }}
                                        </label>
                                        <div class="flex items-center gap-2">
                                            @if($setting->is_public)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Public
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                                    </svg>
                                                    Private
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($setting->description)
                                        <p class="text-sm text-gray-600 mb-2">{{ $setting->description }}</p>
                                    @endif
                                </div>
                                
                                <div class="lg:w-80">
                                    @if($setting->type === 'boolean')
                                        <div class="flex items-center">
                                            <input type="hidden" name="settings[{{ $groupName }}][{{ $setting->key }}]" value="0">
                                            <input type="checkbox" 
                                                   id="setting_{{ $setting->key }}"
                                                   name="settings[{{ $groupName }}][{{ $setting->key }}]" 
                                                   value="1"
                                                   {{ $setting->value ? 'checked' : '' }}
                                                   @change="autoSave && $el.form.submit()"
                                                   class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                                            <span class="ml-3 text-sm font-medium text-gray-900">
                                                {{ $setting->value ? 'Enabled' : 'Disabled' }}
                                            </span>
                                        </div>
                                    @elseif($setting->type === 'integer')
                                        <input type="number" 
                                               id="setting_{{ $setting->key }}"
                                               name="settings[{{ $groupName }}][{{ $setting->key }}]" 
                                               value="{{ $setting->value }}"
                                               @change="autoSave && $el.form.submit()"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                    @elseif($setting->key === 'theme_color')
                                        <select id="setting_{{ $setting->key }}"
                                                name="settings[{{ $groupName }}][{{ $setting->key }}]"
                                                @change="autoSave && $el.form.submit()"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                            <option value="blue" {{ $setting->value === 'blue' ? 'selected' : '' }}>🔵 Blue</option>
                                            <option value="red" {{ $setting->value === 'red' ? 'selected' : '' }}>🔴 Red</option>
                                            <option value="green" {{ $setting->value === 'green' ? 'selected' : '' }}>🟢 Green</option>
                                            <option value="purple" {{ $setting->value === 'purple' ? 'selected' : '' }}>🟣 Purple</option>
                                            <option value="gray" {{ $setting->value === 'gray' ? 'selected' : '' }}>⚫ Gray</option>
                                        </select>
                                    @elseif($setting->key === 'timezone')
                                        <select id="setting_{{ $setting->key }}"
                                                name="settings[{{ $groupName }}][{{ $setting->key }}]"
                                                @change="autoSave && $el.form.submit()"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                            <option value="UTC" {{ $setting->value === 'UTC' ? 'selected' : '' }}>🌍 UTC (Coordinated Universal Time)</option>
                                            <option value="America/New_York" {{ $setting->value === 'America/New_York' ? 'selected' : '' }}>🇺🇸 Eastern Time (ET)</option>
                                            <option value="America/Chicago" {{ $setting->value === 'America/Chicago' ? 'selected' : '' }}>🇺🇸 Central Time (CT)</option>
                                            <option value="America/Denver" {{ $setting->value === 'America/Denver' ? 'selected' : '' }}>🇺🇸 Mountain Time (MT)</option>
                                            <option value="America/Los_Angeles" {{ $setting->value === 'America/Los_Angeles' ? 'selected' : '' }}>🇺🇸 Pacific Time (PT)</option>
                                            <option value="Europe/London" {{ $setting->value === 'Europe/London' ? 'selected' : '' }}>🇬🇧 London (GMT/BST)</option>
                                            <option value="Europe/Paris" {{ $setting->value === 'Europe/Paris' ? 'selected' : '' }}>🇫🇷 Paris (CET/CEST)</option>
                                            <option value="Asia/Tokyo" {{ $setting->value === 'Asia/Tokyo' ? 'selected' : '' }}>🇯🇵 Tokyo (JST)</option>
                                        </select>
                                    @elseif($setting->key === 'currency')
                                        <select id="setting_{{ $setting->key }}"
                                                name="settings[{{ $groupName }}][{{ $setting->key }}]"
                                                @change="autoSave && $el.form.submit()"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                            <option value="USD" {{ $setting->value === 'USD' ? 'selected' : '' }}>💵 USD - US Dollar</option>
                                            <option value="EUR" {{ $setting->value === 'EUR' ? 'selected' : '' }}>💶 EUR - Euro</option>
                                            <option value="GBP" {{ $setting->value === 'GBP' ? 'selected' : '' }}>💷 GBP - British Pound</option>
                                            <option value="CAD" {{ $setting->value === 'CAD' ? 'selected' : '' }}>🍁 CAD - Canadian Dollar</option>
                                            <option value="AUD" {{ $setting->value === 'AUD' ? 'selected' : '' }}>🇦🇺 AUD - Australian Dollar</option>
                                        </select>
                                    @elseif($setting->key === 'language')
                                        <select id="setting_{{ $setting->key }}"
                                                name="settings[{{ $groupName }}][{{ $setting->key }}]"
                                                @change="autoSave && $el.form.submit()"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                            <option value="en" {{ $setting->value === 'en' ? 'selected' : '' }}>🇺🇸 English</option>
                                            <option value="es" {{ $setting->value === 'es' ? 'selected' : '' }}>🇪🇸 Spanish</option>
                                            <option value="fr" {{ $setting->value === 'fr' ? 'selected' : '' }}>🇫🇷 French</option>
                                            <option value="de" {{ $setting->value === 'de' ? 'selected' : '' }}>🇩🇪 German</option>
                                            <option value="pt" {{ $setting->value === 'pt' ? 'selected' : '' }}>🇵🇹 Portuguese</option>
                                        </select>
                                    @elseif($setting->key === 'email_signature')
                                        <textarea id="setting_{{ $setting->key }}"
                                               name="settings[{{ $groupName }}][{{ $setting->key }}]"
                                               rows="3"
                                               @change="autoSave && $el.form.submit()"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">{{ $setting->value }}</textarea>
                                    @else
                                        <input type="text" 
                                               id="setting_{{ $setting->key }}"
                                               name="settings[{{ $groupName }}][{{ $setting->key }}]" 
                                               value="{{ $setting->value }}"
                                               @change="autoSave && $el.form.submit()"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No Settings Found</h3>
                    <p class="text-gray-600 mb-4">It looks like no settings have been configured yet. Initialize the default settings to get started with essential system configuration options.</p>
                    <form action="{{ route('settings.initialize') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200">
                            Initialize Default Settings
                        </button>
                    </form>
                </div>
            @endforelse

            @if($filteredSettings->count() > 0)
                <!-- Auto-save Toggle & Save Button -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 p-6">
                    <div class="flex flex-col sm:flex-row items-center justify-between">
                        <div class="flex items-center mb-4 sm:mb-0">
                            <input type="checkbox" 
                                   id="auto_save"
                                   x-model="autoSave"
                                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                            <label for="auto_save" class="ml-2 text-sm font-medium text-gray-900">
                                Auto-save changes
                            </label>
                        </div>
                        <button type="submit" 
                                class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-xl transition-all duration-200 font-medium shadow-lg hover:shadow-xl flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                            </svg>
                            Save All Settings
                        </button>
                    </div>
                </div>
            @endif
    </div>
</div>

<script>
function settingsApp() {
    return {
        clearCache() {
            if (confirm('Are you sure you want to clear the settings cache?')) {
                fetch('{{ route("settings.clear-cache") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message || 'Cache cleared successfully!');
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error clearing cache. Please try again.');
                });
            }
        }
    }
}

// Auto-save functionality for settings
document.addEventListener('DOMContentLoaded', function() {
    // Handle checkbox label updates
    document.querySelectorAll('input[type="checkbox"][name*="settings"]').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const label = this.parentElement.querySelector('span');
            if (label) {
                label.textContent = this.checked ? 'Enabled' : 'Disabled';
            }
        });
    });
});
</script>

    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
// Auto-save form handling
document.addEventListener('DOMContentLoaded', function() {
    // Toggle boolean setting labels
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        if (checkbox.id && checkbox.id.startsWith('setting_')) {
            checkbox.addEventListener('change', function() {
                const span = this.parentElement.querySelector('span');
                if (span) {
                    span.textContent = this.checked ? 'Enabled' : 'Disabled';
                }
            });
        }
    });
});
</script>
@endpush
