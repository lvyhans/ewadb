<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\TaskStatusChangedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;

class TaskWebhookController extends Controller
{
    /**
     * Handle incoming task status webhooks from Tarundemo
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleTaskWebhook(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'b2b_member_id' => 'required|integer|exists:users,id',
            'task_id' => 'required|string',
            'task_name' => 'required|string|max:255',
            'task_status' => 'required|string|in:created,reopen,removed'
        ]);

        if ($validator->fails()) {
            Log::warning('Invalid task webhook received', [
                'errors' => $validator->errors()->toArray(),
                'payload' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Get validated data
            $data = $validator->validated();
            
            // Find the user
            $user = User::findOrFail($data['b2b_member_id']);
            
            // Log the webhook
            Log::info('Task webhook received', [
                'user_id' => $data['b2b_member_id'],
                'task_id' => $data['task_id'],
                'task_name' => $data['task_name'],
                'task_status' => $data['task_status']
            ]);

            // Extract numeric task ID from the task ID string (e.g., "task-456-fix-redirect" becomes "456")
            $numericTaskId = preg_match('/\d+/', $data['task_id'], $matches) ? $matches[0] : $data['task_id'];
            
            // Create and send notification
            $user->notify(new TaskStatusChangedNotification(
                $numericTaskId,
                $data['task_name'],
                $data['task_status']
            ));
            
            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Task notification created successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Failed to process task webhook', [
                'error' => $e->getMessage(),
                'payload' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process task webhook: ' . $e->getMessage()
            ], 500);
        }
    }
}
