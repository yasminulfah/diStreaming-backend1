<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'movie_id' => 1, // Diatur otomatis oleh Seeder
            'user_id' => 1,  // Diatur otomatis oleh Seeder
            'rating' => fake()->randomFloat(1, 1, 10), // Rating 1.0 sampai 10.0
            'review_text' => fake()->paragraph(),
        ];
    }
}
