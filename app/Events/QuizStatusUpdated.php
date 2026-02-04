<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizStatusUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $quizId;
    public $roomName;
    public $start;
    public function __construct($quizId, $roomName,$start )
    {
        $this->quizId = $quizId;
        $this->roomName = $roomName;
        $this->start = $start;
    }
}
