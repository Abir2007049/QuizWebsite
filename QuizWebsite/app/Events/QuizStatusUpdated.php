<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public $quizId;
    public $roomName;
    public $status;
    public function __construct($quizId, $roomName, $status)
    {
        $this->quizId = $quizId;
        $this->roomName = $roomName;
        $this->status = $status;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
             new PrivateChannel('room.' . $this->roomName),
        ];
    }
    public function broadcastWith()
    {
        return [
            'quizId' => $this->quizId,
            'status' => $this->status,
        ];
    }
}
