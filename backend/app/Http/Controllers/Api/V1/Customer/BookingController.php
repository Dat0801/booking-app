<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\CreateBookingRequest;
use App\Models\Booking;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $bookings = Booking::query()
            ->where('user_id', $user->id)
            ->with(['product', 'payments'])
            ->orderByDesc('scheduled_date')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15));

        return response()->json($bookings);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $booking = Booking::query()
            ->where('user_id', $user->id)
            ->with(['product', 'payments'])
            ->findOrFail($id);

        return response()->json($booking);
    }

    public function store(CreateBookingRequest $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validated();

        $product = Product::query()
            ->where('is_active', true)
            ->findOrFail($data['product_id']);

        $booking = Booking::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'booking_number' => 'BKG-' . now()->format('YmdHis') . '-' . $user->id,
            'status' => 'pending',
            'scheduled_date' => $data['scheduled_date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'] ?? null,
            'notes' => $data['notes'] ?? null,
            'total_amount' => $product->price,
            'payment_status' => 'unpaid',
        ]);

        $booking->load(['product', 'payments']);

        return response()->json($booking, 201);
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $booking = Booking::query()
            ->where('user_id', $user->id)
            ->findOrFail($id);

        if (! in_array($booking->status, ['pending', 'confirmed', 'in_progress'], true)) {
            return response()->json([
                'message' => 'Booking cannot be cancelled in its current status',
            ], 422);
        }

        $booking->status = 'cancelled';

        if ($booking->payment_status === 'paid') {
            $booking->payment_status = 'refunded';
        }

        $booking->save();

        return response()->json($booking);
    }
}

