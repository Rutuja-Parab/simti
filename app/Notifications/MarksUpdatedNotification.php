<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MarksUpdatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $marks;

    /**
     * Create a new notification instance.
     */
    public function __construct($marks)
    {
        // $marks can be your Marks model instance
        $this->marks = $marks;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database']; // store in DB only
    }

    /**
     * Get the array representation of the notification for the database channel.
     */
    public function toDatabase($notifiable): array
    {
        $studentName = $this->marks->candidate->name ?? 'Unknown Student';
        $subjectName = $this->marks->subject->name ?? 'Unknown Subject';

        return [
            'message' => "Marks updated for {$studentName} in {$subjectName}.",
            'student_id' => $this->marks->student_id,
            'subject_id' => $this->marks->subject_id,
            'marks_id' => $this->marks->id,
            'url' => route('marks.approvals'),
        ];
    }
}
