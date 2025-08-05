<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'external_api' => [
        'base_url' => env('EXTERNAL_API_BASE_URL', 'https://tarundemo.innerxcrm.com'),
        'enquiry_lead_url' => env('EXTERNAL_API_ENQUIRY_LEAD_URL'),
        'checklist_url' => env('EXTERNAL_API_CHECKLIST_URL'),
        'college_filter_url' => env('EXTERNAL_API_COLLEGE_FILTER_URL'),
        'application_url' => env('EXTERNAL_API_APPLICATION_URL'),
        'application_details_url' => env('EXTERNAL_API_APPLICATION_DETAILS_URL'),
        'application_journey_url' => env('EXTERNAL_API_APPLICATION_JOURNEY_URL'),
        'phone_check_url' => env('EXTERNAL_API_PHONE_CHECK_URL'),
        'course_finder_url' => env('EXTERNAL_API_COURSE_FINDER_URL'),
        'countries_url' => env('EXTERNAL_API_COUNTRIES_URL'),
        'cities_url' => env('EXTERNAL_API_CITIES_URL'),
        'colleges_url' => env('EXTERNAL_API_COLLEGES_URL'),
        'courses_url' => env('EXTERNAL_API_COURSES_URL'),
        'enabled' => env('EXTERNAL_API_ENABLED', false),
    ],

    'task_management' => [
        'base_url' => env('TASK_MANAGEMENT_API_URL', 'https://tarundemo.innerxcrm.com/b2bapi'),
        'timeout' => env('TASK_MANAGEMENT_API_TIMEOUT', 30),
        'enabled' => env('TASK_MANAGEMENT_API_ENABLED', true),
    ],
];
