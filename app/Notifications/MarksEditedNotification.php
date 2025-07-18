<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class MarksEditedNotification extends Notification
{
    use Queueable;

    public $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toArray($notifiable)
    {
        return [
            'faculty' => $this->details['faculty'],
            'course' => $this->details['course'],
            'batch' => $this->details['batch'],
            'candidate' => $this->details['candidate'],
            'subject' => $this->details['subject'],
            'message' => $this->details['message'] ?? 'Marks were edited by faculty.'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
} 