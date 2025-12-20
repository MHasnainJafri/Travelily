<?php

namespace Database\Factories;

use App\Models\Jam;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JamFactory extends Factory
{
    protected $model = Jam::class;

    public function definition()
    {
        $startDate = $this->faker->dateTimeBetween('now', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, '+2 months');

        return [
            'name' => $this->faker->sentence(3),
            'destination' => $this->faker->city(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'budget_min' => $this->faker->numberBetween(300, 1000),
            'budget_max' => $this->faker->numberBetween(1000, 5000),
            'num_guests' => $this->faker->numberBetween(1, 10),
            //random user here already added
            'creator_id'=>  $this->faker->numberBetween(1,User::count()),
            'is_locked' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Jam $jam) {
            $user = User::factory()->create();
            $jam->users()->attach($user->id, [
                'role' => 'creator',
                'can_edit_jamboard' => true,
                'can_add_travellers' => true,
                'can_edit_budget' => true,
                'can_add_destinations' => true,
            ]);
        });
    }
}