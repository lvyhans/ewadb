<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Facades\DB;

try {
    DB::beginTransaction();
    
    // Get a test user
    $user = User::first();
    if (!$user) {
        echo "No users found in database\n";
        exit(1);
    }
    
    // Test data that matches form submission
    $testData = [
        'application_number' => Application::generateApplicationNumber(),
        'created_by' => $user->id,
        'status' => 'pending',
        'name' => 'Test User',
        'email' => 'test@example.com',
        'phone' => '1234567890',
        'date_of_birth' => '1990-01-01',
        'address' => 'Test Address',
        'city' => 'Test City',
        'preferred_country' => 'Test Country',
        'preferred_city' => 'Test Preferred City',
        'preferred_college' => 'Test College',
        'field_of_study' => 'Test Course',
        'travel_history' => 1,
        'visa_refusal_history' => 0,
        'course_level' => 'bachelor', // Valid enum value
        'tenth_percentage' => '85',
        'tenth_year' => '2005',
        'twelfth_percentage' => '90',
        'twelfth_year' => '2007',
        'bachelor_percentage' => '80',
        'bachelor_year' => '2011',
        'english_proficiency' => 'ielts', // Valid enum value
        'ielts_score' => '7.0',
        'remarks' => 'Test application'
    ];
    
    echo "Creating application with data:\n";
    print_r($testData);
    
    $application = Application::create($testData);
    
    echo "Application created successfully with ID: " . $application->id . "\n";
    echo "Application Number: " . $application->application_number . "\n";
    
    DB::commit();
    
} catch (Exception $e) {
    DB::rollback();
    echo "Error creating application: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
