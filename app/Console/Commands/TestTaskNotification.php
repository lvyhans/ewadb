<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\TaskStatusChangedNotification;
use Illuminate\Console\Command;

class TestTaskNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:task-notification {user_id} {task_id} {task_name} {task_status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test task notification with a given task ID';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $taskId = $this->argument('task_id');
        $taskName = $this->argument('task_name');
        $taskStatus = $this->argument('task_status');
        
        $user = User::findOrFail($userId);
        
        $this->info("Testing notification for user: {$user->name}");
        $this->info("Original task ID: {$taskId}");
        
        // Create notification with task ID
        $notification = new TaskStatusChangedNotification($taskId, $taskName, $taskStatus);
        
        // Use reflection to get the processed task ID
        $reflectionClass = new \ReflectionClass($notification);
        $property = $reflectionClass->getProperty('taskId');
        $property->setAccessible(true);
        $processedTaskId = $property->getValue($notification);
        
        $this->info("Processed task ID: {$processedTaskId}");
        
        // Send notification
        $user->notify($notification);
        
        $this->info("Notification sent successfully!");
        
        return 0;
    }
}
