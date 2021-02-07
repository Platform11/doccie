<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Spatie\Activitylog\Traits\LogsActivity;
use Browser;

class LogSuccessfulLogout
{
    use LogsActivity;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        activity()
        ->causedBy($event->user)
        ->withProperties(['ip_address'=>$_SERVER['REMOTE_ADDR'], 'user_agent'=>Browser::platformName().', '.Browser::browserName()])
        ->log('logout');
    }
}
