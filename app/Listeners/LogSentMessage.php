<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSent;
use App\Models\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        if(!empty($event->message->report))
        {   
            foreach($event->message->to as $recipient)
            {
                $this->createNotificationRecord(
                    $event->message->report, 
                    $event->message->author, 
                    $recipient['address'], 
                );
            }

            foreach($event->message->cc as $recipient)
            {
                $this->createNotificationRecord(
                    $event->message->report, 
                    $event->message->author, 
                    $recipient['address'], 
                );
            }

            foreach($event->message->bcc as $recipient)
            {
                $this->createNotificationRecord(
                    $event->message->report, 
                    $event->message->author, 
                    $recipient['address'], 
                );
            }

            $event->message->administration->status = "sent";
            $event->message->administration->save();
            $this->deleteFiles($event->message->files);
        }
    }

    private function createNotificationRecord($subject, $sender, $recipient)
    {
        $notification = new Notification;
        $notification->subject()->associate($subject);
        $notification->sender()->associate($sender);
        $notification->channel = 'mail';
        $notification->recipient = $recipient;
        $notification->save();
    }

    private function deleteFiles($files)
    {
        foreach($files as $file)
        {
            $parts = explode('/', $file);
            \Storage::deleteDirectory($parts[0]);
        }
    }
}
