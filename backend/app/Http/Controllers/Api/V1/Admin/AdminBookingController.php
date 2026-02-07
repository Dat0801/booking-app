<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Notifications\BookingConfirmedNotification;
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
                $q->where('booking_number', 'like', '%'.$search.'%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%'.$search.'%')
                            ->orWhere('email', 'like', '%'.$search.'%');
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

        $booking = Booking::with(['user', 'product'])->findOrFail($id);

        $oldStatus = $booking->status;
        $booking->status = $data['status'];

        if (array_key_exists('payment_status', $data)) {
            $booking->payment_status = $data['payment_status'];
        }

        $booking->save();

        if ($oldStatus !== $booking->status && in_array($booking->status, ['confirmed', 'completed'], true)) {
            $booking->user->notify(new BookingConfirmedNotification($booking));
        }

        return response()->json($booking);
    }
}
