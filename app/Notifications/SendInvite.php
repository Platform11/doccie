<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\MailInvite as Mailable;

class SendInvite extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var \App\Models\User
     */
    protected $inviter;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $inviter)
    {
        $this->token = $token;
        $this->inviter = $inviter;
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
    public function toMail($notifiable): Mailable
    {   
        return (new Mailable(
            $this->token,
            $this->inviter,
            $notifiable
        ))
        ->to($notifiable->email);
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
}
