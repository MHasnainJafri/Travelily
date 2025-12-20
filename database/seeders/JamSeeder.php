<?php

namespace Database\Seeders;

use App\Models\Jam;
use App\Models\Task;
use App\Models\Itinerary;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class JamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Jam::factory()
            ->count(5)
            ->hasflights(2)
            ->has(Itinerary::factory()->accommodation()->count(1))
            ->has(Itinerary::factory()->activity()->count(2))
            ->has(Itinerary::factory()->experience()->count(1))
            ->has(Task::factory()->count(3)->hasassignees(2))
            ->create();

    }
}
