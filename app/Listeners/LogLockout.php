<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Browser;

class LogLockout
{
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
     * @param  Lockout  $event
     * @return void
     */
    public function handle(Lockout $event)
    {
        activity()
        ->causedBy($event->user)
        ->withProperties(['username' => $event->credentials['email'], 'ip_address'=>$_SERVER['REMOTE_ADDR'], 'user_agent'=>Browser::platformName().', '.Browser::browserName()])
        ->log('account-lockout');
    }
}
