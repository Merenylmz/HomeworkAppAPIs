<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgotPassMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    protected $token;
    public function __construct($token)
    {
        $this->token = $token;
    }

    public function build(){
        return $this->view("ForgotPassMail")
        ->subject("Forgot Password Mail HomeworkApp")
        ->with([
            "token"=>$this->token
        ]);

    }
}
