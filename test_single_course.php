<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Application;
use App\Models\ApplicationCourseOption;
use Illuminate\Support\Facades\DB;

// Load Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Single Course Selection (Traditional Flow)\n";
echo "================================================\n\n";

try {
    DB::beginTransaction();

    // Test data for a traditional single course application
    $testApplicationData = [
        'application_number' => 'SINGLE-' . time(),
        'created_by' => 1,
        'status' => 'pending',
        'name' => 'Jane Smith Test',
        'email' => 'jane.test@example.com',
        'phone' => '+1-555-0456',
        'preferred_country' => 'Australia',
        'preferred_city' => 'Melbourne',
        'preferred_college' => 'University of Melbourne',
        'field_of_study' => 'Business Administration',
        'remarks' => 'Test application for single course selection'
    ];

    echo "1. Creating test application with traditional single course data...\n";
    $application = Application::create($testApplicationData);
    echo "   Application created with ID: {$application->id}\n";
    echo "   Application Number: {$application->application_number}\n\n";

    // Simulate the single course option creation that would happen in the controller
    echo "2. Creating single course option from main form fields...\n";
    $singleCourseData = [
        'application_id' => $application->id,
        'country' => $application->preferred_country,
        'city' => $application->preferred_city,
        'college' => $application->preferred_college,
        'course' => $application->field_of_study,
        'course_type' => null,
        'fees' => null,
        'duration' => null,
        'college_detail_id' => null,
        'is_primary' => true,
        'priority_order' => 1,
    ];

    $courseOption = ApplicationCourseOption::create($singleCourseData);
    echo "   Single course option created: {$singleCourseData['course']} at {$singleCourseData['college']}\n\n";

    echo "3. Verifying single course data retrieval...\n";
    $applicationWithCourse = Application::with('courseOptions')->find($application->id);
    echo "   Application loaded with " . $applicationWithCourse->courseOptions->count() . " course option\n";
    
    echo "\n4. Course option details:\n";
    $course = $applicationWithCourse->courseOptions->first();
    echo "   - Course: {$course->course}\n";
    echo "   - College: {$course->college}\n";
    echo "   - City: {$course->city}\n";
    echo "   - Country: {$course->country}\n";
    echo "   - Is Primary: " . ($course->is_primary ? 'Yes' : 'No') . "\n";
    echo "   - Priority: {$course->priority_order}\n";

    DB::commit();
    echo "\n✅ Single course test completed successfully!\n";

    // Cleanup
    echo "\n5. Cleaning up test data...\n";
    $applicationWithCourse->courseOptions()->delete();
    $applicationWithCourse->delete();
    echo "   Test data cleaned up\n";

} catch (Exception $e) {
    DB::rollback();
    echo "\n❌ Test failed with error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nSingle course test completed.\n";
