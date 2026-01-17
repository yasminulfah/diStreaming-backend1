<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class WatchlistController extends Controller
{
    public function index(): JsonResponse
    {
        $user = Auth::user();

        $watchlist = $user->watchlistMovies()->with(['director', 'category'])->get();

        return response()->json([
            'success' => true,
            'data' => $watchlist
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|integer|exists:movies,movie_id',
        ]);

        if (Validator::fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $movieId = $request->movie_id;

        if ($user->watchlistMovies()->where('watchlist.movie_id', $movieId)->exists()) {
             return response()->json([
                'success' => false,
                'message' => 'Movie already in watchlist.'
            ], 409);
        }

        $user->watchlistMovies()->attach($movieId);

        return response()->json([
            'success' => true,
            'message' => 'Movie added to watchlist successfully.'
        ], 200);
    }

    public function destroy(string $movieId): JsonResponse
    {
        $user = Auth::user();

        $detached = $user->watchlistMovies()->detach($movieId);

        if ($detached === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Movie not found in watchlist.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Movie removed from watchlist.'
        ], 200);
    }
}
