<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HackathonCertificate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $attachment;
    public function __construct($attachment)
    {
        $this->attachment = $attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Devfest'22 Thank you!")->view('emails.hackathon.certificates')->attach(public_path("/participants/".$this->attachment.".pdf"), [
            'as' => 'certificate.pdf',
            'mime' => 'application/pdf',
        ]);
    }
}
