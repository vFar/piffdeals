<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;

class VerifyEmailNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('E-pasta verifikācija')
            ->line('Sveicināti Piffdeals e-veikalā! Lūdzu, apstipriniet savu e-pasta adresi, noklikšķinot uz zemāk redzamās pogas.')
            ->action('Verificēt E-pastu', $verificationUrl)
            ->line('Ja jūs nepieprasījāt šo verifikāciju, nekādi turpmāki soļi nav nepieciešami.');
    }

    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()), // Add the hash parameter
            ]
        );
    }
}
