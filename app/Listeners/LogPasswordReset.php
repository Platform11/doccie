<?php

namespace App\Listeners;

use Illuminate\Auth\Events\PasswordReset;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Models\User;
use Browser;

class LogPasswordReset
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
     * @param  PasswordReset  $event
     * @return void
     */
    public function handle(PasswordReset $event)
    {
        activity()
        ->causedBy($event->user)
        ->withProperties(['ip_address'=>$_SERVER['REMOTE_ADDR'], 'user_agent'=>Browser::platformName().', '.Browser::browserName()])
        ->log('password-reset');

        if($event->user->status == 'invited')
        {   
            $user = User::find($event->user->id);
            $user->status = 'active';
            $user->save();
        }
    }
}
