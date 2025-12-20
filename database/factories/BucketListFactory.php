<?php

namespace Database\Factories;

use App\Models\BucketList;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BucketListFactory extends Factory
{
    protected $model = BucketList::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->paragraph(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}