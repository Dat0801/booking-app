<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
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
        $currentMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $todayOrders = Order::whereDate('placed_at', $today)->count();
        $todayBookings = Booking::whereDate('scheduled_date', $today)->count();

        $revenue = Order::where('payment_status', 'paid')->sum('total_amount');

        // Booking-specific statistics
        $monthlyBookingRevenue = Booking::whereBetween('scheduled_date', [$currentMonth, $endOfMonth])
            ->sum('total_amount');
        
        $pendingApprovals = Booking::where('status', 'pending')->count();
        $confirmedBookings = Booking::where('status', 'confirmed')->count();
        $completedBookings = Booking::where('status', 'completed')->count();
        $cancelledBookings = Booking::where('status', 'cancelled')->count();

        // Payment statistics
        $paidBookings = Booking::where('payment_status', 'paid')->sum('total_amount');
        $unpaidBookings = Booking::where('payment_status', 'unpaid')->sum('total_amount');
        $pendingPayments = Booking::where('payment_status', 'pending')->count();

        return response()->json([
            'users_count' => $usersCount,
            'orders_count' => $ordersCount,
            'bookings_count' => $bookingsCount,
            'products_count' => $productsCount,
            'today_orders_count' => $todayOrders,
            'today_bookings_count' => $todayBookings,
            'total_revenue' => $revenue,
            'bookings' => [
                'monthly_revenue' => $monthlyBookingRevenue,
                'pending_approvals' => $pendingApprovals,
                'confirmed' => $confirmedBookings,
                'completed' => $completedBookings,
                'cancelled' => $cancelledBookings,
                'paid_amount' => $paidBookings,
                'unpaid_amount' => $unpaidBookings,
                'pending_payments' => $pendingPayments,
            ],
        ]);
    }
}

