<?php

/**
 * API Testing Script for Admin Hierarchy and User Approval
 * 
 * This script demonstrates how to test the new API endpoints
 * Usage: php api_test_examples.php
 */

class ApiTester
{
    private $baseUrl;
    private $token;

    public function __construct($baseUrl, $token)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->token = $token;
    }

    /**
     * Make HTTP request
     */
    private function makeRequest($method, $endpoint, $data = null)
    {
        $url = $this->baseUrl . '/api' . $endpoint;
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token,
                'Content-Type: application/json',
                'Accept: application/json'
            ],
            CURLOPT_CUSTOMREQUEST => $method,
        ]);

        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'status_code' => $httpCode,
            'response' => json_decode($response, true)
        ];
    }

    /**
     * Test: Get all admins with complete hierarchy
     */
    public function testGetAllAdmins()
    {
        echo "🔍 Testing: Get All Admins with Hierarchy\n";
        echo "Endpoint: GET /admin-hierarchy/admins\n";
        echo "Expected: Complete admin hierarchy with members\n\n";

        $result = $this->makeRequest('GET', '/admin-hierarchy/admins');
        
        echo "Status Code: " . $result['status_code'] . "\n";
        echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";
        echo str_repeat('-', 80) . "\n\n";

        return $result;
    }

    /**
     * Test: Get specific admin with members
     */
    public function testGetSpecificAdmin($adminId = 2)
    {
        echo "🔍 Testing: Get Specific Admin with Members\n";
        echo "Endpoint: GET /admin-hierarchy/admins/{$adminId}\n";
        echo "Expected: Specific admin details with their members\n\n";

        $result = $this->makeRequest('GET', "/admin-hierarchy/admins/{$adminId}");
        
        echo "Status Code: " . $result['status_code'] . "\n";
        echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";
        echo str_repeat('-', 80) . "\n\n";

        return $result;
    }

    /**
     * Test: Get hierarchy statistics
     */
    public function testGetHierarchyStats()
    {
        echo "🔍 Testing: Get Hierarchy Statistics\n";
        echo "Endpoint: GET /admin-hierarchy/stats\n";
        echo "Expected: Role-based statistics\n\n";

        $result = $this->makeRequest('GET', '/admin-hierarchy/stats');
        
        echo "Status Code: " . $result['status_code'] . "\n";
        echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";
        echo str_repeat('-', 80) . "\n\n";

        return $result;
    }

    /**
     * Test: Enhanced user approval
     */
    public function testEnhancedApproval($userId = 3, $approverId = 1)
    {
        echo "🔍 Testing: Enhanced User Approval\n";
        echo "Endpoint: POST /user-approval/users/{$userId}/enhanced-approve\n";
        echo "Expected: Detailed approval response with metadata\n\n";

        $data = [
            'approved_by' => $approverId,
            'notes' => 'Approved via API testing - documents verified',
            'notification_preferences' => [
                'email' => true,
                'sms' => false
            ],
            'auto_assign_admin' => true
        ];

        $result = $this->makeRequest('POST', "/user-approval/users/{$userId}/enhanced-approve", $data);
        
        echo "Request Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
        echo "Status Code: " . $result['status_code'] . "\n";
        echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";
        echo str_repeat('-', 80) . "\n\n";

        return $result;
    }

    /**
     * Test: Enhanced user rejection
     */
    public function testEnhancedRejection($userId = 4, $rejectorId = 1)
    {
        echo "🔍 Testing: Enhanced User Rejection\n";
        echo "Endpoint: POST /user-approval/users/{$userId}/enhanced-reject\n";
        echo "Expected: Detailed rejection response with metadata\n\n";

        $data = [
            'rejection_reason' => 'Incomplete documentation - missing GST certificate',
            'rejected_by' => $rejectorId,
            'notes' => 'User needs to upload valid GST certificate and reapply',
            'allow_reapplication' => true
        ];

        $result = $this->makeRequest('POST', "/user-approval/users/{$userId}/enhanced-reject", $data);
        
        echo "Request Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
        echo "Status Code: " . $result['status_code'] . "\n";
        echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";
        echo str_repeat('-', 80) . "\n\n";

        return $result;
    }

    /**
     * Test: Bulk user actions
     */
    public function testBulkUserActions($userIds = [5, 6, 7], $approverId = 1)
    {
        echo "🔍 Testing: Bulk User Actions\n";
        echo "Endpoint: POST /user-approval/users/bulk-actions\n";
        echo "Expected: Bulk operation results with individual status\n\n";

        $data = [
            'action' => 'approve',
            'user_ids' => $userIds,
            'approved_by' => $approverId,
            'notes' => 'Bulk approval via API testing - batch verification completed'
        ];

        $result = $this->makeRequest('POST', '/user-approval/users/bulk-actions', $data);
        
        echo "Request Data: " . json_encode($data, JSON_PRETTY_PRINT) . "\n";
        echo "Status Code: " . $result['status_code'] . "\n";
        echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";
        echo str_repeat('-', 80) . "\n\n";

        return $result;
    }

    /**
     * Test: Get all users with filtering
     */
    public function testGetAllUsersWithFilter($status = 'pending')
    {
        echo "🔍 Testing: Get All Users with Status Filter\n";
        echo "Endpoint: GET /user-approval/users?status={$status}\n";
        echo "Expected: Filtered user list based on approval status\n\n";

        $result = $this->makeRequest('GET', "/user-approval/users?status={$status}&per_page=10");
        
        echo "Status Code: " . $result['status_code'] . "\n";
        echo "Response: " . json_encode($result['response'], JSON_PRETTY_PRINT) . "\n\n";
        echo str_repeat('-', 80) . "\n\n";

        return $result;
    }

    /**
     * Run all tests
     */
    public function runAllTests()
    {
        echo "🚀 Starting API Tests for Admin Hierarchy and User Approval\n";
        echo "Base URL: " . $this->baseUrl . "\n";
        echo "Token: " . substr($this->token, 0, 10) . "...\n\n";
        echo str_repeat('=', 80) . "\n\n";

        $tests = [
            'Admin Hierarchy Tests' => [
                'getAllAdmins' => [$this, 'testGetAllAdmins'],
                'getSpecificAdmin' => [$this, 'testGetSpecificAdmin'],
                'getHierarchyStats' => [$this, 'testGetHierarchyStats'],
            ],
            'User Approval Tests' => [
                'getAllUsersWithFilter' => [$this, 'testGetAllUsersWithFilter'],
                'enhancedApproval' => [$this, 'testEnhancedApproval'],
                'enhancedRejection' => [$this, 'testEnhancedRejection'],
                'bulkUserActions' => [$this, 'testBulkUserActions'],
            ]
        ];

        $results = [];

        foreach ($tests as $category => $categoryTests) {
            echo "📋 {$category}\n";
            echo str_repeat('=', 80) . "\n\n";

            foreach ($categoryTests as $testName => $testMethod) {
                try {
                    $results[$testName] = call_user_func($testMethod);
                } catch (Exception $e) {
                    echo "❌ Error in {$testName}: " . $e->getMessage() . "\n\n";
                    $results[$testName] = ['error' => $e->getMessage()];
                }
            }
        }

        echo "✅ All tests completed!\n";
        echo str_repeat('=', 80) . "\n";

        return $results;
    }
}

// Example usage
if (php_sapi_name() === 'cli') {
    echo "Admin Hierarchy & User Approval API Tester\n";
    echo "==========================================\n\n";

    // Configuration - Replace with your actual values
    $baseUrl = 'http://localhost:8000';  // Your Laravel app URL
    $token = 'your-api-token-here';      // Your actual API token

    if ($token === 'your-api-token-here') {
        echo "⚠️  Please update the \$token variable with your actual API token\n";
        echo "You can get a token by:\n";
        echo "1. Creating a user account\n";
        echo "2. Using the login API endpoint\n";
        echo "3. Or running: php artisan tinker\n";
        echo "   \$user = User::first();\n";
        echo "   \$token = \$user->createToken('api-test')->plainTextToken;\n";
        echo "   echo \$token;\n\n";
        exit(1);
    }

    $tester = new ApiTester($baseUrl, $token);

    // Run individual tests or all tests
    $tester->runAllTests();

    echo "\n📖 For complete API documentation, see: ADMIN_HIERARCHY_API_DOCUMENTATION.md\n";
}
