<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\Jam;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition()
    {
        return [
            'jam_id' => Jam::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['pending', 'complete']),
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}