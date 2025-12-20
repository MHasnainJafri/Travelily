<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostCheckIn;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition()
    {
        return [
            'content' => $this->faker->paragraph,
            'user_id' => User::factory(),
            'visibility' => $this->faker->randomElement(['public', 'friends', 'selected']),
            'status' => $this->faker->randomElement(['active', 'inactive', 'draft']),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Post $post) {
            // Add 2-5 media files (images or videos)
            $mediaCount = $this->faker->numberBetween(2, 5);
            for ($i = 0; $i < 2; $i++) {
                $fileType = $this->faker->randomElement(['image']);
                if ($fileType === 'image') {
                    $post->addMediaFromUrl('https://picsum.photos/200/300')
                        ->toMediaCollection('post_media');
                } else {
                    $post->addMediaFromUrl('http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4')
                        ->toMediaCollection('post_media');
                }
            }

            // Tag 1-3 random users
            $taggedUsers = User::inRandomOrder()->limit($this->faker->numberBetween(1, 3))->pluck('id')->toArray();
            $post->taggedUsers()->sync($taggedUsers);

            // Add check-in 50% of the time
            if ($this->faker->boolean(50)) {
                PostCheckIn::create([
                    'post_id' => $post->id,
                    'location_name' => $this->faker->city,
                    'latitude' => $this->faker->latitude,
                    'longitude' => $this->faker->longitude,
                ]);
            }
        });
    }
}
