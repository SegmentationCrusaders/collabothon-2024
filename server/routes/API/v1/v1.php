<?php

use App\Helpers\ClientEmployeeAuth;
use App\Http\Controllers\CalendarActionController;
use App\Http\Controllers\CalendarActionTagController;
use App\Http\Controllers\CalendarActionTemplateController;
use App\Http\Resources\ClientEmployeeResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/client-employee', function (Request $request) {
    $emp = ClientEmployeeAuth::tryAuthenticate($request);

    if (!isset($emp)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return new ClientEmployeeResource($emp);
});

Route::middleware(['auth:apiKey'])->group(function () {

    Route::get('/calendar-actions', [CalendarActionController::class, 'index']);

    Route::get('/calendar-action-tags', [CalendarActionTagController::class, 'index']);

    Route::get('/calendar-action-templates', [CalendarActionTemplateController::class, 'index']);

});
