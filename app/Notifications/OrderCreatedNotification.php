<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url('/profile/orders');

        $mailMessage = (new MailMessage)
            ->subject('Pasūtījuma rēķins')
            ->greeting('Sveiki ' . $notifiable->name . '!')
            ->line('Paldies par Jūsu pasūtījumu.')
            ->line('Pasūtījuma ID: ' . $this->order->id);

        // Adding each item details
        // foreach ($this->order->items as $item) {
        //     $mailMessage->line($item->good->name . ' - Daudzums: ' . $item->quantity . ' - Cena par vienību: $' . number_format($item->good->price, 2));
        // }

        $mailMessage->line('Kopsumma: ' . number_format($this->order->total, 2) . ' €')
            ->action('Apskatīt pasūtījumu', $url)
            ->line('Pasūtījuma statusu ir iespējams apskatīt profila sadaļā!')
            ->line('Pasūtījumu var atcelt, sazinoties ar administrāciju kontaktu saziņas lapā!');

        return $mailMessage;
    }
}
