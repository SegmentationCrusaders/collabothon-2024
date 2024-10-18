<?php

use App\Http\Controllers\CalendarActionController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:apiKey'])->group(function () {
    Route::get('/calendar-actions', [CalendarActionController::class, 'index']);
});
