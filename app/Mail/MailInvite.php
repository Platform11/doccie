<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class MailInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var \App\Models\User
     */
    protected $inviter;

    /**
     * @var \App\Models\User
     */
    protected $invitee;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, User $inviter, User $invitee)
    {
        $this->token = $token;
        $this->inviter = $inviter;
        $this->invitee = $invitee;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('vendor.notifications.email')
                    ->subject('Uitnoding voor Doccie!')->with([
                        'level' => 'primary',
                        'greeting' => 'Beste '.$this->invitee->first_name,
                        'introLines' => [$this->inviter->first_name. ' van '.$this->inviter->account->name.' heeft je uitgenodigd voor Doccie. Met Doccie kan je eenvoudig overzichten van vraagposten genereren en versturen naar klanten.'],
                        'actionText' => 'Uitnoding accepteren',
                        'outroLines' => [],
                        'actionUrl' => route('invite.accept', ['token' => $this->token]),
                        'salutation' => 'Dit is een automatisch gegenereerd bericht',
                        'displayableActionUrl' => route('invite.accept', ['token' => $this->token]),
                        'displayableActionText' => route('invite.accept', ['token' => $this->token]),
                    ]);
    }
}
