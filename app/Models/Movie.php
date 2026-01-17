<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Movie extends Model
{
    use HasFactory;

    protected $primaryKey = 'movie_id';
    protected $appends = ['average_rating', 'rating_class'];
    
    protected $fillable = [
        'title',
        'description',
        'release_year',
        'duration',
        'language',
        'poster_url',
        'category_id',
        'director_id',
    ];

    protected function ratingClass(): Attribute
    {
        return Attribute::make(
            get: function () {
                $rating = $this->averageRating; 
                if (is_null($rating)) {
                    return 'Unrated';
                }
                
                if ($rating >= 8.5) {
                    return 'Top Rated';
                } elseif ($rating >= 7.0) {
                    return 'Popular';
                } else { 
                    return 'Regular';
                }
            },
        );
    }

    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->reviews()->avg('rating'),
        );
    }

    public function actors(): BelongsToMany
    {
        return $this->belongsToMany(Actor::class, 'movie_actors', 'movie_id', 'actor_id');
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(Director::class, 'director_id', 'director_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'movie_id', 'movie_id');
    }

    public function watchlistMovies(): BelongstoMany
    {
        return $this->belongsToMany(User::class, 'watchlist', 'movie_id', 'user_id')->withTimestamps();
    }
}
