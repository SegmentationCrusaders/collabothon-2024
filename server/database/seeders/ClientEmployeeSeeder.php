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
        $specialists = BankEmployee::factory(rand(2, 3))->create();

        $client = Client::factory()
            ->for($generalAdvisor)
            ->create();

        $roles = Role::all();
        $tags = CalendarActionTag::all();

        $roles->each(function (Role $role) use ($client, $tags, $specialists) {
            $clientEmployees = ClientEmployee::factory(2)
                ->for($client)
                ->for($role)
                ->create();

            $clientEmployees->each(function (ClientEmployee $clientEmployee) use ($tags, $specialists) {
                $calendarActions = CalendarAction::factory(5)
                    ->for($clientEmployee)
                    ->hasAttached($tags->random())
                    ->create();

                $calendarActions->each(function (CalendarAction $calendarAction) use ($clientEmployee, $specialists) {
                    $calendarEvents = CalendarEvent::factory(4)
                        ->for($calendarAction)
                        ->hasAttached($specialists, ['accepted' => true])
                        ->create();

                    $calendarEvents->each(function (CalendarEvent $calendarEvent) use ($clientEmployee) {
                        $calendarEvent->clientEmployees()->attach($clientEmployee);
                    });

                    $calendarAction->calendarEvents()->saveMany($calendarEvents);

                    $calendarActionStatuses = CalendarActionStatus::factory(4)
                        ->for($calendarAction)
                        ->create();

                    $calendarAction->calendarActionStatuses()->saveMany($calendarActionStatuses);
                });
            });
        });
    }
}
