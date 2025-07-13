<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\TaskManagementService;

class TaskManagementTest extends TestCase
{
    /**
     * Test TaskManagementService parameter validation
     */
    public function test_service_validates_parameters()
    {
        $service = new TaskManagementService();
        
        // Test missing required parameters
        $errors = $service->validateParams([]);
        $this->assertNotEmpty($errors);
        $this->assertContains('Either b2b_admin_id or b2b_member_id is required', $errors);
        
        // Test valid parameters
        $errors = $service->validateParams(['b2b_admin_id' => 1]);
        $this->assertEmpty($errors);
        
        // Test invalid task status
        $errors = $service->validateParams(['b2b_admin_id' => 1, 'task_status' => 'invalid']);
        $this->assertNotEmpty($errors);
        
        // Test invalid date format
        $errors = $service->validateParams(['b2b_admin_id' => 1, 'deadline_start' => 'invalid-date']);
        $this->assertNotEmpty($errors);
    }

    /**
     * Test parameter preparation
     */
    public function test_service_prepares_parameters()
    {
        $service = new TaskManagementService();
        
        $params = $service->prepareParams(['b2b_admin_id' => '1']);
        
        $this->assertEquals(1, $params['b2b_admin_id']);
        $this->assertEquals('count', $params['return']);
        $this->assertEquals('open', $params['task_status']);
        $this->assertEquals(1, $params['page']);
        $this->assertEquals(10, $params['limit']);
    }

    /**
     * Test date validation
     */
    public function test_date_validation()
    {
        $service = new TaskManagementService();
        
        // Valid date
        $errors = $service->validateParams([
            'b2b_admin_id' => 1,
            'deadline_start' => '2025-07-01',
            'deadline_end' => '2025-07-31'
        ]);
        $this->assertEmpty($errors);
        
        // Invalid date format
        $errors = $service->validateParams([
            'b2b_admin_id' => 1,
            'deadline_start' => '2025/07/01'
        ]);
        $this->assertNotEmpty($errors);
    }

    /**
     * Test numeric field validation
     */
    public function test_numeric_fields_validation()
    {
        $service = new TaskManagementService();
        
        // Valid numeric fields
        $errors = $service->validateParams([
            'b2b_admin_id' => 1,
            'page' => 1,
            'limit' => 10
        ]);
        $this->assertEmpty($errors);
        
        // Invalid numeric fields
        $errors = $service->validateParams([
            'b2b_admin_id' => 'not-a-number',
            'page' => 'invalid',
            'limit' => 'invalid'
        ]);
        $this->assertNotEmpty($errors);
    }
}
