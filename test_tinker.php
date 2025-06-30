<?php

use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Test course options submission
$requestData = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '1234567890',
    'country' => 'USA',
    'source' => 'Google',
    'remarks' => 'Test application submission',
    'course_options' => [
        [
            'country' => 'Canada',
            'city' => 'Toronto',
            'college' => 'University of Toronto',
            'course' => 'Computer Science',
            'course_type' => 'Bachelor',
            'fees' => '50000',
            'duration' => '4 years',
            'college_detail_id' => '123'
        ],
        [
            'country' => 'Canada',
            'city' => 'Vancouver',
            'college' => 'UBC',
            'course' => 'Engineering',
            'course_type' => 'Bachelor',
            'fees' => '45000',
            'duration' => '4 years',
            'college_detail_id' => '456'
        ]
    ]
];

echo "Testing course options submission...\n";
print_r($requestData);

// Create a request instance
$request = new Request($requestData);

// Test the store method
$controller = new ApplicationController();

try {
    $response = $controller->store($request);
    echo "\nSubmission successful!\n";
    
    // Check the latest application
    $latestApp = \App\Models\Application::with('courseOptions')->latest()->first();
    if ($latestApp) {
        echo "Application ID: " . $latestApp->id . "\n";
        echo "Name: " . $latestApp->name . "\n";
        echo "Course Options Count: " . $latestApp->courseOptions->count() . "\n";
        
        foreach ($latestApp->courseOptions as $option) {
            echo "  - Course: " . $option->course_name . " at " . $option->college_name . "\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
