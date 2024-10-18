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
        CalendarActionTag::firstOrCreate([
            'tag' => 'Cash Flow',
        ]);

        CalendarActionTag::firstOrCreate([
            'tag' => 'Payment',
        ]);

        CalendarActionTag::firstOrCreate([
            'tag' => 'Technical',
        ]);

        CalendarActionTag::firstOrCreate([
            'tag' => 'Other',
        ]);
    }
}
