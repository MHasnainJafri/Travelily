<?php

namespace Database\Factories;

use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoryFactory extends Factory
{
    protected $model = Story::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'content' => $this->faker->sentence,
            'visibility' => $this->faker->randomElement(['public', 'friends', 'selected']),
            'status' => $this->faker->randomElement(['active', 'inactive']),
            'expires_at' => now()->addHours(24),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Story $story) {
            $mediaCount = $this->faker->numberBetween(1, 2);
            for ($i = 0; $i < $mediaCount; $i++) {
                $imageUrl = "https://picsum.photos/200/300";
                $story->addMediaFromUrl($imageUrl)
                    ->preservingOriginal()
                    ->toMediaCollection('story_media');
            }
        });
    }
}
