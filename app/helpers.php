<?php

if (!function_exists('setting')) {
    /**
     * Get a setting value by key
     */
    function setting(string $key, $default = null)
    {
        return \App\Models\Setting::get($key, $default);
    }
}

if (!function_exists('set_setting')) {
    /**
     * Set a setting value
     */
    function set_setting(string $key, $value, string $type = 'string', string $group = 'general', ?string $description = null, bool $isPublic = false)
    {
        return \App\Models\Setting::set($key, $value, $type, $group, $description, $isPublic);
    }
}