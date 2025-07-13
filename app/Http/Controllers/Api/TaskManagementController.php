<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskManagementRequest;
use App\Services\TaskManagementService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Exception;

class TaskManagementController extends Controller
{
    protected TaskManagementService $taskService;

    public function __construct(TaskManagementService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Fetch tasks from external API
     *
     * @param TaskManagementRequest $request
     * @return JsonResponse
     */
    public function fetchTasks(TaskManagementRequest $request): JsonResponse
    {
        try {
            $params = $request->validated();

            // Fetch tasks from external API
            $response = $this->taskService->fetchTasks($params);

            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get task count only
     *
     * @param TaskManagementRequest $request
     * @return JsonResponse
     */
    public function getTaskCount(TaskManagementRequest $request): JsonResponse
    {
        try {
            $params = $request->validated();
            $params['return'] = 'count';

            // Get task count
            $response = $this->taskService->fetchTasks($params);

            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get task count',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get full task details
     *
     * @param TaskManagementRequest $request
     * @return JsonResponse
     */
    public function getFullTasks(TaskManagementRequest $request): JsonResponse
    {
        try {
            $params = $request->validated();
            $params['return'] = 'full';

            // Get full tasks
            $response = $this->taskService->fetchTasks($params);

            return response()->json($response);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get full tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get tasks with advanced filtering
     *
     * @param TaskManagementRequest $request
     * @return JsonResponse
     */
    public function getTasksFiltered(TaskManagementRequest $request): JsonResponse
    {
        try {
            $params = $request->validated();

            // Fetch tasks with filters
            $response = $this->taskService->fetchTasks($params);

            return response()->json([
                'success' => true,
                'data' => $response,
                'filters_applied' => [
                    'task_status' => $params['task_status'],
                    'return_type' => $params['return'],
                    'page' => $params['page'],
                    'limit' => $params['limit'],
                    'deadline_start' => $params['deadline_start'] ?? null,
                    'deadline_end' => $params['deadline_end'] ?? null,
                    'admission_id' => $params['admission_id'] ?? null,
                ]
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get filtered tasks',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
