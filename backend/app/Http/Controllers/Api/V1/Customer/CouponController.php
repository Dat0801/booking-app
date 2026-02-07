<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
            'type' => ['sometimes', 'string', 'in:order,booking'],
            'product_id' => ['sometimes', 'nullable', 'integer', 'exists:products,id'],
        ]);

        $user = $request->user();
        $code = $request->string('code')->toString();
        $amount = (float) $request->input('amount');

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon) {
            return response()->json([
                'message' => 'Invalid coupon code.',
            ], 404);
        }

        if (! $coupon->canBeUsedBy($user)) {
            return response()->json([
                'message' => 'This coupon cannot be used.',
            ], 422);
        }

        $discount = $coupon->calculateDiscount($amount);

        return response()->json([
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
            ],
            'discount_amount' => $discount,
            'original_amount' => $amount,
            'final_amount' => $amount - $discount,
        ]);
    }
}
