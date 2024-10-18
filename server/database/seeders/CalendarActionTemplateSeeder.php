<?php

namespace Database\Seeders;

use App\Models\CalendarActionTag;
use App\Models\CalendarActionTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CalendarActionTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = CalendarActionTag::all();

        $tags->each(function (CalendarActionTag $tag) {
            CalendarActionTemplate::factory(2)
                ->hasAttached($tag)
                ->create();
        });
    }
}
