<?php

/**
 * API Hit Monitoring Test
 * This script tests and monitors API hits to verify the Lead Revert API is working correctly
 */

echo "=== Lead Revert API Hit Monitoring Test ===\n\n";

// Configuration
$baseUrl = 'http://localhost:8003/api/external/lead-reverts';
$token = '2|UYVUc16fQ3xq0wKp0GrtfhzgYBq2UGtbHY0nWedxc0da848f';

echo "Base URL: $baseUrl\n";
echo "Token: " . substr($token, 0, 20) . "...\n\n";

/**
 * Function to make HTTP requests and monitor response
 */
function makeApiRequest($url, $method = 'GET', $data = null, $token = null) {
    $curl = curl_init();
    
    $headers = [
        'Content-Type: application/json',
        'Accept: application/json'
    ];
    
    if ($token) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 30,
        CURLINFO_HEADER_OUT => true,
        CURLOPT_VERBOSE => false
    ]);
    
    if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    }
    
    $startTime = microtime(true);
    $response = curl_exec($curl);
    $endTime = microtime(true);
    
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $responseTime = round(($endTime - $startTime) * 1000, 2);
    $requestHeaders = curl_getinfo($curl, CURLINFO_HEADER_OUT);
    
    curl_close($curl);
    
    return [
        'success' => $response !== false,
        'http_code' => $httpCode,
        'response' => $response,
        'response_time' => $responseTime,
        'request_headers' => $requestHeaders
    ];
}

/**
 * Test 1: Submit Single Revert
 */
echo "🔄 TEST 1: Single Revert Submission\n";
echo "------------------------------------\n";

$singleData = [
    'ref_no' => 'LEAD000001',
    'revert_message' => 'API monitoring test - single submission',
    'submitted_by' => 'Monitor Test User'
];

$result1 = makeApiRequest($baseUrl . '/submit', 'POST', $singleData, $token);

echo "HTTP Status: {$result1['http_code']}\n";
echo "Response Time: {$result1['response_time']}ms\n";
echo "Success: " . ($result1['success'] ? 'YES' : 'NO') . "\n";

if ($result1['success']) {
    $response1 = json_decode($result1['response'], true);
    if ($response1['success']) {
        echo "✅ API Hit Successful\n";
        echo "Revert ID: {$response1['data']['revert_id']}\n";
        echo "Response: {$response1['message']}\n";
    } else {
        echo "❌ API Hit Failed\n";
        echo "Error: {$response1['message']}\n";
    }
} else {
    echo "❌ Request Failed\n";
}

echo "\n";

/**
 * Test 2: Submit Bulk Reverts
 */
echo "🔄 TEST 2: Bulk Revert Submission\n";
echo "------------------------------------\n";

$bulkData = [
    'reverts' => [
        [
            'ref_no' => 'LEAD000001',
            'revert_message' => 'API monitoring test - bulk submission #1',
            'submitted_by' => 'Bulk Monitor User'
        ],
        [
            'ref_no' => 'LEAD000002',
            'revert_message' => 'API monitoring test - bulk submission #2',
            'submitted_by' => 'Bulk Monitor User'
        ]
    ]
];

$result2 = makeApiRequest($baseUrl . '/bulk-submit', 'POST', $bulkData, $token);

echo "HTTP Status: {$result2['http_code']}\n";
echo "Response Time: {$result2['response_time']}ms\n";
echo "Success: " . ($result2['success'] ? 'YES' : 'NO') . "\n";

if ($result2['success']) {
    $response2 = json_decode($result2['response'], true);
    if ($response2['success']) {
        echo "✅ API Hit Successful\n";
        echo "Processed: {$response2['summary']['total_processed']}\n";
        echo "Successful: {$response2['summary']['successful']}\n";
        echo "Failed: {$response2['summary']['failed']}\n";
    } else {
        echo "❌ API Hit Failed\n";
        echo "Error: {$response2['message']}\n";
    }
} else {
    echo "❌ Request Failed\n";
}

echo "\n";

/**
 * Test 3: Get Reverts for Lead
 */
echo "🔄 TEST 3: Get Lead Reverts\n";
echo "------------------------------------\n";

$result3 = makeApiRequest($baseUrl . '/lead/LEAD000001', 'GET', null, $token);

echo "HTTP Status: {$result3['http_code']}\n";
echo "Response Time: {$result3['response_time']}ms\n";
echo "Success: " . ($result3['success'] ? 'YES' : 'NO') . "\n";

if ($result3['success']) {
    $response3 = json_decode($result3['response'], true);
    if ($response3['success']) {
        echo "✅ API Hit Successful\n";
        echo "Lead: {$response3['data']['lead']['name']}\n";
        echo "Total Reverts: {$response3['data']['statistics']['total_reverts']}\n";
        echo "Active Reverts: {$response3['data']['statistics']['active_reverts']}\n";
    } else {
        echo "❌ API Hit Failed\n";
        echo "Error: {$response3['message']}\n";
    }
} else {
    echo "❌ Request Failed\n";
}

echo "\n";

/**
 * Test 4: Authentication Test (Invalid Token)
 */
echo "🔄 TEST 4: Authentication Test (Invalid Token)\n";
echo "------------------------------------\n";

$result4 = makeApiRequest($baseUrl . '/submit', 'POST', $singleData, 'invalid-token');

echo "HTTP Status: {$result4['http_code']}\n";
echo "Response Time: {$result4['response_time']}ms\n";

if ($result4['http_code'] == 401) {
    echo "✅ Authentication Working (Correctly rejected invalid token)\n";
} else {
    echo "❌ Authentication Issue (Should have returned 401)\n";
}

echo "\n";

/**
 * Summary
 */
echo "📊 API MONITORING SUMMARY\n";
echo "========================\n";
echo "Single Submission: " . ($result1['http_code'] == 201 ? '✅ Working' : '❌ Failed') . "\n";
echo "Bulk Submission: " . ($result2['http_code'] == 201 ? '✅ Working' : '❌ Failed') . "\n";
echo "Get Reverts: " . ($result3['http_code'] == 200 ? '✅ Working' : '❌ Failed') . "\n";
echo "Authentication: " . ($result4['http_code'] == 401 ? '✅ Working' : '❌ Failed') . "\n";

echo "\n";

// Check for recent database activity
echo "📋 DATABASE ACTIVITY CHECK\n";
echo "==========================\n";

// Include Laravel bootstrap
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

// Check recent reverts
$recentReverts = DB::table('lead_reverts')
    ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-5 minutes')))
    ->orderBy('created_at', 'desc')
    ->get(['id', 'ref_no', 'submitted_by', 'created_at']);

echo "Recent Reverts (last 5 minutes): " . count($recentReverts) . "\n";
foreach ($recentReverts as $revert) {
    echo "  - ID: {$revert->id} | {$revert->ref_no} | {$revert->submitted_by} | {$revert->created_at}\n";
}

// Check queued jobs
$queuedJobs = DB::table('jobs')->count();
echo "\nQueued Notification Jobs: $queuedJobs\n";

// Check recent notifications
$recentNotifications = DB::table('notifications')
    ->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('-5 minutes')))
    ->count();

echo "Recent Notifications (last 5 minutes): $recentNotifications\n";

echo "\n✅ API Hit Monitoring Complete!\n";
