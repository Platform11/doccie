<?php

namespace App\Observers;

use Spatie\ModelStatus\Status;
use App\Models\Administration;
use App\Events\Administration\Status\Updated as AdministrationStatusUpdated;
use App\Models\Overview;
use App\Events\Overview\Status\Updated as OverviewStatusUpdated;
use App\Models\Notification;
use App\Events\Notification\Status\Updated as NotificationStatusUpdated;

class StatusObserver
{
    /**
     * Handle the Status "created" event.
     *
     * @param  \Spatie\ModelStatus\Status  $status
     * @return void
     */
    public function created(Status $status)
    {
        switch ($status->model_type) {
            case 'App\Models\Administration':
                AdministrationStatusUpdated::dispatch(Administration::find($status->model_id));
                break;
            case 'App\Models\Overview':
                OverviewStatusUpdated::dispatch(Overview::find($status->model_id));
                break;
            case 'App\Models\Notification':
                NotificationStatusUpdated::dispatch(Notification::find($status->model_id));
                break;
        }
    }
}
