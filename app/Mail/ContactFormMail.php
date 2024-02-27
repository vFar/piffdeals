<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $formData;
    public $fromAddress;

    public function __construct($formData, $fromAddress)
    {
        $this->formData = $formData;
        $this->fromAddress = $fromAddress;
    }

    public function build()
    {
        return $this->from($this->fromAddress)
                    ->view('emails.contact'); // Adjust the view name as per your setup
    }
}
