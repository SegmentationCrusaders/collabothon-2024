<?php

namespace App\Providers;

use App\Helpers\Enums\RoleEnum;
use App\Models\ClientEmployee;
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
        // CEO                          2rqkCplZPDNNibrXTAyA576IeOLu18ASBiuer0oqmXuCruwJ5WAaF2KvAa9pCRh2
        // Controller                   koP7tEvVel3gfG0gWOjG3bTgrzo1ubzbfsD5vKll2mjVM263aEGPHhIZSIMWNdy1
        // Cash management specialist   Yk7lYm6LaZwpeDW4yJucFVJ5UaqfWbL9Hc9t5SjmgmZXs03HWZQnaBFErTANrFgm
        // Accountant                   BB4I3gN8OJZPFfVt4fWuYUYxhvnB0jS6feg0KQCx0u33EIl6aCgbx7qZ1VJOxsm0

        Auth::viaRequest('apiKey', function ($request) {
            $token = $request->bearerToken();

            $tokenRoleMap = [
                '2rqkCplZPDNNibrXTAyA576IeOLu18ASBiuer0oqmXuCruwJ5WAaF2KvAa9pCRh2' => RoleEnum::CEO->value,
                'koP7tEvVel3gfG0gWOjG3bTgrzo1ubzbfsD5vKll2mjVM263aEGPHhIZSIMWNdy1' => RoleEnum::CONTROLLER->value,
                'Yk7lYm6LaZwpeDW4yJucFVJ5UaqfWbL9Hc9t5SjmgmZXs03HWZQnaBFErTANrFgm' => RoleEnum::CASH_MANAGEMENT_SPECIALIST->value,
                'BB4I3gN8OJZPFfVt4fWuYUYxhvnB0jS6feg0KQCx0u33EIl6aCgbx7qZ1VJOxsm0' => RoleEnum::ACCOUNTANT->value,
            ];

            if (array_key_exists($token, $tokenRoleMap)) {
                return ClientEmployee::whereHas('role', fn($query) => $query->where('name', $tokenRoleMap[$token]))
                    ->with('role')
                    ->first();
            }

            return null;
        });
    }
}
