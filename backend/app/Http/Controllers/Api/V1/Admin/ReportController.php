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

class ReportController extends Controller
{
    public function sales(Request $request): JsonResponse
    {
        $startDate = $request->date('start_date', now()->startOfMonth());
        $endDate = $request->date('end_date', now()->endOfMonth());

        $orders = Order::where('payment_status', 'paid')
            ->whereBetween('placed_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(placed_at) as date'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('SUM(discount_amount) as discount'),
                DB::raw('SUM(total_amount - discount_amount) as net_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $bookings = Booking::where('payment_status', 'paid')
            ->whereBetween('scheduled_date', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(scheduled_date) as date'),
                DB::raw('COUNT(*) as bookings_count'),
                DB::raw('SUM(total_amount) as revenue'),
                DB::raw('SUM(discount_amount) as discount')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'orders' => $orders,
            'bookings' => $bookings,
            'summary' => [
                'total_orders' => $orders->sum('orders_count'),
                'total_bookings' => $bookings->sum('bookings_count'),
                'total_revenue' => round($orders->sum('revenue') + $bookings->sum('revenue'), 2),
                'total_discount' => round($orders->sum('discount') + $bookings->sum('discount'), 2),
                'net_revenue' => round($orders->sum('net_revenue') + $bookings->sum('revenue') - $bookings->sum('discount'), 2),
            ],
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $startDate = $request->date('start_date');
        $endDate = $request->date('end_date');

        $query = Product::withCount(['orderItems', 'bookings'])
            ->withSum('orderItems', 'subtotal');

        if ($startDate && $endDate) {
            $query->whereHas('orderItems', function ($q) use ($startDate, $endDate) {
                $q->whereHas('order', function ($oq) use ($startDate, $endDate) {
                    $oq->whereBetween('placed_at', [$startDate, $endDate]);
                });
            });
        }

        $products = $query->orderByDesc('order_items_count')
            ->limit(20)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'type' => $product->type,
                    'price' => $product->price,
                    'orders_count' => $product->order_items_count,
                    'bookings_count' => $product->bookings_count,
                    'revenue' => round($product->order_items_sum_subtotal ?? 0, 2),
                ];
            });

        return response()->json($products);
    }

    public function customers(Request $request): JsonResponse
    {
        $startDate = $request->date('start_date');
        $endDate = $request->date('end_date');

        $query = User::withCount(['orders', 'bookings'])
            ->withSum('orders', 'total_amount');

        if ($startDate && $endDate) {
            $query->whereHas('orders', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('placed_at', [$startDate, $endDate]);
            });
        }

        $customers = $query->orderByDesc('orders_sum_total_amount')
            ->limit(20)
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

        return response()->json($customers);
    }
}
