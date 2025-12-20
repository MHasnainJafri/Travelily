<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\User;
use App\Models\Jam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'reviewed_user_id' => User::factory(),
            'reviewer_id' => User::factory(),
            'trip_id' => Jam::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}