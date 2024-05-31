<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NutrientController;

// Home Page
Route::get('/', [NutrientController::class, 'HomePage'])->name('Home');
Route::get('/besinler', [NutrientController::class, 'HomePage'])->name('BacktoHomePage');

// All Nutrients
Route::get('/kac-kalori', [ScrapingController::class, 'ScrapingAllNutrients']);
Route::post('/kac-kalori', [ScrapingController::class, 'ScrapingAllNutrients']);

// General nutrient route
Route::get('/besin/{food}', [NutrientController::class, 'GeneralNutrients'])->name('GeneralNutrients');
Route::post('/besin/{food}', [NutrientController::class, 'GeneralNutrients']);