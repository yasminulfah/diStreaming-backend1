<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Actor;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;


class ActorController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Actor::latest()->get(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'birth_date' => 'nullable|date',
            'nationality' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $actor = Actor::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Actor created successfully',
            'data' => $actor
        ], 201);
    }

    public function show(string $actor_id): JsonResponse
    {
        $actor = Actor::with('movies:movie_id,title,release_year')->find($actor_id);
        if (!$actor) {
            return response()->json([
                'success' => false,
                'message' => 'Actor not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $actor
        ], 200);
    }

    public function update(Request $request, string $actor_id): JsonResponse
    {
        $actor = Actor::find($actor_id);
        if (!$actor) {
            return response()->json([
                'success' => false,
                'message' => 'Actor not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|nullable|string|max:100',
            'birth_date' => 'sometimes|nullable|date',
            'nationality' => 'sometimes|nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $actor->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Actor updated successfully',
            'data' => $actor
        ], 200);
    }

    public function destroy(string $actor_id): JsonResponse
    {
        $actor = Actor::find($actor_id);
        if (!$actor) {
            return response()->json([
                'success' => false,
                'message' => 'Actor not found'
            ], 404);
        }

        $actor->movies()->detach();
        $actor->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Actor deleted successfully'
        ], 200);
    }
}
