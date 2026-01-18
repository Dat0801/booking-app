<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $usersCount = User::count();
        $ordersCount = Order::count();
        $bookingsCount = Booking::count();
        $productsCount = Product::count();

        $today = now()->toDateString();

        $todayOrders = Order::whereDate('placed_at', $today)->count();
        $todayBookings = Booking::whereDate('scheduled_date', $today)->count();

        $revenue = Order::where('payment_status', 'paid')->sum('total_amount');

        return response()->json([
            'users_count' => $usersCount,
            'orders_count' => $ordersCount,
            'bookings_count' => $bookingsCount,
            'products_count' => $productsCount,
            'today_orders_count' => $todayOrders,
            'today_bookings_count' => $todayBookings,
            'total_revenue' => $revenue,
        ]);
    }
}

