<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Order::query()->with(['user', 'payments']);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', '%' . $search . '%')
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

        if ($request->filled('from_date')) {
            $query->whereDate('placed_at', '>=', $request->date('from_date'));
        }

        if ($request->filled('to_date')) {
            $query->whereDate('placed_at', '<=', $request->date('to_date'));
        }

        if ($request->boolean('with_deleted')) {
            $query->withTrashed();
        }

        $orders = $query
            ->orderByDesc('placed_at')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15));

        return response()->json($orders);
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::with(['user', 'items.product', 'payments'])->findOrFail($id);

        return response()->json($order);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'max:50'],
            'payment_status' => ['sometimes', 'nullable', 'string', 'max:50'],
        ]);

        $order = Order::findOrFail($id);

        $order->status = $data['status'];

        if (array_key_exists('payment_status', $data)) {
            $order->payment_status = $data['payment_status'];
        }

        $order->save();

        return response()->json($order);
    }
}

