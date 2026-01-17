<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Director extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'director_id';

    protected $fillable = [
        'first_name',
        'last_name',
        'birth_date',
        'nationality',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }
    
    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class, 'director_id', 'director_id');
    }
}
