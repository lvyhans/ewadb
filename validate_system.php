<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== User Approval System Validation ===\n\n";

// Test 1: Check if API routes are registered
echo "1. Checking API routes...\n";
$routes = collect(\Route::getRoutes())->filter(function ($route) {
    return str_contains($route->uri(), 'user-approval');
});

if ($routes->count() > 0) {
    echo "✓ Found " . $routes->count() . " user-approval API routes\n";
    foreach ($routes as $route) {
        echo "  - " . $route->methods()[0] . " " . $route->uri() . "\n";
    }
} else {
    echo "✗ No user-approval API routes found\n";
}

echo "\n";

// Test 2: Check models and relationships
echo "2. Checking User model capabilities...\n";
$user = new App\Models\User();

$methods = ['isApproved', 'isPending', 'isRejected', 'approve', 'reject', 'hasRole', 'roles'];
foreach ($methods as $method) {
    if (method_exists($user, $method)) {
        echo "✓ User model has method: $method\n";
    } else {
        echo "✗ User model missing method: $method\n";
    }
}

echo "\n";

// Test 3: Check Role model
echo "3. Checking Role model...\n";
try {
    $roleCount = App\Models\Role::count();
    echo "✓ Role model works, found $roleCount roles\n";
    
    $roles = App\Models\Role::pluck('name')->toArray();
    echo "  Available roles: " . implode(', ', $roles) . "\n";
} catch (Exception $e) {
    echo "✗ Role model error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 4: Check database structure
echo "4. Checking database structure...\n";
try {
    $userTableColumns = \Illuminate\Support\Facades\Schema::getColumnListing('users');
    $approvalColumns = ['approval_status', 'rejection_reason', 'approved_at', 'approved_by'];
    
    foreach ($approvalColumns as $column) {
        if (in_array($column, $userTableColumns)) {
            echo "✓ Users table has column: $column\n";
        } else {
            echo "✗ Users table missing column: $column\n";
        }
    }
    
    if (\Illuminate\Support\Facades\Schema::hasTable('roles')) {
        echo "✓ Roles table exists\n";
    } else {
        echo "✗ Roles table missing\n";
    }
    
    if (\Illuminate\Support\Facades\Schema::hasTable('user_roles')) {
        echo "✓ User_roles table exists\n";
    } else {
        echo "✗ User_roles table missing\n";
    }
    
} catch (Exception $e) {
    echo "✗ Database structure error: " . $e->getMessage() . "\n";
}

echo "\n";

// Test 5: Check UserApprovalController
echo "5. Checking UserApprovalController...\n";
try {
    $controller = new App\Http\Controllers\Api\UserApprovalController();
    echo "✓ UserApprovalController instantiated successfully\n";
    
    $methods = ['stats', 'getAllUsers', 'getPendingUsers', 'getUser', 'approveUser', 'rejectUser', 'setPending', 'bulkApprove'];
    foreach ($methods as $method) {
        if (method_exists($controller, $method)) {
            echo "✓ Controller has method: $method\n";
        } else {
            echo "✗ Controller missing method: $method\n";
        }
    }
} catch (Exception $e) {
    echo "✗ UserApprovalController error: " . $e->getMessage() . "\n";
}

echo "\n=== Validation Complete ===\n";
