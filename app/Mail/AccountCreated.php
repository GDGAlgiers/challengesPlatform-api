<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use \Illuminate\Mail\Mailables\Content;

class AccountCreated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $password;
    public $name;
    public function __construct($name, $password)
    {
        $this->password = $password;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function envelope()
    {
        return new Envelope(
            from: new Address('km_rezazi@esi.dz', 'Abdessamed Rezazi'),
            subject: 'Devfest22 acount created!',
        );
    }


    public function build()
    {
        return $this->subject('Devfest22 account created!')->view('emails.accounts.created');
    }
}
