<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use Spatie\Activitylog\Traits\LogsActivity;
use Browser;

class LogFailedLogin
{
    use LogsActivity;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  Failed  $event
     * @return void
     */
    public function handle(Failed $event)
    {   
        activity()
        ->withProperties(['username' => $event->credentials['email'], 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'user_agent'=>Browser::platformName() . ', '. Browser::browserName()])
        ->log('login-failed');
    }
}
