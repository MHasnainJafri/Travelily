<?php

namespace Database\Seeders;

use App\Models\Board;
use App\Models\Jam;
use App\Models\Label;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run()
    {
        $jane = User::where('username', 'janecooper')->first();
        $jaylon = User::where('username', 'jaylonlipshutz')->first();
        $kaylyn = User::where('username', 'kaylynrosser')->first();

        if (! $jane || ! $jaylon || ! $kaylyn) {
            $this->command->error('Required users (janecooper, jaylonlipshutz, kaylynrosser) not found. Please run UserSeeder first.');

            return;
        }

        $board = Jam::latest()->first();
        if (! $board) {
            $this->command->error('Required board (American Wanderers) not found. Please run BoardSeeder first.');

            return;
        }

        $travelLabel = Label::where('name', 'Travel')->first();
        $adventureLabel = Label::where('name', 'Adventure')->first();
        if (! $travelLabel || ! $adventureLabel) {
            $this->command->error('Required labels (Travel, Adventure) not found. Please run LabelSeeder first.');

            return;
        }

        // Post 1: Lake Louise Post
        $post = Post::create([
            'user_id' => $jane->id,
            'content' => 'Lake Louise in Banff National Park, Canada',
        ]);

        // Add a photo to the post (mock path for seeding)
        $post->addMediaFromUrl(env('APP_URL') . '/sample.png')
            ->toMediaCollection('post_photos');

        // Tag users in the post
        $post->taggedUsers()->attach([$jaylon->id, $kaylyn->id]);

        // Tag the board in the post
        $post->taggedBoards()->attach($board->id);

        // Add likes to the post
        $post->likes()->attach([$jaylon->id, $kaylyn->id]);

        // Add labels to the post
        $post->labels()->attach([$travelLabel->id, $adventureLabel->id]);
    }
}
