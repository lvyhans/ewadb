<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\TaskWebhookController;
use App\Models\User;
use App\Notifications\TaskStatusChangedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class TaskWebhookTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_id_is_properly_extracted()
    {
        // Create a user
        $user = User::factory()->create();

        // Mock notification sending
        Notification::fake();

        // Set up request data with a complex task ID
        $requestData = [
            'b2b_member_id' => $user->id,
            'task_id' => 'task-456-fix-redirect',
            'task_name' => 'Test Task',
            'task_status' => 'created'
        ];

        // Create a request instance
        $request = Request::create('/api/webhooks/tasks', 'POST', $requestData);

        // Call the controller method
        $controller = app(TaskWebhookController::class);
        $response = $controller->handleTaskWebhook($request);

        // Assert notification was sent with correct task ID
        Notification::assertSentTo(
            $user,
            TaskStatusChangedNotification::class,
            function ($notification, $channels) {
                // Check if the numeric task ID was extracted correctly
                // Access the taskId property (this may require making it protected instead of private)
                $reflectionClass = new \ReflectionClass($notification);
                $property = $reflectionClass->getProperty('taskId');
                $property->setAccessible(true);
                $taskId = $property->getValue($notification);
                
                return $taskId === '456';
            }
        );

        // Assert the response is successful
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(true, $response->getData()->success);
    }
}
