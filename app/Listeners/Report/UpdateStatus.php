<?php

namespace App\Listeners\Report;

class UpdateStatus
{

    public function update($event) {

        if(!empty($event->report))
        {
        $event->report->setStatus($event->status[0], $event->status[1]);
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
            'App\Events\Report\Composing\Started',
            'App\Listeners\Report\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Report\Composing\Finished',
            'App\Listeners\Report\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Report\Composing\Failed',
            'App\Listeners\Report\UpdateStatus@update'
        );
    }

}