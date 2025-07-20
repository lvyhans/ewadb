<?php

namespace App\Http\Controllers;

use App\Services\TaskManagementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Exception;

class TaskManagementController extends Controller
{
    private TaskManagementService $taskService;

    public function __construct(TaskManagementService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display the task management dashboard
     */
    public function index(Request $request)
    {
        try {
            // Get filter parameters from request
            $params = $this->getFilterParams($request);
            
            // Get tasks from external API
            $tasksData = $this->taskService->getFullTasks($params);
            
            // Get task count for statistics
            $countParams = $params;
            $countParams['return'] = 'count';
            $countData = $this->taskService->fetchTasks($countParams);
            
            return view('task-management.index', [
                'tasks' => $tasksData['tasks'] ?? [],
                'pagination' => $tasksData['pagination'] ?? [],
                'filters' => $params,
                'taskCount' => $countData['count'] ?? 0,
                'success' => $tasksData['success'] ?? false
            ]);
            
        } catch (Exception $e) {
            Log::error('Task management index error: ' . $e->getMessage());
            
            return view('task-management.index', [
                'tasks' => [],
                'pagination' => [],
                'filters' => $this->getFilterParams($request),
                'taskCount' => 0,
                'success' => false,
                'error' => 'Failed to fetch tasks: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Get task count via AJAX
     */
    public function getTaskCount(Request $request)
    {
        try {
            $params = $this->getFilterParams($request);
            $data = $this->taskService->getTaskCount($params);
            
            return response()->json($data);
            
        } catch (Exception $e) {
            Log::error('Task count API error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get full tasks via AJAX
     */
    public function getTasks(Request $request)
    {
        try {
            $params = $this->getFilterParams($request);
            $data = $this->taskService->getFullTasks($params);
            
            return response()->json($data);
            
        } catch (Exception $e) {
            Log::error('Tasks API error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show task details modal
     */
    public function showTask(Request $request)
    {
        try {
            $params = $this->getFilterParams($request);
            $params['task_id'] = $request->input('task_id');
            
            $data = $this->taskService->getFullTasks($params);
            
            return response()->json($data);
            
        } catch (Exception $e) {
            Log::error('Show task error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export tasks to CSV
     */
    public function exportTasks(Request $request)
    {
        try {
            $params = $this->getFilterParams($request);
            $params['limit'] = 1000; // Get more records for export
            
            $data = $this->taskService->getFullTasks($params);
            $tasks = $data['tasks'] ?? [];
            
            if (empty($tasks)) {
                return redirect()->back()->with('error', 'No tasks found to export');
            }
            
            $filename = 'tasks_export_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            return response()->stream(function() use ($tasks) {
                $handle = fopen('php://output', 'w');
                
                // Add CSV headers
                fputcsv($handle, [
                    'ID',
                    'Title',
                    'Description',
                    'Status',
                    'Priority',
                    'Deadline',
                    'Assigned To',
                    'Created At',
                    'Updated At'
                ]);
                
                // Add task data
                foreach ($tasks as $task) {
                    fputcsv($handle, [
                        $task['id'] ?? '',
                        $task['title'] ?? '',
                        $task['description'] ?? '',
                        $task['status'] ?? '',
                        $task['priority'] ?? '',
                        $task['deadline'] ?? '',
                        $task['assigned_to'] ?? '',
                        $task['created_at'] ?? '',
                        $task['updated_at'] ?? ''
                    ]);
                }
                
                fclose($handle);
            }, 200, $headers);
            
        } catch (Exception $e) {
            Log::error('Export tasks error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to export tasks: ' . $e->getMessage());
        }
    }

    /**
     * Complete a task by uploading a document
     */
    public function completeTask(Request $request)
    {
        try {
            $request->validate([
                'task_id' => 'required|integer',
                'task_document' => 'required|file|max:10240|mimes:pdf,doc,docx,jpg,jpeg,png,txt'
            ]);

            $taskId = $request->input('task_id');
            $file = $request->file('task_document');
            
            // Store the file in storage/app/public/task_documents
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('task_documents', $fileName, 'public');
            
            // Generate the public URL for the file
            $fileUrl = asset('storage/' . $filePath);
            
            // Call the completion API
            $data = $this->taskService->completeTask($taskId, $fileUrl);
            
            if ($data['success'] ?? false) {
                return response()->json([
                    'success' => true,
                    'message' => 'Task completed successfully',
                    'file_url' => $fileUrl,
                    'data' => $data
                ]);
            } else {
                // Delete the uploaded file if API call failed
                Storage::disk('public')->delete($filePath);
                
                return response()->json([
                    'success' => false,
                    'error' => $data['message'] ?? 'Failed to complete task'
                ], 400);
            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
            
        } catch (Exception $e) {
            Log::error('Complete task error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get filter parameters from request
     */
    private function getFilterParams(Request $request): array
    {
        $params = [];
        $user = auth()->user();
        
        // Auto-determine b2b_admin_id and b2b_member_id based on user role
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            // If admin is logged in, send admin auth id along with member id 0
            $params['b2b_admin_id'] = $user->id;
            $params['b2b_member_id'] = 0;
        } else {
            // If member is logged in, send member auth id along with admin id
            $params['b2b_member_id'] = $user->id;
            $params['b2b_admin_id'] = $user->admin_id ?? 1; // Use admin_id field or fallback
        }
        
        // Optional filter parameters from request (only the ones user can control)
        if ($request->filled('visa_form_id')) {
            $params['visa_form_id'] = $request->input('visa_form_id');
        }
        
        // Add task_id filter if provided (from notification link)
        if ($request->filled('task_id')) {
            $taskId = $request->input('task_id');
            
            // Ensure we're using a numeric task ID
            if (!is_numeric($taskId) && preg_match('/\d+/', $taskId, $matches)) {
                $taskId = $matches[0];
            }
            
            $params['task_id'] = $taskId;
        }
        
        $params['return'] = 'full'; // Always get full data
        $params['task_status'] = $request->input('task_status', 'open');
        
        if ($request->filled('deadline_start')) {
            $params['deadline_start'] = $request->input('deadline_start');
        }
        
        if ($request->filled('deadline_end')) {
            $params['deadline_end'] = $request->input('deadline_end');
        }
        
        // Auto-set pagination parameters (not user configurable)
        $params['page'] = $request->input('page', 1);
        $params['limit'] = 20; // Fixed limit of 20 items per page
        
        return $this->taskService->prepareParams($params);
    }
}
