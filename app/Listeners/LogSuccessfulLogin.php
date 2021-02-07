<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Browser;

class LogSuccessfulLogin
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
     * @return void
     */
    public function handle()
    {   
        activity()
        ->causedBy(Auth::user())
        ->withProperties(['ip_address'=>$_SERVER['REMOTE_ADDR'], 'user_agent'=>Browser::platformName().', '.Browser::browserName()])
        ->log('login-successful');

        Auth::user()->last_login = now();
        Auth::user()->save();
    }
}
