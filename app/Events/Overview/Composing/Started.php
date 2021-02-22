<?php

namespace App\Events\Overview\Composing;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Overview;
use Illuminate\Support\Facades\Log;

class Started implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\Overview
     */
    public $overview;

    /**
     * @var array
     */
    public $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Overview $overview)
    {
        $this->overview = $overview;
        $this->status = ['composing', 'Overzicht samenstellen::We zijn het overzicht aan het samenstellen, een ogenblik geduld.'];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'overview.composing.started';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {   
        return [
            new PrivateChannel('overview.'.$this->overview->id),
        ];
    }
}
