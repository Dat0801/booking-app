<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function summary(Request $request): JsonResponse
    {
        $usersCount = User::count();
        $ordersCount = Order::count();
        $bookingsCount = Booking::count();
        $productsCount = Product::where('is_active', true)->count();

        $today = now()->toDateString();
        $thisMonth = now()->startOfMonth();
        $lastMonth = now()->subMonth()->startOfMonth();

        $todayOrders = Order::whereDate('placed_at', $today)->count();
        $todayBookings = Booking::whereDate('scheduled_date', $today)->count();

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->where('placed_at', '>=', $thisMonth)
            ->sum('total_amount');
        $lastMonthRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('placed_at', [$lastMonth, $thisMonth])
            ->sum('total_amount');

        $totalDiscount = Order::where('payment_status', 'paid')->sum('discount_amount')
            + Booking::where('payment_status', 'paid')->sum('discount_amount');

        $pendingOrders = Order::whereIn('status', ['pending', 'confirmed'])->count();
        $pendingBookings = Booking::whereIn('status', ['pending', 'confirmed'])->count();

        $recentOrders = Order::with(['user', 'items'])
            ->orderByDesc('placed_at')
            ->limit(5)
            ->get();

        $recentBookings = Booking::with(['user', 'product'])
            ->orderByDesc('scheduled_date')
            ->limit(5)
            ->get();

        return response()->json([
            'users_count' => $usersCount,
            'orders_count' => $ordersCount,
            'bookings_count' => $bookingsCount,
            'products_count' => $productsCount,
            'today_orders_count' => $todayOrders,
            'today_bookings_count' => $todayBookings,
            'pending_orders_count' => $pendingOrders,
            'pending_bookings_count' => $pendingBookings,
            'total_revenue' => round($totalRevenue, 2),
            'monthly_revenue' => round($monthlyRevenue, 2),
            'last_month_revenue' => round($lastMonthRevenue, 2),
            'revenue_growth' => $lastMonthRevenue > 0
                ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 2)
                : 0,
            'total_discount' => round($totalDiscount, 2),
            'recent_orders' => $recentOrders,
            'recent_bookings' => $recentBookings,
        ]);
    }

    public function revenue(Request $request): JsonResponse
    {
        $period = $request->string('period', 'month')->toString();
        $startDate = $request->date('start_date');
        $endDate = $request->date('end_date');

        $query = Order::where('payment_status', 'paid');

        if ($startDate && $endDate) {
            $query->whereBetween('placed_at', [$startDate, $endDate]);
        } else {
            switch ($period) {
                case 'week':
                    $query->where('placed_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->where('placed_at', '>=', now()->startOfMonth());
                    break;
                case 'year':
                    $query->where('placed_at', '>=', now()->startOfYear());
                    break;
            }
        }

        $revenue = $query->sum('total_amount');
        $discount = $query->sum('discount_amount');
        $count = $query->count();

        $dailyRevenue = $query->select(
            DB::raw('DATE(placed_at) as date'),
            DB::raw('SUM(total_amount) as revenue'),
            DB::raw('COUNT(*) as orders')
        )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'total_revenue' => round($revenue, 2),
            'total_discount' => round($discount, 2),
            'net_revenue' => round($revenue - $discount, 2),
            'orders_count' => $count,
            'average_order_value' => $count > 0 ? round($revenue / $count, 2) : 0,
            'daily_revenue' => $dailyRevenue,
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $topProducts = Product::withCount(['orderItems', 'bookings'])
            ->withSum('orderItems', 'subtotal')
            ->orderByDesc('order_items_count')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'orders_count' => $product->order_items_count,
                    'bookings_count' => $product->bookings_count,
                    'revenue' => round($product->order_items_sum_subtotal ?? 0, 2),
                ];
            });

        $topRatedProducts = Product::withAvg('approvedReviews', 'rating')
            ->withCount('approvedReviews')
            ->having('approved_reviews_avg_rating', '>', 0)
            ->orderByDesc('approved_reviews_avg_rating')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'rating' => round($product->approved_reviews_avg_rating, 2),
                    'reviews_count' => $product->approved_reviews_count,
                ];
            });

        return response()->json([
            'top_products' => $topProducts,
            'top_rated_products' => $topRatedProducts,
        ]);
    }

    public function customers(Request $request): JsonResponse
    {
        $topCustomers = User::withCount(['orders', 'bookings'])
            ->withSum('orders', 'total_amount')
            ->orderByDesc('orders_sum_total_amount')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'orders_count' => $user->orders_count,
                    'bookings_count' => $user->bookings_count,
                    'total_spent' => round($user->orders_sum_total_amount ?? 0, 2),
                ];
            });

        return response()->json([
            'top_customers' => $topCustomers,
        ]);
    }
}
