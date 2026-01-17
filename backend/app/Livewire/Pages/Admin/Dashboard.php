<?php

namespace App\Livewire\Pages\Admin;

use App\Models\Booking;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin dashboard')]
class Dashboard extends Component
{
    public int $usersCount = 0;

    public int $ordersCount = 0;

    public int $bookingsCount = 0;

    public int $productsCount = 0;

    public function mount(): void
    {
        $this->usersCount = User::count();
        $this->ordersCount = Order::count();
        $this->bookingsCount = Booking::count();
        $this->productsCount = Product::count();
    }

    public function render()
    {
        return view('pages.admin.dashboard');
    }
}

