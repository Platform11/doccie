<?php

namespace App\Events\Notification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Notification;
use App\Models\Overview;

class Bounced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\Overview
     */
    public $notification;

    /**
     * @var array
     */
    public $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Notification $notification, $delivery_meta_data = null)
    {
        $this->notification = $notification;
        $this->status = ['bounced', 'Notificatie is niet afgeleverd bij de ontvanger. Neem voor meer informatie contact op met de applicatiebeheerders'];
        $this->delivery_meta_data = $delivery_meta_data;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'nofification.bounced';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {   
        return new PrivateChannel('channel-name');
    }
}
