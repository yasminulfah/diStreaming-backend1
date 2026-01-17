<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Director;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;


class DirectorController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => Director::latest()->get(),
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

        $director = Director::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Director created successfully',
            'data' => $director
        ], 201);
    }

    public function show(string $director_id): JsonResponse
    {
        $director = Director::with('movies:movie-id,title,release_year,director_id')->find($director_id);

        if (!$director) {
            return response()->json([
                'success' => false,
                'message' => 'Director not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $director
        ], 200);
    }

    public function update(Request $request, string $director_id): JsonResponse
    {
        $director = Director::find($director_id);
        if (!$director) {
            return response()->json([
                'success' => false,
                'message' => 'Director not found'
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

        $director->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Director updated successfully',
            'data' => $director
        ], 200);
    }

    public function destroy(string $director_id): JsonResponse
    {
        $director = Director::find($director_id);
        if (!$director) {
            return response()->json([
                'success' => false,
                'message' => 'Director not found'
            ], 404);
        }

        $director->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Director deleted successfully'
        ], 200);
    }
}
