<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Lead;
use App\Models\LeadRevert;

class NewLeadRevertNotification extends Notification
{
    use Queueable;

    public $lead;
    public $revert;

    /**
     * Create a new notification instance.
     */
    public function __construct(Lead $lead, LeadRevert $revert)
    {
        $this->lead = $lead;
        $this->revert = $revert;
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
        $leadUrl = url("/leads/{$this->lead->id}");
        
        return (new MailMessage)
            ->subject("New Revert Received for Lead: {$this->lead->ref_no}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A new revert has been received for lead **{$this->lead->name}** ({$this->lead->ref_no}).")
            ->line("**From:** {$this->revert->submitted_by}")
            ->line("**Message:** {$this->revert->revert_message}")
            ->line("**Priority:** " . ucfirst($this->revert->priority))
            ->action('View Lead Details', $leadUrl)
            ->line('Please review and take appropriate action if needed.');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'lead_id' => $this->lead->id,
            'lead_ref_no' => $this->lead->ref_no,
            'lead_name' => $this->lead->name,
            'revert_id' => $this->revert->id,
            'revert_message' => $this->revert->revert_message,
            'submitted_by' => $this->revert->submitted_by,
            'priority' => $this->revert->priority,
            'action_url' => url("/leads/{$this->lead->id}"),
            'title' => "New Revert for {$this->lead->ref_no}",
            'message' => "New revert from {$this->revert->submitted_by}: " . \Str::limit($this->revert->revert_message, 100)
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
