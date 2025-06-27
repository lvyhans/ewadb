<?php

/**
 * Test script for Lead Revert API
 * This script demonstrates how to use the Lead Revert API endpoints
 */

class LeadRevertApiTest
{
    private $baseUrl;
    private $token;
    
    public function __construct($baseUrl = 'http://localhost:8000/api/external/lead-reverts', $token = null)
    {
        $this->baseUrl = $baseUrl;
        $this->token = $token;
    }
    
    /**
     * Test submitting a single revert
     */
    public function testSubmitRevert()
    {
        echo "Testing single revert submission...\n";
        
        $data = [
            'ref_no' => 'LEAD000001',
            'revert_message' => 'Test revert message - please verify the candidate documents',
            'submitted_by' => 'Test User'
        ];
        
        $response = $this->makeRequest('POST', '/submit', $data);
        
        if ($response['success']) {
            echo "✓ Revert submitted successfully\n";
            echo "  Revert ID: " . $response['data']['revert_id'] . "\n";
            return $response['data']['revert_id'];
        } else {
            echo "✗ Failed to submit revert\n";
            echo "  Error: " . $response['message'] . "\n";
            return null;
        }
    }
    
    /**
     * Test bulk revert submission
     */
    public function testBulkSubmitReverts()
    {
        echo "\nTesting bulk revert submission...\n";
        
        $data = [
            'reverts' => [
                [
                    'ref_no' => 'LEAD000001',
                    'revert_message' => 'First bulk revert message',
                    'submitted_by' => 'Bulk Test User'
                ],
                [
                    'ref_no' => 'LEAD000002',
                    'revert_message' => 'Second bulk revert message',
                    'submitted_by' => 'Bulk Test User'
                ]
            ]
        ];
        
        $response = $this->makeRequest('POST', '/bulk-submit', $data);
        
        if ($response['success']) {
            echo "✓ Bulk reverts submitted successfully\n";
            echo "  Successful: " . $response['summary']['successful'] . "\n";
            echo "  Failed: " . $response['summary']['failed'] . "\n";
        } else {
            echo "✗ Failed to submit bulk reverts\n";
            echo "  Error: " . $response['message'] . "\n";
        }
    }
    
    /**
     * Test getting reverts for a lead
     */
    public function testGetRevertsByRefNo($refNo = 'LEAD000001')
    {
        echo "\nTesting get reverts by reference number...\n";
        
        $response = $this->makeRequest('GET', '/lead/' . $refNo);
        
        if ($response['success']) {
            echo "✓ Successfully retrieved reverts for lead: " . $refNo . "\n";
            echo "  Lead Name: " . $response['data']['lead']['name'] . "\n";
            echo "  Total Reverts: " . $response['data']['statistics']['total_reverts'] . "\n";
            echo "  Active Reverts: " . $response['data']['statistics']['active_reverts'] . "\n";
        } else {
            echo "✗ Failed to retrieve reverts\n";
            echo "  Error: " . $response['message'] . "\n";
        }
    }
    
    /**
     * Test getting revert status
     */
    public function testGetRevertStatus($revertId)
    {
        if (!$revertId) {
            echo "\nSkipping revert status test (no revert ID available)\n";
            return;
        }
        
        echo "\nTesting get revert status...\n";
        
        $response = $this->makeRequest('GET', '/status/' . $revertId);
        
        if ($response['success']) {
            echo "✓ Successfully retrieved revert status\n";
            echo "  Revert ID: " . $response['data']['id'] . "\n";
            echo "  Status: " . $response['data']['status'] . "\n";
            echo "  Priority: " . $response['data']['priority'] . "\n";
        } else {
            echo "✗ Failed to retrieve revert status\n";
            echo "  Error: " . $response['message'] . "\n";
        }
    }
    
    /**
     * Test invalid submissions
     */
    public function testInvalidSubmissions()
    {
        echo "\nTesting invalid submissions...\n";
        
        // Test with invalid ref_no
        $invalidData = [
            'ref_no' => 'INVALID_REF',
            'revert_message' => 'Test message',
            'submitted_by' => 'Test User'
        ];
        
        $response = $this->makeRequest('POST', '/submit', $invalidData);
        
        if (!$response['success'] && strpos($response['message'], 'not found') !== false) {
            echo "✓ Correctly handled invalid reference number\n";
        } else {
            echo "✗ Did not handle invalid reference number correctly\n";
        }
        
        // Test with missing required fields
        $missingData = [
            'ref_no' => 'LEAD000001'
            // Missing revert_message, submitted_by
        ];
        
        $response = $this->makeRequest('POST', '/submit', $missingData);
        
        if (!$response['success'] && isset($response['errors'])) {
            echo "✓ Correctly handled missing required fields\n";
        } else {
            echo "✗ Did not handle missing required fields correctly\n";
        }
    }
    
    /**
     * Make HTTP request
     */
    private function makeRequest($method, $endpoint, $data = null)
    {
        $url = $this->baseUrl . $endpoint;
        
        $curl = curl_init();
        
        $headers = [
            'Content-Type: application/json',
            'Accept: application/json'
        ];
        
        if ($this->token) {
            $headers[] = 'Authorization: Bearer ' . $this->token;
        }
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);
        
        if ($data && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        }
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        curl_close($curl);
        
        $decodedResponse = json_decode($response, true);
        
        if ($httpCode >= 400) {
            echo "  HTTP Error: " . $httpCode . "\n";
            if (isset($decodedResponse['message'])) {
                echo "  Message: " . $decodedResponse['message'] . "\n";
            }
        }
        
        return $decodedResponse;
    }
    
    /**
     * Run all tests
     */
    public function runAllTests()
    {
        echo "Starting Lead Revert API Tests\n";
        echo "================================\n";
        
        if (!$this->token) {
            echo "Warning: No API token provided. Tests may fail.\n\n";
        }
        
        // Test single revert submission
        $revertId = $this->testSubmitRevert();
        
        // Test bulk revert submission
        $this->testBulkSubmitReverts();
        
        // Test getting reverts by reference number
        $this->testGetRevertsByRefNo();
        
        // Test getting revert status
        $this->testGetRevertStatus($revertId);
        
        // Test invalid submissions
        $this->testInvalidSubmissions();
        
        echo "\nTests completed!\n";
    }
}

// Run the tests if this script is executed directly
if (php_sapi_name() === 'cli') {
    $baseUrl = $argv[1] ?? 'http://localhost:8000/api/external/lead-reverts';
    $token = $argv[2] ?? null;
    
    echo "Base URL: " . $baseUrl . "\n";
    echo "Token: " . ($token ? "Provided" : "Not provided") . "\n\n";
    
    $tester = new LeadRevertApiTest($baseUrl, $token);
    $tester->runAllTests();
}
