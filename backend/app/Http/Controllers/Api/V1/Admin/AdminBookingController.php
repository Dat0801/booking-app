<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AdminBookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Booking::query()->with(['user', 'product', 'payments']);

        // Search by booking number, guest name, email, or property name
        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('product', function ($pq) use ($search) {
                        $pq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('location', 'like', '%' . $search . '%');
                    });
            });
        }

        // Filter by booking status (pending, confirmed, completed, cancelled, etc.)
        if ($request->filled('status')) {
            $status = $request->string('status')->toString();
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        // Filter by payment status (paid, pending, unpaid, partially paid, etc.)
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->string('payment_status')->toString());
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        // Filter by product/property
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->integer('product_id'));
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('scheduled_date', '>=', $request->date('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('scheduled_date', '<=', $request->date('to_date'));
        }

        // Include soft deleted records if requested
        if ($request->boolean('with_deleted')) {
            $query->withTrashed();
        }

        $per_page = $request->integer('per_page', 15);
        $bookings = $query
            ->orderByDesc('scheduled_date')
            ->orderByDesc('id')
            ->paginate($per_page);

        return response()->json([
            'data' => BookingResource::collection($bookings->items()),
            'pagination' => [
                'total' => $bookings->total(),
                'per_page' => $bookings->perPage(),
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'from' => $bookings->firstItem(),
                'to' => $bookings->lastItem(),
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $booking = Booking::with(['user', 'product', 'payments'])->findOrFail($id);

        return response()->json(new BookingResource($booking));
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:pending,confirmed,in_progress,completed,cancelled,no_show'],
            'payment_status' => ['sometimes', 'nullable', 'string', 'in:unpaid,pending,paid,failed,refunded,partially_paid'],
        ]);

        $booking = Booking::findOrFail($id);

        $booking->status = $data['status'];

        if (array_key_exists('payment_status', $data)) {
            $booking->payment_status = $data['payment_status'];
        }

        $booking->save();

        return response()->json(new BookingResource($booking));
    }

    /**
     * Get booking statistics for dashboard
     */
    public function statistics(Request $request): JsonResponse
    {
        $currentMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Get statistics for current month
        $monthlyRevenue = Booking::whereBetween('scheduled_date', [$currentMonth, $endOfMonth])
            ->where('payment_status', 'paid')
            ->sum('total_amount');

        // Status counts
        $statusCounts = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        // Payment status counts
        $paymentStatusCounts = Booking::selectRaw('payment_status, COUNT(*) as count')
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status')
            ->toArray();

        $stats = [
            'monthly_revenue' => [
                'value' => (float) $monthlyRevenue,
                'formatted' => '$' . number_format((float) $monthlyRevenue, 2),
            ],
            'total_bookings' => Booking::count(),
            'status_breakdown' => [
                'all' => Booking::count(),
                'pending' => $statusCounts['pending'] ?? 0,
                'confirmed' => $statusCounts['confirmed'] ?? 0,
                'completed' => $statusCounts['completed'] ?? 0,
                'cancelled' => $statusCounts['cancelled'] ?? 0,
            ],
            'payment_summary' => [
                'paid' => [
                    'count' => Booking::where('payment_status', 'paid')->count(),
                    'amount' => (float) Booking::where('payment_status', 'paid')->sum('total_amount'),
                    'amount_formatted' => '$' . number_format((float) Booking::where('payment_status', 'paid')->sum('total_amount'), 2),
                ],
                'pending' => [
                    'count' => Booking::where('payment_status', 'pending')->count(),
                    'amount' => (float) Booking::where('payment_status', 'pending')->sum('total_amount'),
                    'amount_formatted' => '$' . number_format((float) Booking::where('payment_status', 'pending')->sum('total_amount'), 2),
                ],
                'unpaid' => [
                    'count' => Booking::where('payment_status', 'unpaid')->count(),
                    'amount' => (float) Booking::where('payment_status', 'unpaid')->sum('total_amount'),
                    'amount_formatted' => '$' . number_format((float) Booking::where('payment_status', 'unpaid')->sum('total_amount'), 2),
                ],
            ],
        ];

        return response()->json($stats);
    }

    /**
     * Get filter options (status list, etc.)
     */
    public function filterOptions(): JsonResponse
    {
        return response()->json([
            'statuses' => [
                ['value' => 'all', 'label' => 'All', 'count' => Booking::count()],
                ['value' => 'pending', 'label' => 'Pending', 'count' => Booking::where('status', 'pending')->count()],
                ['value' => 'confirmed', 'label' => 'Confirmed', 'count' => Booking::where('status', 'confirmed')->count()],
                ['value' => 'completed', 'label' => 'Completed', 'count' => Booking::where('status', 'completed')->count()],
                ['value' => 'cancelled', 'label' => 'Cancelled', 'count' => Booking::where('status', 'cancelled')->count()],
            ],
            'payment_statuses' => [
                ['value' => 'paid', 'label' => 'Paid'],
                ['value' => 'pending', 'label' => 'Pending'],
                ['value' => 'unpaid', 'label' => 'Unpaid'],
                ['value' => 'failed', 'label' => 'Failed'],
                ['value' => 'refunded', 'label' => 'Refunded'],
            ],
        ]);
    }
}

