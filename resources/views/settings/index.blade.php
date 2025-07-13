@extends('layouts.app')

@section('title', 'System Settings')
@section('page-title', 'System Settings')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="settingsApp()">
        
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
                                    <p class="text-lg text-gray-600">Configure and manage your B2B CRM system preferences</p>
                                </div>
                            </div>
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Cached Settings: Active
                                </div>
                                <div class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                    {{ $settings->flatten()->count() }} Settings Configured
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button @click="showAddModal = true" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-6 py-3 rounded-xl transition-all duration-200 flex items-center font-medium shadow-sm hover:shadow-md">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Setting
                            </button>
                            <form action="{{ route('settings.initialize') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="bg-blue-500 border border-blue-500 text-white hover:bg-blue-600 px-6 py-3 rounded-xl transition-all duration-200 flex items-center font-medium shadow-sm hover:shadow-md">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Initialize Defaults
                                </button>
                            </form>
                            <button @click="clearCache()" class="bg-gradient-to-r from-slate-500 to-slate-700 hover:from-slate-600 hover:to-slate-800 text-white px-6 py-3 rounded-xl transition-all duration-200 flex items-center font-medium shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Clear Cache
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

            @forelse($settings as $groupName => $groupSettings)
                <!-- Settings Group -->
                <div class="bg-white rounded-2xl shadow-lg border border-gray-200 overflow-hidden">
                    <div class="bg-gradient-to-r {{ $groupName === 'general' ? 'from-blue-500 to-blue-600' : ($groupName === 'security' ? 'from-red-500 to-red-600' : ($groupName === 'email' ? 'from-green-500 to-green-600' : ($groupName === 'ui' ? 'from-purple-500 to-purple-600' : 'from-gray-500 to-gray-600'))) }} px-6 py-4">
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
                                @else
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                @endif
                            </div>
                            <h2 class="text-xl font-bold text-white capitalize">{{ ucfirst($groupName) }} Settings</h2>
                        </div>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        @foreach($groupSettings as $setting)
                            <div class="flex flex-col lg:flex-row lg:items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors duration-200">
                                <div class="flex-1 mb-4 lg:mb-0 lg:mr-6">
                                    <div class="flex items-center justify-between mb-2">
                                        <label for="setting_{{ $setting->key }}" class="text-lg font-semibold text-gray-900 capitalize">
                                            {{ str_replace('_', ' ', $setting->key) }}
                                        </label>
                                        <div class="flex items-center gap-2">
                                            @if($setting->is_public)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    Public
                                                </span>
                                            @endif
                                            <button type="button" @click="deleteSetting({{ $setting->id }})" class="text-red-500 hover:text-red-700 transition-colors duration-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
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
                                            <option value="blue" {{ $setting->value === 'blue' ? 'selected' : '' }}>Blue</option>
                                            <option value="red" {{ $setting->value === 'red' ? 'selected' : '' }}>Red</option>
                                            <option value="green" {{ $setting->value === 'green' ? 'selected' : '' }}>Green</option>
                                            <option value="purple" {{ $setting->value === 'purple' ? 'selected' : '' }}>Purple</option>
                                            <option value="gray" {{ $setting->value === 'gray' ? 'selected' : '' }}>Gray</option>
                                        </select>
                                    @elseif($setting->key === 'timezone')
                                        <select id="setting_{{ $setting->key }}"
                                                name="settings[{{ $groupName }}][{{ $setting->key }}]"
                                                @change="autoSave && $el.form.submit()"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500 transition-colors duration-200">
                                            <option value="UTC" {{ $setting->value === 'UTC' ? 'selected' : '' }}>UTC</option>
                                            <option value="America/New_York" {{ $setting->value === 'America/New_York' ? 'selected' : '' }}>Eastern Time</option>
                                            <option value="America/Chicago" {{ $setting->value === 'America/Chicago' ? 'selected' : '' }}>Central Time</option>
                                            <option value="America/Denver" {{ $setting->value === 'America/Denver' ? 'selected' : '' }}>Mountain Time</option>
                                            <option value="America/Los_Angeles" {{ $setting->value === 'America/Los_Angeles' ? 'selected' : '' }}>Pacific Time</option>
                                            <option value="Europe/London" {{ $setting->value === 'Europe/London' ? 'selected' : '' }}>London</option>
                                            <option value="Europe/Paris" {{ $setting->value === 'Europe/Paris' ? 'selected' : '' }}>Paris</option>
                                            <option value="Asia/Tokyo" {{ $setting->value === 'Asia/Tokyo' ? 'selected' : '' }}>Tokyo</option>
                                        </select>
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
                    <p class="text-gray-600 mb-4">Get started by initializing default settings or adding your own custom settings.</p>
                    <button @click="showAddModal = true" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200">
                        Add Your First Setting
                    </button>
                </div>
            @endforelse

            @if($settings->count() > 0)
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
        </form>

        <!-- Add Setting Modal -->
        <div x-show="showAddModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showAddModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity" 
                     @click="showAddModal = false">
                    <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                </div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showAddModal"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    
                    <form action="{{ route('settings.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Add New Setting</h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="key" class="block text-sm font-medium text-gray-700 mb-1">Setting Key</label>
                                    <input type="text" name="key" id="key" required
                                           placeholder="e.g., max_file_size"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Value</label>
                                    <input type="text" name="value" id="value"
                                           placeholder="Setting value"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                                    <select name="type" id="type" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="string">String</option>
                                        <option value="integer">Integer</option>
                                        <option value="boolean">Boolean</option>
                                        <option value="json">JSON</option>
                                        <option value="array">Array</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="group" class="block text-sm font-medium text-gray-700 mb-1">Group</label>
                                    <select name="group" id="group" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                        <option value="general">General</option>
                                        <option value="email">Email</option>
                                        <option value="security">Security</option>
                                        <option value="ui">UI</option>
                                        <option value="features">Features</option>
                                        <option value="business">Business</option>
                                        <option value="notifications">Notifications</option>
                                        <option value="integrations">Integrations</option>
                                        <option value="performance">Performance</option>
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea name="description" id="description" rows="2"
                                              placeholder="Brief description of this setting"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>
                                
                                <div class="flex items-center">
                                    <input type="checkbox" name="is_public" id="is_public" value="1"
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_public" class="ml-2 text-sm text-gray-700">Public (visible to non-admins)</label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex justify-end gap-3">
                            <button @click="showAddModal = false" 
                                    type="button" 
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Cancel
                            </button>
                            <button type="submit" 
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Add Setting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function settingsApp() {
    return {
        showAddModal: false,
        
        deleteSetting(settingId) {
            if (confirm('Are you sure you want to delete this setting? This action cannot be undone.')) {
                // Create a form and submit it
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/settings/${settingId}`;
                
                // Add CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                form.appendChild(csrfInput);
                
                // Add method spoofing for DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        },
        
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
