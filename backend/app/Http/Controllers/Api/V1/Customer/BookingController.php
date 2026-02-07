<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Booking\CreateBookingRequest;
use App\Models\Booking;
use App\Models\Product;
use App\Notifications\BookingConfirmedNotification;
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

        $scheduledDate = $data['scheduled_date'];
        $startTime = $data['start_time'];
        $endTime = $data['end_time'] ?? null;

        if (! $this->checkAvailability($product->id, $scheduledDate, $startTime, $endTime)) {
            return response()->json([
                'message' => 'The selected time slot is not available. Please choose another time.',
            ], 422);
        }

        if ($product->stock_quantity !== null && $product->stock_quantity <= 0) {
            return response()->json([
                'message' => 'This product is currently out of stock.',
            ], 422);
        }

        $booking = Booking::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'booking_number' => 'BKG-'.now()->format('YmdHis').'-'.$user->id,
            'status' => 'pending',
            'scheduled_date' => $scheduledDate,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'notes' => $data['notes'] ?? null,
            'total_amount' => $product->price,
            'payment_status' => 'unpaid',
        ]);

        $booking->load(['product', 'user', 'payments']);

        $user->notify(new BookingConfirmedNotification($booking));

        return response()->json($booking, 201);
    }

    private function checkAvailability(int $productId, string $scheduledDate, string $startTime, ?string $endTime): bool
    {
        $query = Booking::query()
            ->where('product_id', $productId)
            ->where('scheduled_date', $scheduledDate)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress']);

        if ($endTime) {
            $query->where(function ($q) use ($startTime, $endTime) {
                $q->where(function ($q2) use ($startTime, $endTime) {
                    $q2->where('start_time', '<', $endTime)
                        ->where(function ($q3) use ($startTime) {
                            $q3->whereNull('end_time')
                                ->orWhere('end_time', '>', $startTime);
                        });
                });
            });
        } else {
            $query->where(function ($q) use ($startTime) {
                $q->where('start_time', '=', $startTime)
                    ->orWhere(function ($q2) use ($startTime) {
                        $q2->where('start_time', '<', $startTime)
                            ->where(function ($q3) use ($startTime) {
                                $q3->whereNull('end_time')
                                    ->orWhere('end_time', '>', $startTime);
                            });
                    });
            });
        }

        return $query->count() === 0;
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
