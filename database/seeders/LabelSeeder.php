<?php

namespace Database\Seeders;

use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labels = ['Travel', 'Adventure', 'Food', 'Culture'];

        foreach ($labels as $label) {
            Label::create(['name' => $label]);
        }
    }
}
