<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
Use App\Models\Overview;

class OverviewMailable extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\Overview
     */
    protected $overview;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Overview $overview)
    {
        $this->overview = $overview;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $mail =  $this->markdown('vendor.notifications.email')
        ->replyTo($this->overview->author->email, $this->overview->author->first_name. ' '. $this->overview->author->last_name)
        ->subject('Overzicht vraagposten')->with([
            'level' => 'primary',
            'logo' => $this->overview->author->account->logo,
            'alt' => $this->overview->author->account->name,
            'color' => '77BC1F',
            'greeting' => 'Beste '.$this->overview->administration->contact_first_name .' '. $this->overview->administration->contact_last_name.',',
            'introLines' => ["Hierbij verzoek ik u om de missende documenten voor de vraagposten te uploaden in Basecone. Het overzicht van de openstaande vraagposten is als bijlage toegevoegd aan deze mail."],
            'outroLines' => ["Met vriendelijke groet"],
            'salutation' => $this->overview->author->first_name .' '. $this->overview->author->last_name,
        ]);

        //Attach extra info to create notifications in the sent event    
        $to = $this->to;
        $cc = $this->cc;
        $bcc = $this->bcc;
        $overview = $this->overview;

        $mail = $mail->withSwiftMessage(
            function ($message) use($to, $cc, $bcc, $overview){
                $message->to = $to;
                $message->cc = $cc;
                $message->bcc = $bcc;
                $message->overview = $overview;
                $headers = $message->getHeaders();
                $headers->addTextHeader('X-PM-Metadata-model', 'Overview');
                $headers->addTextHeader('X-PM-Metadata-model_id', $overview->id);
            }
        );    

        foreach($this->overview->reports as $report)
        {   
            $file_name = '';
            if($report->type == 'call_posts')
            {
                $file_name = 'Vraagposten';
            }
            if($report->type == 'debtors')
            {
                $file_name = 'Debiteurenoverzicht';
            }
            if($report->type == 'creditors')
            {
                $file_name = 'Crediteurenoverzicht';
            }
            $file_name = $file_name .' '. $report->overview->administration->name . ' - '.date('d-m-Y').'.pdf';

            $mail->attach($report->getMedia()->first()->getPath(), [
                'as' => $file_name,
                'mime' => 'application/pdf'
            ]);
        }
        return $mail;
    }
}
