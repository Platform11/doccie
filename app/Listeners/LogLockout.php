<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Lockout;
use Spatie\Activitylog\Traits\LogsActivity;
use Browser;

class LogLockoutAccount
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
     * @param  Lockout $event
     * @return void
     */
    public function handle(Lockout $event)
    {   
        activity()
        ->withProperties(['username' => $event->request['email'], 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'user_agent'=>Browser::platformName().', '.Browser::browserName()])
        ->log('login-lockout');
    }
}
