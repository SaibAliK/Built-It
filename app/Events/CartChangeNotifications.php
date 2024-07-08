<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CartChangeNotifications implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $count, $userId;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($count,$id)
    {
        $this->count = $count;
        $this->userId = $id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('build-it-'.$this->userId);
    }

    public function broadcastAs()
    {
        return 'new-cart-notification';
    }
}
