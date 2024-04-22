<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ContactFormNotification extends Notification
{
    use Queueable;

    public $formData;

    public function __construct($formData)
    {
        $this->formData = $formData;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->formData['title'])
                    ->greeting('Sveiki!')  // Custom greeting
                    ->line('Jauna ziņa no klienta:')
                    ->line('E-pasts: ' . $this->formData['email'])
                    ->line('Temats: ' . $this->formData['title'])
                    ->line('Ziņojums: ' . $this->formData['textMessage'])
                    ->replyTo($this->formData['email']); // Add a reply-to header with the client's email
    }

    public function toArray($notifiable)
    {
        return [
            'email' => $this->formData['email'],
            'title' => $this->formData['title'],
            'message' => $this->formData['textMessage'],
        ];
    }

    
}
