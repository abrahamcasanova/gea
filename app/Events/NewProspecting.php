<?php

namespace App\Events;

use App\Prospecting;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewProspecting
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $prospecting;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($prospecting)
    {
        //
        $this->prospecting = $prospecting;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ['task-event'];
    }

    public function broadcastAs()
    {
        return 'task-event';
    }
}
