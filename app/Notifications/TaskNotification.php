<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TaskNotification extends Notification
{
    use Queueable;

    public $message;
    public $taskId;

    /**
     * Create a new notification instance.
     */
    public function __construct($message, $taskId)
    {
        $this->message = $message;
        $this->taskId = $taskId;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->message,
            'task_id' => $this->taskId,
        ];
    }
}
