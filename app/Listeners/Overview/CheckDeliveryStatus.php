<?php

namespace App\Listeners\Overview;

use App\Models\Overview;
use App\Events\Overview\Delivery\Successful as OverviewDeliverySuccessful;
use App\Events\Overview\Delivery\Failed as OverviewDeliveryFailed;
use Illuminate\Support\Facades\Log;

class CheckDeliveryStatus
{

    public function check($event) {  

        if($event->notification->subject_type == 'App\Models\Overview')
        {   
            $overview = Overview::find($event->notification->subject_id);
            
            if($overview->status === 'sent' && $overview->allNotificationsDelivered())
            {
                OverviewDeliverySuccessful::dispatch($overview);
            }
            if($overview->status === 'sent' && $overview->hasBouncedNotifications())
            {
                OverviewDeliveryFailed::dispatch($overview);
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
            'App\Listeners\Overview\CheckDeliveryStatus@check'
        );
    }

}