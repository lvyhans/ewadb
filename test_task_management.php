<?php

use App\Services\TaskManagementService;

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel application
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Example usage of TaskManagementService
$service = new TaskManagementService();

echo "=== Task Management API Integration Test ===\n\n";

// Test 1: Get task count
echo "1. Testing task count...\n";
try {
    $params = [
        'b2b_admin_id' => 1,
        'task_status' => 'open'
    ];
    
    $response = $service->getTaskCount($params);
    echo "✅ Task count response: " . json_encode($response, JSON_PRETTY_PRINT) . "\n\n";
} catch (Exception $e) {
    echo "❌ Error getting task count: " . $e->getMessage() . "\n\n";
}

// Test 2: Get full tasks
echo "2. Testing full tasks...\n";
try {
    $params = [
        'b2b_admin_id' => 1,
        'task_status' => 'open',
        'page' => 1,
        'limit' => 5
    ];
    
    $response = $service->getFullTasks($params);
    echo "✅ Full tasks response: " . json_encode($response, JSON_PRETTY_PRINT) . "\n\n";
} catch (Exception $e) {
    echo "❌ Error getting full tasks: " . $e->getMessage() . "\n\n";
}

// Test 3: Validation
echo "3. Testing validation...\n";
$invalidParams = [];
$errors = $service->validateParams($invalidParams);
if (!empty($errors)) {
    echo "✅ Validation working correctly. Errors: " . implode(', ', $errors) . "\n";
} else {
    echo "❌ Validation not working as expected\n";
}

echo "\n=== Test Complete ===\n";
