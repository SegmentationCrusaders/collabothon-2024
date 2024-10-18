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
        CalendarActionTag::create([
            'tag' => 'Cash Flow',
        ]);

        CalendarActionTag::create([
            'tag' => 'Payment',
        ]);

        CalendarActionTag::create([
            'tag' => 'Technical',
        ]);

        CalendarActionTag::create([
            'tag' => 'Other',
        ]);
    }
}
