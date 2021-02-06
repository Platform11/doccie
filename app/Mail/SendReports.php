<?php

namespace App\Mail;


use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Administration;
use App\Models\Report;
Use App\Models\Notification;

class SendReports extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var array
     */
    protected $files;

    /**
     * @var int
     */
    protected $transaction_count;

    /**
     * @var \App\Models\Administration
     */
    protected $administration;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * @var \App\Models\Report
     */
    protected $report;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        array $files, 
        int $transaction_count, 
        Administration $administration, 
        User $user, 
        Report $report)
    {
        $this->files = $files;
        $this->transaction_count = $transaction_count;
        $this->administration = $administration;
        $this->user = $user;
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $mail =  $this->markdown('vendor.notifications.email')
            ->replyTo($this->user->email, $this->user->first_name. ' '. $this->user->last_name)
            // ->from('test@test.com')
            ->subject('Overzicht vraagposten')->with([
                'level' => 'primary',
            'logo' => asset('logo-oneaccountants.svg'),
                'alt' => $this->user->account->name,
                'color' => '77BC1F',
                'greeting' => 'Beste '.$this->administration->contact_first_name .' '. $this->administration->contact_last_name.',',
                'introLines' => ["Hierbij verzoek ik u om voor de ". $this->transaction_count. ' vraagposten de bijbehorende documenten naar mij te mailen of te uploaden naar Basecone. Het overzicht van de openstaande vraagposten is als bijlage toegevoegd aan deze mail.'],
                'outroLines' => ["Graag ontvang ik de documenten zo spoedig mogelijk. Alvast bedankt voor uw medewerking."],
                'salutation' => $this->user->first_name .' '. $this->user->last_name,
            ]);
         
        //Attach extra info to create notifications in the sent event    
        $to = $this->to;
        $cc = $this->cc;
        $bcc = $this->bcc;
        $report = $this->report;
        $author = $this->user;
        $administration = $this->administration;

        $mail = $mail->withSwiftMessage(
            function ($message) use($to, $cc, $bcc, $report, $author, $administration){
                $message->to = $to;
                $message->cc = $cc;
                $message->bcc = $bcc;
                $message->author = $author;
                $message->report = $report;
                $message->administration = $administration;
            }
        );    

        foreach($this->files as $file)
        {
            $mail->attach(\Storage::path($file));
        }
        return $mail;
    }
}
