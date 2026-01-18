<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->with(['items.product', 'payments'])
            ->orderByDesc('placed_at')
            ->orderByDesc('id')
            ->paginate($request->integer('per_page', 15));

        return response()->json($orders);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $order = Order::query()
            ->where('user_id', $user->id)
            ->with(['items.product', 'payments'])
            ->findOrFail($id);

        return response()->json($order);
    }

    public function store(CreateOrderRequest $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validated();

        $cart = Cart::query()
            ->where('id', $data['cart_id'])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->with('items')
            ->firstOrFail();

        if ($cart->items->isEmpty()) {
            return response()->json([
                'message' => 'Cart is empty',
            ], 422);
        }

        $total = $cart->items->reduce(function ($carry, $item) {
            return $carry + ($item->quantity * $item->unit_price);
        }, 0);

        $order = null;

        DB::transaction(function () use ($user, $cart, $data, $total, &$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'cart_id' => $cart->id,
                'order_number' => 'ORD-' . now()->format('YmdHis') . '-' . $user->id,
                'status' => 'pending',
                'total_amount' => $total,
                'currency' => 'USD',
                'payment_status' => 'unpaid',
                'payment_method' => $data['payment_method'] ?? null,
                'notes' => $data['notes'] ?? null,
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product->name,
                    'type' => $item->product->type,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'subtotal' => $item->quantity * $item->unit_price,
                ]);
            }

            $cart->status = 'converted';
            $cart->save();
        });

        $order->load(['items.product', 'payments']);

        return response()->json($order, 201);
    }

    public function cancel(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $order = Order::query()
            ->where('user_id', $user->id)
            ->findOrFail($id);

        if (! in_array($order->status, ['pending', 'confirmed', 'processing'], true)) {
            return response()->json([
                'message' => 'Order cannot be cancelled in its current status',
            ], 422);
        }

        $order->status = 'cancelled';

        if ($order->payment_status === 'paid') {
            $order->payment_status = 'refunded';
        }

        $order->save();

        return response()->json($order);
    }
}

