<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;


class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'movie:movie_id,title'])->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $reviews
        ], 200);
    }

    public function show(string $review_id): JsonResponse
    {
        $review = Review::with(['user', 'movie:movie-id,title,release_year'])->find($review_id);

        if (!$review)
        {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $review
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $currentUserId = $request->user()->user_id;

        $validator = Validator::make($request->all(), [
            'movie_id' => [
                'required',
                'exists:movies,movie_id',
                Rule::unique('reviews')->where(function ($query) use ($currentUserId) {
                    return $query->where('user_id', $currentUserId);
                }),
            ],
            'rating' => 'required|numeric|min:0.0|max:10.0',
            'review_text' => 'nullable|string',
        ], [
            'movie_id.unique' => 'You have already reviewed this movie.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();
        
        $validated['user_id'] = $currentUserId;
        
        $review = Review::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Review created successfully',
            'data' => $review
        ], 201);
    }

    public function update(Request $request, string $review_id): JsonResponse
    {
        $review = Review::find($review_id);

        if (!$review){
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

        if ($review->user_id !== $request->user()->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|required|numeric|min:0|max:10',
            'review_text' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $review->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'data' => $review
        ], 200);
    }

    public function destroy(Request $request, string $review_id): JsonResponse
    {
        $review = Review::find($review_id);
        if (!$review) {
            return response()->json([
                'success' => false,
                'message' => 'Review not found'
            ], 404);
        }

if ($review->user_id !== $request->user()->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this'
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully'
        ], 200);
    }
}
