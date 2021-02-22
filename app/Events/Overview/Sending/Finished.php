<?php

namespace App\Events\Overview\Sending;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Overview;
use Illuminate\Contracts\Queue\ShouldQueue;

class Finished implements ShouldBroadcast
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
        $this->status = ['sent', 'Overzicht is verzonden::Het overzicht is succesvol verzonden. Zodra het overzicht bij alle geadresseerden is aangekomen zal de status worden aangepast naar "afgeleverd".'];
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'overview.sending.finished';
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
