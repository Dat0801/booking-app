<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddCartItemRequest;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateActiveCart($request);

        $cart->load('items.product');

        return response()->json($cart);
    }

    public function store(AddCartItemRequest $request): JsonResponse
    {
        $cart = $this->getOrCreateActiveCart($request);

        $data = $request->validated();

        $product = Product::query()
            ->where('is_active', true)
            ->findOrFail($data['product_id']);

        $item = $cart->items()
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            $item->quantity += $data['quantity'];
            $item->unit_price = $product->price;
            $item->save();
        } else {
            $item = new CartItem([
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'unit_price' => $product->price,
            ]);

            $cart->items()->save($item);
        }

        $cart->load('items.product');

        return response()->json($cart, 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $cart = $this->getOrCreateActiveCart($request);

        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item = $cart->items()
            ->where('id', $id)
            ->firstOrFail();

        $item->quantity = $data['quantity'];
        $item->save();

        $cart->load('items.product');

        return response()->json($cart);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $cart = $this->getOrCreateActiveCart($request);

        $item = $cart->items()
            ->where('id', $id)
            ->firstOrFail();

        $item->delete();

        return response()->json(null, 204);
    }

    public function clear(Request $request): JsonResponse
    {
        $cart = $this->getOrCreateActiveCart($request);

        $cart->items()->delete();

        return response()->json(null, 204);
    }

    private function getOrCreateActiveCart(Request $request): Cart
    {
        $user = $request->user();

        $cart = Cart::query()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        if (! $cart) {
            $cart = Cart::create([
                'user_id' => $user->id,
                'status' => 'active',
            ]);
        }

        return $cart;
    }
}

