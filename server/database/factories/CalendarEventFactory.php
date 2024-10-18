<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CalendarEvent>
 */
class CalendarEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::parse(fake()->dateTimeBetween('now', '+1 week'));
        $endDate = $startDate->copy()->addHours(2);

        $location = fake()->boolean() ? fake()->address() : "ONLINE";

        return [
            'title' => fake()->sentence(),
            'location' => $location,
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
