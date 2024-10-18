<?php

namespace Database\Seeders;

use App\Models\BankEmployee;
use Illuminate\Database\Seeder;

class BankEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BankEmployee::factory(count: 5)
            ->create();
    }
}
