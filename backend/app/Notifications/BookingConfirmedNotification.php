<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingConfirmedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Booking $booking) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));
        $bookingUrl = $frontendUrl.'/bookings/'.$this->booking->id;

        return (new MailMessage)
            ->subject('Booking Confirmation - '.$this->booking->booking_number)
            ->greeting('Hello '.$this->booking->user->name.',')
            ->line('Your booking has been '.$this->booking->status.' successfully.')
            ->line('**Booking Details:**')
            ->line('Booking Number: '.$this->booking->booking_number)
            ->line('Service: '.$this->booking->product->name)
            ->line('Date: '.$this->booking->scheduled_date->format('F d, Y'))
            ->line('Time: '.$this->booking->start_time->format('H:i').($this->booking->end_time ? ' - '.$this->booking->end_time->format('H:i') : ''))
            ->line('Amount: $'.number_format($this->booking->total_amount, 2))
            ->line('Payment Status: '.ucfirst($this->booking->payment_status))
            ->action('View Booking', $bookingUrl)
            ->line('Thank you for choosing our service!');
    }
}
