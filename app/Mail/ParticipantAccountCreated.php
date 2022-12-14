<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParticipantAccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $password;
    public $email;
    public $track;
    public function __construct($email, $password, $track)
    {
        $this->password = $password;
        $this->email = $email;
        $this->track = $track;
    }

    /**
     * Build the message.
     *
     * @return $this
     */



    public function build()
    {
        return $this->subject('Devfest22 Challenges Account Created!')->view('emails.accounts.participant_created');
    }
}
