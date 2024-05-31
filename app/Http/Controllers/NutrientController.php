<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\ScrapingService;

class NutrientController extends Controller
{
    
    // Home Page //

    public function HomePage()
    {
        $foods = [
            [
                'name' => 'Tavuk Göğüs (Derisiz, Çiğ)',
                'route' => 'tavuk-gogus-derisiz-cig',
                'url' => 'https://www.diyetkolik.com/site_media/media/nutrition_images/tavuk-gogus-derisiz-cig.webp'
            ],
            [
                'name' => 'Muz',
                'route' => 'muz',
                'url' => 'https://www.diyetkolik.com/site_media/media/nutrition_images/muz.webp'
            ],
            [
                'name' => 'Haşlanmış Yumurta',
                'route' => 'haslanmis-yumurta',
                'url' => 'https://www.diyetkolik.com/site_media/media/nutrition_images/haslanmis-yumurta.webp'
            ],
            [
                'name' => 'Tereyağlı Pirinç Pilavı',
                'route' => 'tereyagli-pirinc-pilavi',
                'url' => 'https://www.diyetkolik.com/site_media/media/nutrition_images/tereyagli-pirinc-pilavi.webp'
            ],
            [
                'name' => 'Kuru Hurma',
                'route' => 'kuru-hurma',
                'url' => 'https://www.diyetkolik.com/site_media/media/nutrition_images/kuru-hurma.webp'
            ],
            [
                'name' => 'Yoğurt',
                'route' => 'yogurt',
                'url' => 'https://www.diyetkolik.com/site_media/media/nutrition_images/yogurt.webp'
            ],
        ];

        return view('homepage', compact('foods'));
    }

    // General Nutrients //

    public function GeneralNutrients(Request $request, $food)
    {
        $scrapingService = new ScrapingService();
        $gram_value = $request->input('gram_value', 1);

        $englishNames = [
            'tavuk-gogus-derisiz-cig' => 'chicken',
            'muz' => 'banana',
            'haslanmis-yumurta' => 'egg',
            'tereyagli-pirinc-pilavi' => 'pilaf',
            'kuru-hurma' => 'dates',
            'yogurt' => 'yogurt'
        ];

        $foodEnglish = $englishNames[$food] ?? $food;

        $nutrient = $scrapingService->scrapeAndCalculate($foodEnglish, $gram_value);

        $food_names = [
            'chicken' => 'Tavuk Göğüs (Derisiz, Çiğ)',
            'banana' => 'Muz',
            'egg' => 'Haşlanmış Yumurta',
            'pilaf' => 'Tereyağlı Pirinç Pilavı',
            'dates' => 'Kuru Hurma',
            'yogurt' => 'Yoğurt'
        ];

        $food_name = $food_names[$foodEnglish] ?? ucfirst($food);

        return view('nutrient', compact('nutrient', 'gram_value', 'food_name', 'food'));
    }

    // All Nutrients //    

    public function ScrapingAllNutrients(Request $request)
    {
        $scrapingService = new ScrapingService();
        $gramValue = $request->input('gram_value', 1);

        $scrapingService->scrapeAllNutrients($gramValue);

        return redirect()->route('Home')->with(['message' => 'Veriler başarıyla çekilmiştir.']);
    }
}