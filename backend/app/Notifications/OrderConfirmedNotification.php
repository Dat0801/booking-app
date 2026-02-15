<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));
        $orderUrl = $frontendUrl.'/orders/'.$this->order->id;

        $message = (new MailMessage)
            ->subject('Order Confirmation - '.$this->order->order_number)
            ->greeting('Hello '.$this->order->user->name.',')
            ->line('Your order has been '.$this->order->status.' successfully.')
            ->line('**Order Details:**')
            ->line('Order Number: '.$this->order->order_number)
            ->line('Total Amount: '.$this->order->currency.' '.number_format($this->order->total_amount, 2))
            ->line('Payment Status: '.ucfirst($this->order->payment_status));

        if ($this->order->items->isNotEmpty()) {
            $message->line('**Items:**');
            foreach ($this->order->items as $item) {
                $message->line('- '.$item->name.' (x'.$item->quantity.') - '.$this->order->currency.' '.number_format($item->subtotal, 2));
            }
        }

        return $message
            ->action('View Order', $orderUrl)
            ->line('Thank you for your purchase!');
    }
}
