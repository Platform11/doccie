<?php

namespace App\Listeners\Overview;

class UpdateStatus
{

    public function update($event) {  
        if(!empty($event->overview))
        {
          $event->overview->setStatus($event->status[0], $event->status[1]);
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
            'App\Events\Overview\Composing\Queued',
            'App\Listeners\Overview\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Composing\Started',
            'App\Listeners\Overview\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Composing\Failed',
            'App\Listeners\Overview\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Sending\Started',
            'App\Listeners\Overview\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Sending\Finished',
            'App\Listeners\Overview\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Sending\Failed',
            'App\Listeners\Overview\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Delivery\Successful',
            'App\Listeners\Overview\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Delivery\Failed',
            'App\Listeners\Overview\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Illuminate\Mail\Events\NotificationFailed',
            'App\Listeners\Overview\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Report\Failed',
            'App\Listeners\Overview\UpdateStatus@update'
        );
    }

}