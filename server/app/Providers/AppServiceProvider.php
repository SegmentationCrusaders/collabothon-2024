<?php

namespace App\Providers;

use App\Helpers\ClientEmployeeAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::viaRequest('apiKey', function ($request) {
            return ClientEmployeeAuth::tryAuthenticate($request);
        });
    }
}
