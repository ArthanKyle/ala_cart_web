<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
class ReviewService implements ReviewServiceInterface
{
    public function getAllReviews(): Collection
    {
        return Review::with(['user', 'product'])->latest()->get();
    }

    public function getReviewById(int $id): ?Review
    {
        return Review::with(['user', 'product'])->where('id', $id)->first();
    }
    

    public function createReview(Request $request): Review
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:lunar_products,id',
            'rating' => 'required|integer|between:1,5',
            'review' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['images'] = $request->has('images') ? json_encode($request->images) : null;

        return Review::create($validated);
    }

    public function deleteReview(int $id): bool
    {
        $review = Review::find($id);
        return $review ? $review->delete() : false;
    }
}
