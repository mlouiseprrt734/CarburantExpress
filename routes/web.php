<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FuelPriceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return view('about');
});
Route::get('/mentions-legales', function () {
    return view('mentions-legales');
});


Route::get('dashboard', [FuelPriceController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //gestion des préférences de l'utilisateur
    Route::patch('/profile/preferences', [ProfileController::class, 'update_preferences'])->name('profile.preferences');
    
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/fuel-prices', [FuelPriceController::class, 'getFuelPrices']);
    Route::post('/recherche', [FuelPriceController::class, 'searchFuelPrices'])->name('recherche');
});

require __DIR__.'/auth.php';
