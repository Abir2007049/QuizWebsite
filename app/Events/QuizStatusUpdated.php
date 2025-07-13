<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuizStatusUpdated implements ShouldBroadcastNow
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

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn()
    {
         
              return  new Channel('room.' . $this->roomName);
        
    }
    public function broadcastWith()
{
    \Log::info('Broadcasting QuizStatusUpdated', ['quizId' => $this->quizId, 'start' => $this->start]);
    return [
        'quizId' => $this->quizId,
        'start' => $this->start,
    ];
}

}
