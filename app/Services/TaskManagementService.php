<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class TaskManagementService
{
    private string $baseUrl;
    private int $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.task_management.base_url', 'https://tarundemo.innerxcrm.com/b2bapi');
        $this->timeout = config('services.task_management.timeout', 30);
    }

    /**
     * Fetch tasks from external API
     *
     * @param array $params
     * @return array
     * @throws Exception
     */
    public function fetchTasks(array $params): array
    {
        try {
            $url = $this->baseUrl . '/task_management';
            
            Log::info('Fetching tasks from external API', [
                'url' => $url,
                'params' => $params
            ]);

            $response = Http::timeout($this->timeout)
                ->post($url, $params);

            if (!$response->successful()) {
                Log::error('Task API request failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'params' => $params
                ]);
                
                throw new Exception('Failed to fetch tasks from external API: ' . $response->body());
            }

            $data = $response->json();
            
            Log::info('Tasks fetched successfully', [
                'response_keys' => array_keys($data),
                'success' => $data['success'] ?? false
            ]);

            return $data;

        } catch (Exception $e) {
            Log::error('Task API service error', [
                'error' => $e->getMessage(),
                'params' => $params
            ]);
            
            throw $e;
        }
    }

    /**
     * Get task count only
     *
     * @param array $params
     * @return array
     */
    public function getTaskCount(array $params): array
    {
        $params['return'] = 'count';
        return $this->fetchTasks($params);
    }

    /**
     * Get full task details
     *
     * @param array $params
     * @return array
     */
    public function getFullTasks(array $params): array
    {
        $params['return'] = 'full';
        return $this->fetchTasks($params);
    }

    /**
     * Validate required parameters
     *
     * @param array $params
     * @return array
     */
    public function validateParams(array $params): array
    {
        $errors = [];

        // Either b2b_admin_id or b2b_member_id is required
        if (!isset($params['b2b_admin_id']) && !isset($params['b2b_member_id'])) {
            $errors[] = 'Either b2b_admin_id or b2b_member_id is required';
        }

        // Validate task_status if provided
        if (isset($params['task_status']) && !in_array($params['task_status'], ['open', 'closed'])) {
            $errors[] = 'task_status must be either "open" or "closed"';
        }

        // Validate return type if provided
        if (isset($params['return']) && !in_array($params['return'], ['count', 'full'])) {
            $errors[] = 'return must be either "count" or "full"';
        }

        // Validate date format if provided
        if (isset($params['deadline_start']) && !$this->isValidDate($params['deadline_start'])) {
            $errors[] = 'deadline_start must be in YYYY-MM-DD format';
        }

        if (isset($params['deadline_end']) && !$this->isValidDate($params['deadline_end'])) {
            $errors[] = 'deadline_end must be in YYYY-MM-DD format';
        }

        // Validate numeric fields
        $numericFields = ['b2b_admin_id', 'b2b_member_id', 'visa_form_id', 'page', 'limit'];
        foreach ($numericFields as $field) {
            if (isset($params[$field]) && !is_numeric($params[$field])) {
                $errors[] = "$field must be a valid number";
            }
        }

        return $errors;
    }

    /**
     * Check if date is valid YYYY-MM-DD format
     *
     * @param string $date
     * @return bool
     */
    private function isValidDate(string $date): bool
    {
        return (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $date) && 
               strtotime($date) !== false;
    }

    /**
     * Prepare default parameters
     *
     * @param array $params
     * @return array
     */
    public function prepareParams(array $params): array
    {
        // Set defaults
        $params['return'] = $params['return'] ?? 'count';
        $params['task_status'] = $params['task_status'] ?? 'open';
        $params['page'] = $params['page'] ?? 1;
        $params['limit'] = $params['limit'] ?? 10;

        // Convert numeric strings to integers
        $numericFields = ['b2b_admin_id', 'b2b_member_id', 'visa_form_id', 'page', 'limit'];
        foreach ($numericFields as $field) {
            if (isset($params[$field])) {
                $params[$field] = (int) $params[$field];
            }
        }

        return $params;
    }

    /**
     * Complete a task by uploading a document
     *
     * @param int $taskId
     * @param string $documentPath
     * @return array
     * @throws Exception
     */
    public function completeTask(int $taskId, string $documentPath): array
    {
        try {
            $url = $this->baseUrl . '/save_task_managment';
            
            $params = [
                'id' => $taskId,
                'task_document' => $documentPath
            ];
            
            Log::info('Completing task via external API', [
                'url' => $url,
                'params' => $params
            ]);

            $response = Http::timeout($this->timeout)
                ->post($url, $params);

            if (!$response->successful()) {
                Log::error('Task completion API request failed', [
                    'status' => $response->status(),
                    'response' => $response->body(),
                    'params' => $params
                ]);
                
                throw new Exception('Failed to complete task via external API: ' . $response->body());
            }

            $data = $response->json();
            
            Log::info('Task completed successfully', [
                'response_keys' => array_keys($data),
                'success' => $data['success'] ?? false,
                'task_id' => $taskId
            ]);

            return $data;

        } catch (Exception $e) {
            Log::error('Task completion API service error', [
                'error' => $e->getMessage(),
                'task_id' => $taskId,
                'document_path' => $documentPath
            ]);
            
            throw $e;
        }
    }
}
