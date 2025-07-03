<?php
// Test application submission
require_once 'vendor/autoload.php';

// Load Laravel application
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Http\Controllers\ApplicationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Create a test request with proper data
$testData = [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'phone' => '1234567890',
    'source' => 'website',
    'remarks' => 'Test application submission',
    'intake_year' => '2025',
    'intake_month' => 'january', // This should be mapped to 'Jan'
    'country' => 'Canada',
    'city' => 'Toronto',
    'college' => 'Test College',
    'course' => 'Test Course',
];

// Create a request object
$request = new Request();
$request->merge($testData);

echo "Testing application submission with data:\n";
print_r($testData);

try {
    $controller = new ApplicationController();
    
    // Test the mapping function first
    $reflection = new ReflectionClass($controller);
    $mapMethod = $reflection->getMethod('mapIntakeMonth');
    $mapMethod->setAccessible(true);
    
    $mappedMonth = $mapMethod->invoke($controller, 'january');
    echo "\nIntake month mapping test: january -> $mappedMonth\n";
    
    // Test basic application creation
    $testApp = [
        'application_number' => App\Models\Application::generateApplicationNumber(),
        'created_by' => 1,
        'status' => 'pending',
        'name' => 'Test User',
        'phone' => '1234567890',
        'email' => 'test@example.com',
        'remarks' => 'Test application',
        'intake_year' => '2025',
        'intake_month' => $mappedMonth
    ];
    
    $application = App\Models\Application::create($testApp);
    echo "Application created successfully!\n";
    echo "Application ID: " . $application->id . "\n";
    echo "Application Number: " . $application->application_number . "\n";
    echo "Intake Month stored as: " . $application->intake_month . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
