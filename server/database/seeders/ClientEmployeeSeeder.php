<?php

namespace Database\Seeders;

use App\Models\CalendarAction;
use App\Models\CalendarActionStatus;
use App\Models\CalendarActionTag;
use App\Models\CalendarEvent;
use App\Models\Client;
use App\Models\ClientEmployee;
use App\Models\Role;
use Illuminate\Database\Seeder;

class ClientEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = Client::all();
        $roles = Role::all();

        $clients->each(function ($client) use ($roles) {
            ClientEmployee::factory(2)
                ->for($client)
                ->for($roles->random())
                ->has(
                    CalendarAction::factory()
                        ->has(CalendarActionTag::factory()->count(3))
                        ->has(CalendarEvent::factory()->count(4))
                        ->has(
                            CalendarActionStatus::factory()
                                ->count(4)
                        )
                        ->count(5)
                )
                ->create();
        });
    }
}
