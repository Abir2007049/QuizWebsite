<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuizViolationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentId;

    public function __construct($studentId)
    {
        $this->studentId = $studentId;
    }

    public function build()
    {
        return $this->subject('Quiz Violation Alert')
                    ->view('emails.quiz_violation')
                    ->with([
                        'studentId' => $this->studentId,
                    ]);
    }
}
