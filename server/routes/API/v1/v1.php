<?php

use App\Http\Controllers\CalendarActionController;
use App\Http\Controllers\CalendarActionTemplateController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:apiKey'])->group(function () {

    Route::get('/calendar-actions', [CalendarActionController::class, 'index']);

    Route::get('/calendar-action-templates', [CalendarActionTemplateController::class, 'index']);

});
