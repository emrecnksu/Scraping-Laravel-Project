<?php

namespace App\Http\Classes;

use App\Enums\FoodType;

class CalculatingNutrients
{
    public function calculateNutrients($data, $gram_value = 1, $food_name)
    {
        $calculatedData = [];

        $adjustments = [
            FoodType::CHICKEN->value => ['kcal' => 0.8, 'modifier' => 1/100],
            FoodType::BANANA->value => ['kcal' => -62, 'modifier' => 1/100],
            FoodType::EGG->value => ['kcal' => 78, 'modifier' => 1/100],
            FoodType::PILAF->value => ['kcal' => -116.295, 'modifier' => 1],
            FoodType::DATES->value => ['kcal' => 250, 'modifier' => 1],
            FoodType::YOGURT->value => ['kcal' => -61, 'modifier' => 1/100]
        ];

        $adjustment = $adjustments[$food_name] ?? null;

        if ($adjustment) {
            foreach ($data as $key => $value) {
                if ($key === 'kcal') {
                    $calculatedData[$key] = ($value + $adjustment['kcal']) * $adjustment['modifier'] * $gram_value;
                } else {
                    $calculatedData[$key] = ($value * $adjustment['modifier']) * $gram_value;
                }
            }
        }

        return $calculatedData;
    }
}