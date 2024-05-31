<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllNutrient extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_url',
        'food_name',
        'gram_value',
        'kcal',
        'carbs',
        'protein',
        'fat',
    ];
}