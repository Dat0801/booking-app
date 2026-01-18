<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Booking::query()->with(['user', 'product', 'payments']);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('booking_number', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->string('payment_status')->toString());
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->integer('product_id'));
        }

        if ($request->filled('from_date')) {
            $query->whereDate('scheduled_date', '>=', $request->date('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('scheduled_date', '<=', $request->date('to_date'));
        }

        if ($request->boolean('with_deleted')) {
            $query->withTrashed();
        }

        $bookings = $query
            ->orderByDesc('scheduled_date')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15));

        return response()->json($bookings);
    }

    public function show(int $id): JsonResponse
    {
        $booking = Booking::with(['user', 'product', 'payments'])->findOrFail($id);

        return response()->json($booking);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:50'],
            'payment_status' => ['sometimes', 'nullable', 'string', 'max:50'],
        ]);

        $booking = Booking::findOrFail($id);

        $booking->status = $data['status'];

        if (array_key_exists('payment_status', $data)) {
            $booking->payment_status = $data['payment_status'];
        }

        $booking->save();

        return response()->json($booking);
    }
}

