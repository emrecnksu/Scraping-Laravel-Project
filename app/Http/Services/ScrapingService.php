<?php

namespace App\Http\Services;

use App\Enums\FoodType;
use App\Models\Nutrient;
use App\Models\AllNutrient;
use App\Models\Processedurl;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\BrowserKit\HttpBrowser;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Http\Classes\CalculatingNutrients;

class ScrapingService
{

    // General Nutrients //

    private $urls = [
        FoodType::CHICKEN->value => 'https://www.diyetkolik.com/kac-kalori/tavuk-gogus-derisiz-cig',
        FoodType::BANANA->value => 'https://www.diyetkolik.com/kac-kalori/muz',
        FoodType::EGG->value => 'https://www.diyetkolik.com/kac-kalori/haslanmis-yumurta',
        FoodType::PILAF->value => 'https://www.diyetkolik.com/kac-kalori/tereyagli-pirinc-pilavi',
        FoodType::DATES->value => 'https://www.diyetkolik.com/kac-kalori/kuru-hurma',
        FoodType::YOGURT->value => 'https://www.diyetkolik.com/kac-kalori/yogurt',
    ];

    public function scrapeAndCalculate($food_name, $gram_value = 1)
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

    // All Nutrients //   

    public function scrapeAllNutrients($gramValue = 1)
    {
        set_time_limit(300);

        $urls = $this->getUrlsFromSitemap();

        $processedUrls = Processedurl::pluck('url')->toArray();

        $newUrls = array_diff($urls, $processedUrls);

        $foodUrls = array_filter($newUrls, function ($url) {
            return strpos($url, 'https://www.diyetkolik.com/kac-kalori/') === 0;
        });

        $httpClient = Http::retry(3, 100);
        $browser = new Crawler();

        $allNutritionData = [];

        foreach ($foodUrls as $url) {
            try {
                $response = $httpClient->get($url);
                $content = $response->body();
                $crawler = new Crawler($content, $url);

                $foodName = $this->extractFoodNameFromUrl($url);
            
                $selectors = [
                    'kcal' => '.mt-3 .carb-prot-fat .text-center .centerADivInAnother .kkBigNumber',
                    'carbs' => '.kkMikroDegerlerTasiyici .kkTable .lbl_carb100',
                    'protein' => '.kkMikroDegerlerTasiyici .kkTable .lbl_prot100',
                    'fat' => '.kkMikroDegerlerTasiyici .kkTable .lbl_fat100',
                ];
                
                $nutritionData = [];
                
                foreach ($selectors as $key => $selector) {
                    $nodes = $crawler->filter($selector);
                    if ($nodes->count() > 0) {
                        $value = $nodes->text();
                        $value = $this->cleanNumericNutrient($value) / 100;
                        $nutritionData[$key] = $value;
                    } else {
                        $nutritionData[$key] = null;
                    }
                }
        
                $nutrition = new AllNutrient();
                $nutrition->source_url = $url;
                $nutrition->food_name = $foodName;
                $nutrition->gram_value = $gramValue;
                $nutrition->kcal = $nutritionData['kcal'] ?? null;
                $nutrition->carbs = $nutritionData['carbs'] ?? null;
                $nutrition->protein = $nutritionData['protein'] ?? null;
                $nutrition->fat = $nutritionData['fat'] ?? null;
                $nutrition->save();
                
                Processedurl::create(['url' => $url]);
                
                $allNutritionData[] = $nutritionData;
            } catch (\Exception $e) {
                \Log::error('Error scraping data for URL: ' . $url);
                continue;
            }
        }

        return $allNutritionData;
    }

    private function getUrlsFromSitemap()
    {
        $sitemap = file_get_contents('https://www.diyetkolik.com/sitemap.xml');

        preg_match_all('/<loc>(.*?)<\/loc>/', $sitemap, $matches);

        return isset($matches[1]) ? $matches[1] : [];
    }

    private function extractFoodNameFromUrl($url)
    {
        $parts = explode('/', $url);
        return end($parts); 
    }

    private function cleanNumericNutrient($value)
    {
        if (is_numeric($value)) {
            if (strpos($value, '.') !== false) {
                return (float) $value;
            } else {
                return (int) $value;
            }
        } else {
            return $value;
        }
    }
}
