<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use App\Models\Notification;
use App\Events\Overview\Sending\Finished as OverviewSendingFinished;
use App\Events\Notification\Sent as NotificationSent;

class LogSentMessage
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
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $recipients = array_merge(
            $event->message->to, 
            $event->message->cc, 
            $event->message->bcc
        );

        foreach($recipients as $recipient)
        {
            $notification = $this->createNotificationRecord($event->message->overview, $recipient['address']); 
            NotificationSent::dispatch($notification);
        }

        $this->deleteReportFiles($event->message->overview);

        OverviewSendingFinished::dispatch($event->message->overview);
    }

    private function createNotificationRecord($overview, $recipient)
    {
        $notification = new Notification;
        $notification->subject()->associate($overview);
        $notification->sender()->associate($overview->author);
        $notification->channel = 'mail';
        $notification->recipient = $recipient;
        $notification->save();

        return $notification;
    }

    private function deleteReportFiles($overview)
    {
        foreach($overview->reports as $report)
        {
            $report->getMedia()[0]->delete();
        }
    }
}
