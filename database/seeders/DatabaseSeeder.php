<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Director;
use App\Models\Actor;
use App\Models\Movie;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Matikan Foreign Key biar lancar
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // 2. Buat Admin
        $admin = User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'username' => 'yasminulfah',
                'password' => Hash::make('password123'),
            ]
        );

        // 3. Buat 5 User Random (Gunakan cara manual agar tidak error fake)
        for ($i = 0; $i < 5; $i++) {
            User::updateOrCreate(
                ['email' => "user{$i}@gmail.com"],
                [
                    'username' => "user_streaming_{$i}",
                    'password' => Hash::make('password123'),
                ]
            );
        }

        // 4. Buat 5 Kategori
        $categoryNames = ['Action', 'Drama', 'Horror', 'Sci-Fi', 'Animation'];
        foreach ($categoryNames as $name) {
            Category::updateOrCreate(['category_name' => $name]);
        }

        $catIds = Category::pluck('category_id')->toArray();
        
        // 5. Buat Director & Actor (Hanya jika factory sudah benar, jika ragu ganti ke create manual)
        // Saya asumsikan factory kamu sudah benar, jika error lagi di sini, kabari ya!
        if (Director::count() == 0) Director::factory(10)->create();
        if (Actor::count() == 0) Actor::factory(10)->create();
        
        $dirIds = Director::pluck('director_id')->toArray();
        $actorIds = Actor::pluck('actor_id')->toArray();

        // 6. Masukkan 15 Film
        $moviesData = [
            ['title' => 'Avatar: Fire and Ash', 'poster' => 'https://image.tmdb.org/t/p/w500/vGv0vW66Nre3Uat97vP6NY0rYvJ.jpg'],
            ['title' => 'Zootopia 2', 'poster' => 'https://image.tmdb.org/t/p/w500/1p6uWpPpsm6RlsR380k8iC7Tz6m.jpg'],
            ['title' => 'The Housemaid', 'poster' => 'https://image.tmdb.org/t/p/w500/AmIcl0BshG719eK3L2U2QW1E6Lp.jpg'],
            ['title' => 'Shine On Me', 'poster' => 'https://image.tmdb.org/t/p/w500/6vS0vW66Nre3Uat97vP6NY0rYvJ.jpg'],
            ['title' => 'The SpongeBob Movie', 'poster' => 'https://image.tmdb.org/t/p/w500/7O0OAs6K98T3S7L00L6Sj4mY5mR.jpg'],
            ['title' => 'Fallout', 'poster' => 'https://image.tmdb.org/t/p/w500/7S9p6S6O682V6fS5688S7T4S6mS.jpg'],
            ['title' => 'Goodbye June', 'poster' => 'https://image.tmdb.org/t/p/w500/w7S9p6S6O682V6fS5688S7T4S6mS.jpg'],
            ['title' => 'The Swords', 'poster' => 'https://image.tmdb.org/t/p/w500/xGv0vW66Nre3Uat97vP6NY0rYvJ.jpg'],
            ['title' => 'Made in Korea', 'poster' => 'https://image.tmdb.org/t/p/w500/yGv0vW66Nre3Uat97vP6NY0rYvJ.jpg'],
            ['title' => 'Anaconda', 'poster' => 'https://image.tmdb.org/t/p/w500/kS6S6O682V6fS5688S7T4S6mS.jpg'],
            ['title' => 'Dear X', 'poster' => 'https://image.tmdb.org/t/p/w500/zGv0vW66Nre3Uat97vP6NY0rYvJ.jpg'],
            ['title' => 'Insidious', 'poster' => 'https://image.tmdb.org/t/p/w500/9S6S6O682V6fS5688S7T4S6mS.jpg'],
            ['title' => 'Dear Nathan', 'poster' => 'https://image.tmdb.org/t/p/w500/aGv0vW66Nre3Uat97vP6NY0rYvJ.jpg'],
            ['title' => 'Barbie', 'poster' => 'https://image.tmdb.org/t/p/w500/iuFNm9pYFZno3o3TemHsidvS9mP.jpg'],
            ['title' => 'Dragon Ball', 'poster' => 'https://image.tmdb.org/t/p/w500/bGv0vW66Nre3Uat97vP6NY0rYvJ.jpg'],
        ];

        foreach ($moviesData as $m) {
            $movie = Movie::updateOrCreate(
                ['title' => $m['title']],
                [
                    'poster_url' => $m['poster'],
                    'release_year' => 2024,
                    'duration' => 120,
                    'description' => 'Description for ' . $m['title'],
                    'language' => 'English',
                    'category_id' => $catIds[array_rand($catIds)],
                    'director_id' => $dirIds[array_rand($dirIds)],
                ]
            );

            // 7. Tambahkan Aktor ke Film
            $movie->actors()->sync(array_slice($actorIds, 0, 3));

            // 8. Tambahkan Review
            Review::updateOrCreate(
                ['movie_id' => $movie->movie_id, 'user_id' => $admin->user_id],
                [
                    'rating' => rand(4, 5),
                    'review_text' => 'Film yang sangat direkomendasikan!',
                ]
            );
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}