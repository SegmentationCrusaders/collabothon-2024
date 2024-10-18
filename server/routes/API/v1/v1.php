<?php

use App\Http\Controllers\CalendarActionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(['message' => 'Hello, World!']);
})->name('index');

Route::get('/calendar-actions', [CalendarActionController::class, 'index']);
