<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Review::query()->with(['user', 'product', 'booking']);

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->integer('product_id'));
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->integer('user_id'));
        }

        $reviews = $query
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($reviews);
    }

    public function show(int $id): JsonResponse
    {
        $review = Review::with(['user', 'product', 'booking'])->findOrFail($id);

        return response()->json($review);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:pending,approved,rejected'],
        ]);

        $review = Review::findOrFail($id);
        $review->status = $data['status'];
        $review->save();

        $review->load(['user', 'product', 'booking']);

        return response()->json($review);
    }

    public function destroy(int $id): JsonResponse
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully.',
        ]);
    }
}
