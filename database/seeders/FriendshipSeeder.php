<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
  $users = \App\Models\User::all();
        foreach ($users as $index => $user) {
            $friend = $users->where('id', '!=', $user->id)->random();
            \App\Models\Friendship::create([
                'user_id' => $user->id,
                'friend_id' => $friend->id,
                'status' => $index % 2 == 0 ? 'accepted' : 'pending',
            ]);
        }
    }
}
