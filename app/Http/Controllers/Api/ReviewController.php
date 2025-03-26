<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use App\Services\ReviewServiceInterface;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    private ReviewServiceInterface $reviewService;

    public function __construct(ReviewServiceInterface $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function index(): JsonResponse
    {
        return response()->json([
            'reviews' => $this->reviewService->getAllReviews(),
        ]);

    }

    public function show(int $id): JsonResponse
    {
        $review = $this->reviewService->getReviewById($id);

        return $review ? response()->json($review) : response()->json(['message' => 'Review not found'], 404);
    }

    public function store(ReviewRequest $request): JsonResponse
    {
        $review = $this->reviewService->createReview($request);

        return response()->json(['message' => 'Review created successfully', 'data' => $review], 201);
    }

    public function destroy(int $id): JsonResponse
    {
        return $this->reviewService->deleteReview($id)
            ? response()->json(['message' => 'Review deleted successfully'])
            : response()->json(['message' => 'Review not found'], 404);
    }
}
