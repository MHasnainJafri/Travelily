<?php

namespace Database\Factories;

use App\Models\Itinerary;
use App\Models\Jam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItineraryFactory extends Factory
{
    protected $model = Itinerary::class;

    public function definition()
    {
        $date = $this->faker->dateTimeBetween('now', '+1 month');

        return [
            'jam_id' => Jam::factory(),
            'type' => 'accommodation', // Default type
            'title' => $this->faker->sentence(2),
            'details' => json_encode([
                'location' => $this->faker->city(),
                'category' => 'hotel',
                'time' => $this->faker->time('H:i:s'),
            ]),
            'date' => $date,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function accommodation()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'accommodation',
                'details' => json_encode([
                    'location' => $this->faker->city(),
                    'category' => $this->faker->randomElement(['hotel', 'hostel', 'airbnb']),
                    'time' => $this->faker->time('H:i:s'),
                ]),
            ];
        });
    }

    public function activity()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'activity',
                'details' => json_encode([
                    'location' => $this->faker->city(),
                    'category' => $this->faker->randomElement(['tour', 'hiking', 'sightseeing']),
                    'time' => $this->faker->time('H:i:s'),
                ]),
            ];
        });
    }

    public function experience()
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'experience',
                'details' => json_encode([
                    'location' => $this->faker->city(),
                    'category' => $this->faker->randomElement(['relaxation', 'adventure', 'cultural']),
                    'time' => $this->faker->time('H:i:s'),
                ]),
            ];
        });
    }
}