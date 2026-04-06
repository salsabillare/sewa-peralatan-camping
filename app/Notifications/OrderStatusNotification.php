<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // bisa email + database (untuk frontend)
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject("Status Pesanan #{$this->order->id} Update")
                    ->greeting("Halo {$notifiable->name},")
                    ->line("Status pesanan Anda sekarang: {$this->order->status}.")
                    ->action('Lihat Pesanan', url(route('customer.orders.show', $this->order->id)))
                    ->line('Terima kasih telah berbelanja!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'message' => "Status pesanan Anda sekarang: {$this->order->status}.",
        ];
    }
}
