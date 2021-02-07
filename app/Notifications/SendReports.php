<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Mail\SendReports as Mailable;
use App\Models\Administration;
use App\Models\Report;

class SendReports extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @var array
     */
    protected $files;

    /**
     * @var array
     */
    protected $directories;

    /**
     * @var int
     */
    protected $transaction_count;

    /**
     * @var \App\Models\Administration
     */
    protected $administration;

    /**
     * @var \App\Models\Report
     */
    protected $report;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $files, array $directories, int $transaction_count, Administration $administration, Report $report)
    {
        $this->files = $files;
        $this->directories = $directories;
        $this->transaction_count = $transaction_count;
        $this->administration = $administration;
        $this->report = $report;
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
            $this->files,
            $this->directories,
            $this->transaction_count,
            $this->administration,
            $notifiable,
            $this->report,
        ))
        ->to($this->administration->contact_email)
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
}
