<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $primaryKey = 'category_id';

    protected $fillable = [
        'category_name',
    ];

    public function movies(): HasMany
    {
        return $this->hasMany(Movie::class, 'category_id', 'category_id');
    }
}
