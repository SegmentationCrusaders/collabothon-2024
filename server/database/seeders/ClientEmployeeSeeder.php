<?php

namespace Database\Seeders;

use App\Models\BankEmployee;
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
        $generalAdvisor = BankEmployee::factory()->create();

        $client = Client::factory()
            ->for($generalAdvisor)
            ->create();

        $roles = Role::all();
        $tags = CalendarActionTag::all();

        $roles->each(function (Role $role) use ($client, $tags) {
            $clientEmployees = ClientEmployee::factory(2)
                ->for($client)
                ->for($role)
                ->has(
                    CalendarAction::factory()
                        ->hasAttached($tags->random())
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
