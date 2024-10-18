<?php

namespace Database\Seeders;

use App\Helpers\Enums\CalendarActionTagEnum;
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
            'tag' => CalendarActionTagEnum::CASH_FLOW->value,
        ]);

        CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::SHORT_TERM_LOAN->value,
        ]);

        CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::LONG_TERM_LOAN->value,
        ]);

        CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::PAYMENT->value,
        ]);

        CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::TECHNICAL->value,
        ]);

        CalendarActionTag::firstOrCreate([
            'tag' => CalendarActionTagEnum::OTHER->value,
        ]);
    }
}
