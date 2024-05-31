<?php

namespace App\Http\Classes;

use App\Enums\FoodType;
use App\Models\Nutrient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\BrowserKit\HttpBrowser;

class ScrapingNutrients
{
    private $urls = [
        FoodType::CHICKEN->value => 'https://www.diyetkolik.com/kac-kalori/tavuk-gogus-derisiz-cig',
        FoodType::BANANA->value => 'https://www.diyetkolik.com/kac-kalori/muz',
        FoodType::EGG->value => 'https://www.diyetkolik.com/kac-kalori/haslanmis-yumurta',
        FoodType::PILAF->value => 'https://www.diyetkolik.com/kac-kalori/tereyagli-pirinc-pilavi',
        FoodType::DATES->value => 'https://www.diyetkolik.com/kac-kalori/kuru-hurma',
        FoodType::YOGURT->value => 'https://www.diyetkolik.com/kac-kalori/yogurt',
    ];

    public function scrape($food_name, $gram_value = 1)
    {
        if (!isset($this->urls[$food_name])) {
            return null;
        }

        $url = $this->urls[$food_name];
        $httpClient = HttpClient::create();
        $browser = new HttpBrowser($httpClient);
        $crawler = $browser->request('GET', $url);

        $selectors = [
            'kcal' => '.mt-3 .carb-prot-fat .text-center .centerADivInAnother .nut_kcal_count2',
            'carbs' => '.kkMikroDegerlerTasiyici .kkTable .lbl_carb100',
            'protein' => '.kkMikroDegerlerTasiyici .kkTable .lbl_prot100',
            'fat' => '.kkMikroDegerlerTasiyici .kkTable .lbl_fat100',
        ];

        $data = [];
        foreach ($selectors as $key => $selector) {
            $value = $crawler->filter($selector)->text();
            $data[$key] = $value;
        }

        $calculator = new CalculatingNutrients();
        $calculatedData = $calculator->calculateNutrients($data, $gram_value, $food_name);
        
        $nutrient = new Nutrient();
        $nutrient->source_url = $url;
        $nutrient->food_name = $food_name;
        $nutrient->gram_value = $gram_value;
        $nutrient->kcal = $calculatedData['kcal'];
        $nutrient->carbs = $calculatedData['carbs'];
        $nutrient->protein = $calculatedData['protein'];
        $nutrient->fat = $calculatedData['fat'];
        $nutrient->food_type = array_search($food_name, array_column(FoodType::cases(), 'value'));
        $nutrient->save();

        return $nutrient;
    }
}
