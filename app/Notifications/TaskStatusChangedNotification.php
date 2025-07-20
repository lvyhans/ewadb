<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskStatusChangedNotification extends Notification
{

    protected $taskId;
    protected $taskName;
    protected $taskStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $taskId, string $taskName, string $taskStatus)
    {
        // Ensure we're using a numeric task ID
        $this->taskId = is_numeric($taskId) ? $taskId : (preg_match('/\d+/', $taskId, $matches) ? $matches[0] : $taskId);
        $this->taskName = $taskName;
        $this->taskStatus = $taskStatus;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = match($this->taskStatus) {
            'created' => 'A new task has been created',
            'reopen' => 'A task has been reopened',
            'removed' => 'A task has been removed',
            default => 'A task status has changed'
        };

        return (new MailMessage)
            ->subject("Task Update: {$this->taskName}")
            ->line($statusText)
            ->line("Task: {$this->taskName}")
            ->line("Task ID: {$this->taskId}")
            ->action('View Task', url('/task-management?task_id=' . $this->taskId))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->taskId,
            'task_name' => $this->taskName,
            'task_status' => $this->taskStatus,
            'message' => $this->getNotificationMessage(),
            'timestamp' => now()->toIso8601String(),
            'action_url' => url('/task-management?task_id=' . $this->taskId),
            'title' => 'Task Update: ' . $this->taskName,
            'priority' => $this->taskStatus === 'created' ? 'normal' : ($this->taskStatus === 'reopen' ? 'medium' : 'high')
        ];
    }

    /**
     * Get a human-readable notification message
     */
    private function getNotificationMessage(): string
    {
        return match($this->taskStatus) {
            'created' => "New task created: {$this->taskName}",
            'reopen' => "Task has been reopened: {$this->taskName}",
            'removed' => "Task has been removed: {$this->taskName}",
            default => "Task status updated: {$this->taskName}"
        };
    }
}
