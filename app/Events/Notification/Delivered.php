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

class Delivered implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\Models\Notification
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
        $this->status = ['delivered', 'Notificatie succesvol afgeleverd'];
        $this->delivery_meta_data = $delivery_meta_data;
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'notification.delivered';
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