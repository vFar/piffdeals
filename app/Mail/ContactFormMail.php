<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable
{
    use Queueable, SerializesModels;

    public $formData;
    public $toAddress;

    public function __construct($formData, $toAddress)
    {
        $this->formData = $formData;
        $this->toAddress = $toAddress;
    }

    public function build()
    {
        return $this->from('no-reply@example.com') // Use a static "from" address or pull from .env
                    ->view('emails.contact')
                    ->with([
                        'formData' => $this->formData,
                    ]);
    }
    
}
