<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\{UserController, MovieController, ReviewController, DirectorController, ActorController, CategoryController, WatchlistController, AuthController};

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::apiResources([
    'movies' => MovieController::class,
    'directors' => DirectorController::class,
    'actors' => ActorController::class,
    'categories' => CategoryController::class,
    'reviews' => ReviewController::class,
], ['only' => ['index', 'show']]);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/users', [UserController::class, 'index']); 
    Route::get('/users/{id}', [UserController::class, 'show']); 

    Route::get('watchlist', [WatchlistController::class, 'index']); 
    Route::post('watchlist', [WatchlistController::class, 'store']); 
    Route::delete('watchlist/{movie_id}', [WatchlistController::class, 'destroy']); 

    Route::apiResources([
    'users' => UserController::class,
    'movies' => MovieController::class,
    'directors' => DirectorController::class,
    'actors' => ActorController::class,
    'categories' => CategoryController::class,
    'reviews' => ReviewController::class,
], ['except' => ['index', 'show']]);

});



