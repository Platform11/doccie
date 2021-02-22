<?php

namespace App\Listeners\Administration;

class UpdateStatus
{

    public function update($event) {

        if(!empty($event->status) && $event->status[0] == 'queued') 
        {
          $event->overview->administration->setStatus('overview_queued', 'In de wachtrij::Het overzicht staat in de wachtrij en zal automatisch in behandeling worden genomen.');
        }

        if(!empty($event->status) && $event->status[0] == 'composing')
        {
          $event->overview->administration->setStatus('overview_composing', $event->status[1]);
        }

        if(!empty($event->status) && $event->status[0] == 'failed')
        {
          $event->overview->administration->setStatus('overview_failed', $event->status[1]);
        }

        if(!empty($event->status) && $event->status[0] == 'sending')
        {
          $event->overview->administration->setStatus('overview_sending', $event->status[1]);
        }

        if(!empty($event->status) && $event->status[0] == 'sent')
        {
          $event->overview->administration->setStatus('ready');
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
            'App\Listeners\Administration\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Composing\Started',
            'App\Listeners\Administration\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Composing\Failed',
            'App\Listeners\Administration\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Sending\Started',
            'App\Listeners\Administration\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Sending\Finished',
            'App\Listeners\Administration\UpdateStatus@update'
        );

        $events->listen(
            'App\Events\Overview\Sending\Failed',
            'App\Listeners\Administration\UpdateStatus@update'
        );
    }

}