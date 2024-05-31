<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Services\ScrapingService;
use App\Enums\FoodType;

class DiyetKolikImport extends Command
{
    protected $signature = 'diyetkolik:import';
    protected $description = 'Import nutrient data from Diyetkolik';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $scrapingService = new ScrapingService();

        $gram_value = $this->ask('Please enter the gram value (1-999)');

        if (!is_numeric($gram_value) || $gram_value < 1 || $gram_value > 999) {
            $this->error('Invalid gram value. Please enter a value between 1 and 999.');
            return;
        }

        foreach (FoodType::cases() as $foodType) {
            $this->info('Scraping data for ' . $foodType->value . ' with gram value: ' . $gram_value);
            $scrapingService->scrapeAndCalculate($foodType->value, $gram_value);
        }

        $this->info('Scraping all nutrient data with default gram value: 1');
        $scrapingService->scrapeAllNutrients(1);

        $this->info('Data scraping and saving completed.');
    }
}