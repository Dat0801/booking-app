<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Notifications\BookingReminderNotification;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    protected $signature = 'bookings:send-reminders {--hours=24 : Hours before booking to send reminder}';

    protected $description = 'Send reminder emails for upcoming bookings';

    public function handle(): int
    {
        $hours = (int) $this->option('hours');
        $targetDate = now()->addHours($hours)->startOfDay();
        $nextDay = $targetDate->copy()->addDay();

        $bookings = Booking::query()
            ->whereIn('status', ['pending', 'confirmed'])
            ->whereDate('scheduled_date', '>=', $targetDate)
            ->whereDate('scheduled_date', '<', $nextDay)
            ->whereDoesntHave('review', function ($query) {
                $query->where('created_at', '>=', now()->subHours(24));
            })
            ->with(['user', 'product'])
            ->get();

        $count = 0;

        foreach ($bookings as $booking) {
            $booking->user->notify(new BookingReminderNotification($booking));
            $count++;
        }

        $this->info("Sent {$count} booking reminder(s).");

        return Command::SUCCESS;
    }
}
