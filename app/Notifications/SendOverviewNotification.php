<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Mail\OverviewMailable;
use App\Models\Overview;
use App\Events\Overview\Sending\Queued as OverviewSendingQueued;
use App\Events\Overview\Sending\Started as OverviewSendingStarted;
use App\Events\Overview\Sending\Failed as OverviewSendingFailed;

class SendOverviewNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var \App\Models\Overview
     */
    protected $overview;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Overview $overview)
    {   
        $this->overview = $overview;
        OverviewSendingQueued::dispatch($this->overview);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

     /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     */
    public function toMail($notifiable): OverviewMailable
    {   
        OverviewSendingStarted::dispatch($this->overview);
        sleep(2);
        return (new OverviewMailable($this->overview))
        ->to($this->overview->administration->contact_email)
        ->cc($notifiable->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function failed($e)
    {
        OverviewSendingFailed::dispatch(
          $this->overview, 
          !empty($reason) ? $reason : 'Er is iets misgegaan::'.$e->getMessage(),
        );
        
    }
}
