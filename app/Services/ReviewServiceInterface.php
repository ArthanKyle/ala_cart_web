<?php


namespace App\Services;

use App\Models\Review;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface ReviewServiceInterface
{
    public function getAllReviews(): Collection;
    public function getReviewById(int $id): ?Review;
    public function createReview(Request $request): Review;
    public function deleteReview(int $id): bool;
}
