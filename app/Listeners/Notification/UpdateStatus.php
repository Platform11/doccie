<?php

namespace App\Listeners\Notification;

class UpdateStatus
{

    public function update($event) {

        if(!empty($event->notification))
        {
            $event->notification->setStatus($event->status[0], $event->status[1]);

            if(!empty($event->delivery_meta_data))
            {
                $event->notification->setDeliveryMetaData($event->delivery_meta_data);
            }
        }
        
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            'App\Events\Notification\Delivered',
            'App\Listeners\Notification\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Notification\Bounced',
            'App\Listeners\Notification\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Notification\Sent',
            'App\Listeners\Notification\UpdateStatus@update'
        );
    }

}