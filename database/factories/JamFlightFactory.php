<?php

namespace Database\Factories;

use App\Models\JamFlight;
use App\Models\Jam;
use Illuminate\Database\Eloquent\Factories\Factory;

class JamFlightFactory extends Factory
{
    protected $model = JamFlight::class;

    public function definition()
    {
        $date = $this->faker->dateTimeBetween('now', '+1 month');
        $departureTime = $this->faker->time('H:i:s');
        $arrivalTime = $this->faker->time('H:i:s', $departureTime);

        return [
            'jam_id' => Jam::factory(),
            'from' => $this->faker->city(),
            'to' => $this->faker->city(),
            'date' => $date,
            'departure_time' => $departureTime,
            'arrival_time' => $arrivalTime,
            'mode_of_transportation' => $this->faker->randomElement(['airplane', 'train', 'bus']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}