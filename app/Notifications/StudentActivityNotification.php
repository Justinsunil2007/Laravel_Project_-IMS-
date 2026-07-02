<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class StudentActivityNotification extends Notification
{
    use Queueable;

    public function __construct(
        public string $title,
        public string $message,
        public string $status,
        public ?string $achievementTitle = null,
        public ?string $remarks = null
    ) {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'title' => $this->title,
            'message' => $this->message,
            'status' => $this->status,
            'achievement_title' => $this->achievementTitle,
            'remarks' => $this->remarks,
        ];
    }
}
