<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Review\CreateReviewRequest;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $productId = $request->integer('product_id');

        $query = Review::query()
            ->with(['user', 'product'])
            ->where('status', 'approved');

        if ($productId) {
            $query->where('product_id', $productId);
        }

        $reviews = $query
            ->orderByDesc('created_at')
            ->paginate($request->integer('per_page', 15));

        return response()->json($reviews);
    }

    public function store(CreateReviewRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if ($data['booking_id']) {
            $booking = Booking::query()
                ->where('id', $data['booking_id'])
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->firstOrFail();

            $existingReview = Review::where('booking_id', $data['booking_id'])->first();
            if ($existingReview) {
                return response()->json([
                    'message' => 'You have already reviewed this booking.',
                ], 422);
            }
        } else {
            $existingReview = Review::where('user_id', $user->id)
                ->where('product_id', $data['product_id'])
                ->first();

            if ($existingReview) {
                return response()->json([
                    'message' => 'You have already reviewed this product.',
                ], 422);
            }
        }

        $review = Review::create([
            'user_id' => $user->id,
            'product_id' => $data['product_id'],
            'booking_id' => $data['booking_id'] ?? null,
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
            'status' => 'pending',
        ]);

        $review->load(['user', 'product']);

        return response()->json($review, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $review = Review::with(['user', 'product', 'booking'])
            ->findOrFail($id);

        if ($review->status !== 'approved' && $review->user_id !== $request->user()->id) {
            abort(404);
        }

        return response()->json($review);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $review = Review::query()
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->findOrFail($id);

        if ($review->status === 'approved') {
            return response()->json([
                'message' => 'Cannot update an approved review.',
            ], 422);
        }

        $data = $request->validate([
            'rating' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'comment' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ]);

        $review->update($data);
        $review->load(['user', 'product']);

        return response()->json($review);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $review = Review::query()
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->findOrFail($id);

        if ($review->status === 'approved') {
            return response()->json([
                'message' => 'Cannot delete an approved review.',
            ], 422);
        }

        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully.',
        ]);
    }
}
