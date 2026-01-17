<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Movie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;

class MovieController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Movie::with(['director', 'category', 'actors'])
                        ->withAvg('reviews as average_rating', 'rating');

        // filter & search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // sorting
        $sortBy = $request->input('sort_by', 'created_at');
        $order = $request->input('order', 'desc');

        if ($sortBy === 'rating') {
            $query->orderByDesc('average_rating');
        } else {
            $query->orderBy($sortBy, $order);
        }
    
        $movies = $query->paginate(10); 

        return response()->json([
            'success' => true,
            'data' => $movies
        ], 200);
    }

    public function show(string $movie_id): JsonResponse
    {
        $movie = Movie::with(['director', 'category', 'actors', 'reviews.user'])->withAvg('reviews as average_rating', 'rating')->find($movie_id);

        if (!$movie) {
            return response()->json([
                'success' => false,
                'message' => 'Movie not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $movie
        ], 200);    
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:250|unique:movies,title',
            'description' => 'nullable|string',
            'release_year' => 'required|integer|min:1900|max:2030',
            'duration' => 'required|integer|min:1',
            'poster_url' => 'nullable|url',
            'language' => 'nullable|string|max:50',
            'category_id' => 'required|exists:categories,category_id',
            'director_id' => 'required|exists:directors,director_id',
            'actors' => 'required|array',
            'actors.*' => 'exists:actors,actor_id',
        ]);
        
        if ($validator->fails()) {
            return  response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $movie = Movie::create(collect($validated)->except('actors')->toArray());

        if (isset($validated['actors'])) {
            $movie->actors()->sync($validated['actors']);
        }
       
        return response()->json([
            'success' => true,
            'message' => 'Movie created successfully',
            'data' => $movie->load(['director', 'category', 'actors'])
        ], 201);
    }

    public function update(Request $request, string $movie_id): JsonResponse
    {
        $movie = Movie::find($movie_id);
        if (!$movie) {
            return response()->json([
                'success' => false,
                'message' => 'Movie not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => [
                'sometimes',
                'required',
                'string',
                'max:250',
                Rule::unique('movies', 'title')->ignore($movie->movie_id, 'movie_id'),
            ],
            'description' => 'nullable|string',
            'release_year' => 'sometimes|required|integer|min:1900|max:2030',
            'duration' => 'sometimes|required|integer|min:1',
            'poster_url' => 'nullable|url',
            'language' => 'nullable|string|max:50',
            'category_id' => 'sometimes|required|exists:categories,category_id',
            'director_id' => 'sometimes|required|exists:directors,director_id',
            'actors' => 'sometimes|array',
            'actors.*' => 'exists:actors,actor_id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $validated = $validator->validated();

        $movie->update(collect($validated)->except('actors')->toArray());

        if (isset($validated['actors'])) {
            $movie->actors()->sync($validated['actors']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Movie updated successfully',
            'data' => $movie->load(['director', 'category', 'actors'])
        ], 200);
    }

    public function destroy(string $movie_id): JsonResponse
    {
        $movie = Movie::find($movie_id);
        if (!$movie) {
            return response()->json([
                'success' => false,
                'message' => 'Movie not found'
            ], 404);
        }

        $movie->actors()->detach();
        $movie->watchlistMovies()->detach();
        $movie->delete();

        return response()->json([
            'success' => true,
            'message' => 'Movie deleted successfully'
        ], 200);
    }   
}
