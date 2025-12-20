<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\PostComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostCommentFactory extends Factory
{
    protected $model = PostComment::class;

    public function definition()
    {
        return [
            'post_id' => Post::factory(),
            'user_id' => User::factory(),
            'parent_id' => null,
            'content' => $this->faker->paragraph,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (PostComment $comment) {
            // Add 0-2 replies 50% of the time
            if ($this->faker->boolean(50)) {
                $replyCount = $this->faker->numberBetween(0, 2);
                PostComment::factory()->count($replyCount)->create([
                    'post_id' => $comment->post_id,
                    'parent_id' => $comment->id,
                    'user_id' => User::factory(),
                ]);
            }
        });
    }
}
