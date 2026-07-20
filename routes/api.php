<?php

use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'quote.throttle'])->group(function () {
    Route::get('/quote', [QuoteController::class, 'random'])->name('api.quote.random');
});