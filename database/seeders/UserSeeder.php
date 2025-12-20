<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::factory()
            ->count(5)
            ->has(UserProfile::factory())
            ->create()
            ->each(function ($user, $index) {
                $roles = ['traveller', 'guide', 'admin', 'host'];
                $user->assignRole($roles[$index % count($roles)]);

                for ($i = 0; $i < 2; $i++) {
                    $user->addMediaFromUrl(env('APP_URL') . '/sample.png')->toMediaCollection('gallery');
                }
            });
        // Manually create 3 travellers
        $manualUsers = [
            [
                'name' => 'johndoe',
                'username' => 'janecooper',
                'email' => 'jane@example.com',
            ],
            [
                'name' => 'jamesbond',
                'username' => 'jaylonlipshutz',
                'email' => 'jaylon@example.com',
            ],
            [
                'name' => 'kaylynrosser',
                'username' => 'kaylynrosser',
                'email' => 'kaylyn@example.com',
            ],
        ];

        foreach ($manualUsers as $data) {
            $user = User::firstOrCreate(['email'=>$data['email']],[
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'password' => bcrypt('password'),
            ]);

            $user->assignRole('traveller');
        }
        // Create one admin user
        $admin = User::create([
            'name' => 'Admin User',
            'username' => 'adminuser',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
        ]);

        $admin->assignRole('admin');

       
    }
}
