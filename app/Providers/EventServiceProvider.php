<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use Spatie\ModelStatus\Status;
use App\Observers\StatusObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        'Illuminate\Mail\Events\MessageSent' => [
            'App\Listeners\LogSentMessage',
        ],

        'Illuminate\Mail\Events\NotificationFailed' => [],

        'Illuminate\Auth\Events\Login' => [
            'App\Listeners\LogSuccessfulLogin',
        ],

        'Illuminate\Auth\Events\Failed' => [
            'App\Listeners\LogFailedLogin',
        ],

        'Illuminate\Auth\Events\Logout' => [
            'App\Listeners\LogSuccessfulLogout',
        ],

        'Illuminate\Auth\Events\Lockout' => [
            'App\Listeners\LogLockout',
        ],

        'Illuminate\Auth\Events\PasswordReset' => [
            'App\Listeners\LogPasswordReset',
        ],

        'App\Events\Overview\Composing\Queued' => [],

        'App\Events\Overview\Composing\Started' => [],

        'App\Events\Overview\Composing\Finished' => [],

        'App\Events\Overview\Composing\Failed' => [],

        'App\Events\Overview\Sending\Queued' => [],

        'App\Events\Overview\Sending\Started' => [],

        'App\Events\Overview\Sending\Finished' => [],

        'App\Events\Overview\Sending\Failed' => [],

        'App\Events\Overview\Notification\Delivered' => [],

        'App\Events\Overview\Notification\Bounced' => [],

        'App\Events\Overview\Notification\Status\Updated' => [],

        'App\Events\Report\Composing\Started' => [],

        'App\Events\Report\Composing\Finished' => [],

        'App\Events\Report\Composing\Failed' => [],

        'App\Events\Administration\Status\Updated' => [],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        'App\Listeners\Administration\UpdateStatus',
        'App\Listeners\Overview\UpdateStatus',
        'App\Listeners\Report\UpdateStatus',
        'App\Listeners\Notification\UpdateStatus',
        'App\Listeners\Overview\CheckDeliveryStatus',
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Status::observe(StatusObserver::class);
    }
}
