<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCouponController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Coupon::query();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', '%'.$search.'%')
                    ->orWhere('name', 'like', '%'.$search.'%');
            });
        }

        if ($request->has('is_active')) {
            $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE);
            if (! is_null($isActive)) {
                $query->where('is_active', $isActive);
            }
        }

        if ($request->boolean('with_deleted')) {
            $query->withTrashed();
        }

        $coupons = $query->orderByDesc('created_at')->paginate($request->integer('per_page', 15));

        return response()->json($coupons);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:percentage,fixed'],
            'value' => ['required', 'numeric', 'min:0'],
            'min_amount' => ['nullable', 'numeric', 'min:0'],
            'max_discount' => ['nullable', 'numeric', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'user_limit' => ['nullable', 'integer', 'min:1'],
            'applicable_to' => ['required', 'string', 'in:all,products,services,categories'],
            'applicable_ids' => ['nullable', 'array'],
            'valid_from' => ['nullable', 'date'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:valid_from'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if (! array_key_exists('is_active', $data)) {
            $data['is_active'] = true;
        }

        $coupon = Coupon::create($data);

        return response()->json($coupon, 201);
    }

    public function show(Coupon $coupon): JsonResponse
    {
        $coupon->load('usages');

        return response()->json($coupon);
    }

    public function update(Request $request, Coupon $coupon): JsonResponse
    {
        $data = $request->validate([
            'code' => ['sometimes', 'string', 'max:50', 'unique:coupons,code,'.$coupon->id],
            'name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'type' => ['sometimes', 'string', 'in:percentage,fixed'],
            'value' => ['sometimes', 'numeric', 'min:0'],
            'min_amount' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'max_discount' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'usage_limit' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'user_limit' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'applicable_to' => ['sometimes', 'string', 'in:all,products,services,categories'],
            'applicable_ids' => ['sometimes', 'nullable', 'array'],
            'valid_from' => ['sometimes', 'nullable', 'date'],
            'valid_until' => ['sometimes', 'nullable', 'date', 'after_or_equal:valid_from'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $coupon->update($data);

        return response()->json($coupon);
    }

    public function destroy(Coupon $coupon): JsonResponse
    {
        $coupon->delete();

        return response()->json(null, 204);
    }
}
