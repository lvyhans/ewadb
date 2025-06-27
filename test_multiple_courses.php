<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Models\Application;
use App\Models\ApplicationCourseOption;
use Illuminate\Support\Facades\DB;

// Load Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Multiple Course Selection Implementation\n";
echo "==============================================\n\n";

try {
    DB::beginTransaction();

    // Test data for an application with multiple course options
    $testApplicationData = [
        'application_number' => 'TEST-' . time(),
        'created_by' => 1,
        'status' => 'pending',
        'name' => 'John Doe Test',
        'email' => 'john.test@example.com',
        'phone' => '+1-555-0123',
        'preferred_country' => 'Canada',
        'remarks' => 'Test application for multiple course selection'
    ];

    echo "1. Creating test application...\n";
    $application = Application::create($testApplicationData);
    echo "   Application created with ID: {$application->id}\n";
    echo "   Application Number: {$application->application_number}\n\n";

    // Test course options data (simulating data from course finder)
    $testCourseOptions = [
        [
            'country' => 'Canada',
            'city' => 'Toronto',
            'college' => 'University of Toronto',
            'course' => 'Computer Science Masters',
            'course_type' => 'Masters',
            'fees' => 'CAD 45,000',
            'duration' => '2 years',
            'college_detail_id' => 'UOT-CS-001',
            'is_primary' => true,
            'priority_order' => 1
        ],
        [
            'country' => 'Canada',
            'city' => 'Vancouver',
            'college' => 'University of British Columbia',
            'course' => 'Data Science Masters',
            'course_type' => 'Masters',
            'fees' => 'CAD 42,000',
            'duration' => '2 years',
            'college_detail_id' => 'UBC-DS-001',
            'is_primary' => false,
            'priority_order' => 2
        ],
        [
            'country' => 'Canada',
            'city' => 'Montreal',
            'college' => 'McGill University',
            'course' => 'Software Engineering Masters',
            'course_type' => 'Masters',
            'fees' => 'CAD 38,000',
            'duration' => '2 years',
            'college_detail_id' => 'MCG-SE-001',
            'is_primary' => false,
            'priority_order' => 3
        ]
    ];

    echo "2. Creating multiple course options...\n";
    foreach ($testCourseOptions as $index => $courseData) {
        $courseData['application_id'] = $application->id;
        $courseOption = ApplicationCourseOption::create($courseData);
        echo "   Course Option " . ($index + 1) . " created: {$courseData['course']} at {$courseData['college']}\n";
    }

    echo "\n3. Verifying data retrieval...\n";
    $applicationWithCourses = Application::with('courseOptions')->find($application->id);
    echo "   Application loaded with " . $applicationWithCourses->courseOptions->count() . " course options\n";
    
    echo "\n4. Course options details:\n";
    foreach ($applicationWithCourses->courseOptions->sortBy('priority_order') as $course) {
        $primaryText = $course->is_primary ? " (PRIMARY)" : "";
        echo "   - Priority {$course->priority_order}: {$course->course} at {$course->college}, {$course->city}{$primaryText}\n";
    }

    echo "\n5. Testing primary course detection...\n";
    $primaryCourse = $applicationWithCourses->courseOptions->where('is_primary', true)->first();
    if ($primaryCourse) {
        echo "   Primary course found: {$primaryCourse->course} at {$primaryCourse->college}\n";
    } else {
        echo "   No primary course found\n";
    }

    DB::commit();
    echo "\n✅ Test completed successfully!\n";

    // Cleanup
    echo "\n6. Cleaning up test data...\n";
    $applicationWithCourses->courseOptions()->delete();
    $applicationWithCourses->delete();
    echo "   Test data cleaned up\n";

} catch (Exception $e) {
    DB::rollback();
    echo "\n❌ Test failed with error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nTest completed.\n";
