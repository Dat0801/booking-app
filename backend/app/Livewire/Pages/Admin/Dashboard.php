<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Booking;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard · Booking App Admin')]
class Dashboard extends Component
{
    public int $usersCount = 0;

    public int $ordersCount = 0;

    public int $bookingsCount = 0;

    public int $productsCount = 0;

    public int $todayOrdersCount = 0;

    public int $todayBookingsCount = 0;

    public float $totalRevenue = 0.0;

    public function mount(): void
    {
        $today = now()->toDateString();

        $this->usersCount = User::count();
        $this->ordersCount = Order::count();
        $this->bookingsCount = Booking::count();
        $this->productsCount = Product::count();
        $this->todayOrdersCount = Order::whereDate('placed_at', $today)->count();
        $this->todayBookingsCount = Booking::whereDate('scheduled_date', $today)->count();
        $this->totalRevenue = (float) Order::where('payment_status', 'paid')->sum('total_amount');
    }

    public function render()
    {
        return view('pages.admin.dashboard');
    }
}
