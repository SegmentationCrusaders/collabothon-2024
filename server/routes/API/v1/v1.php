<?php

use App\Helpers\APIAuth;
use App\Http\Controllers\BankEmployeeController;
use App\Http\Controllers\CalendarActionController;
use App\Http\Controllers\CalendarEventActionController;
use App\Http\Controllers\CalendarActionTagController;
use App\Http\Controllers\CalendarActionTemplateController;
use App\Http\Resources\BankEmployeeResource;
use App\Http\Resources\ClientEmployeeResource;
use App\Models\ClientEmployee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    $emp = APIAuth::tryAuthenticate($request);

    if (!isset($emp)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    return $emp instanceof ClientEmployee ?
        new ClientEmployeeResource($emp) : new BankEmployeeResource($emp);
});

Route::middleware(['auth:apiKey'])->group(function () {

    Route::get('/calendar-actions', [CalendarActionController::class, 'index']);
    Route::post('/calendar-actions', [CalendarActionController::class, 'store']);

    Route::get('/calendar-action-tags', [CalendarActionTagController::class, 'index']);

    Route::get('/calendar-action-templates', [CalendarActionTemplateController::class, 'index']);

    Route::get('/bank-employees/getGeneralAdvisor', [BankEmployeeController::class, 'getGeneralAdvisor']);

    Route::get('/general-advisor', [CalendarActionTemplateController::class, 'index']);

    Route::post('/calendar-event-decline/{uuid}', [CalendarEventActionController::class, 'decline'])
        ->name('calendar-event-decline')
    ;

    Route::post('/calendar-event-accept/{uuid}', [CalendarEventActionController::class, 'accept'])
        ->name('calendar-event-accept')
    ;

    Route::post('/calendar-event-change-interval/{uuid}', [CalendarEventActionController::class, 'changeInterval'])
        ->name('calendar-event-change-interval')
    ;
});
