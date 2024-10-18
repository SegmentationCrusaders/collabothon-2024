<?php

namespace Database\Seeders;

use App\Models\BankEmployee;
use App\Models\Client;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $generalAdvisors = BankEmployee::all();

        $generalAdvisors->each(function ($generalAdvisor) {
            Client::factory(count: 2)
                ->for($generalAdvisor)
                ->create();
        });
    }
}
