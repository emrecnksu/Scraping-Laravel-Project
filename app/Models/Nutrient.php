<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nutrient extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_url',
        'food_name',
        'food_type',
        'gram_value',
        'kcal',
        'carbs',
        'protein',
        'fat',
    ];
}
