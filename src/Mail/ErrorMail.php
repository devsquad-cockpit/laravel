<?php

namespace Cockpit\Mail;

use Cockpit\Models\Error;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    public Error $error;

    public function __construct(Error $error)
    {
        $this->error = $error;
    }

    public function build()
    {
        return $this
        ->view('cockpit::emails.error_report')
        ->subject('Test subject');
    }
}
