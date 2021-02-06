<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Invite;
use Illuminate\Support\Str;
use App\Notifications\SendInvite;


class InviteUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\User
     */
    protected $inviter;

    /**
     * @var \App\Models\User
     */
    protected $invitee;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $inviter, User $invitee)
    {
        $this->inviter = $inviter;
        $this->invitee = $invitee;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $invite = new Invite;
        $invite->user_id = $this->invitee->id;
        $invite->token = Str::random(24);
        $invite->save();

        $this->invitee->notify(new SendInvite($invite->token, $this->inviter));
    }
}
