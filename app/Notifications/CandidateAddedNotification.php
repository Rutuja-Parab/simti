<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// app/Notifications/CandidateAddedNotification.php
class CandidateAddedNotification extends Notification
{
    use Queueable;

    protected $candidate;

    public function __construct($candidate)
    {
        $this->candidate = $candidate;
    }

    public function via($notifiable)
    {
        return ['database']; // stores in DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "New candidate {$this->candidate->name} added.",
            'candidate_id' => $this->candidate->id,
            'url' => route('candidate.view'),
        ];
    }
}
