<?php

namespace Database\Seeders;

use App\Models\CalendarActionTag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalendarActionTagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CalendarActionTag::factory(10)
            ->create();
    }
}
