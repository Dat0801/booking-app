<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Review;

class ReviewObserver
{
    public function updated(Review $review): void
    {
        if ($review->wasChanged('status')) {
            $this->updateProductRating($review->product);
        }
    }

    public function deleted(Review $review): void
    {
        $this->updateProductRating($review->product);
    }

    private function updateProductRating(Product $product): void
    {
        $approvedReviews = $product->approvedReviews();

        $averageRating = $approvedReviews->avg('rating');
        $reviewCount = $approvedReviews->count();

        $product->update([
            'rating' => $averageRating ? round($averageRating, 2) : 0,
            'review_count' => $reviewCount,
        ]);
    }
}
