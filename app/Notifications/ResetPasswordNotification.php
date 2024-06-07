<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email, // Ensure this is the correct attribute for the user's email
        ]);
    
        return (new MailMessage)
            ->subject('Paroles Atiestatīšana')
            ->line('Jūs saņēmāt šo e-pastu, jo mēs saņēmām paroles atiestatīšanas pieprasījumu jūsu kontam.')
            ->action('Atiestatīt Paroli', $url)
            ->line('Ja jūs nepieprasījāt paroles atiestatīšanu, turpmākas darbības nav nepieciešamas.');
    }
    
}
