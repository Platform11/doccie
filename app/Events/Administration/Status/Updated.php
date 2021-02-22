<?php

namespace App\Events\Administration\Status;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Administration;

class Updated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\Administration
     */
    public $administration;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Administration $administration)
    {
        $this->administration = $administration;
        $administration->load('last_status', 'relation_manager');
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'administration.status.updated';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {   
        return [
            new PrivateChannel('account.'.$this->administration->account->id),
            new PrivateChannel('administration.'.$this->administration->id),
        ];
    }
}
