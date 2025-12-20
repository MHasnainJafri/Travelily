<?php

namespace Database\Seeders;

use App\Models\HouseRule;
use Illuminate\Database\Seeder;

class HouseRuleSeeder extends Seeder
{
    public function run()
    {
        $rules = [
            'No Pets',
            'No Littering',
            'No Drugs',
            'No Smoking',
        ];

        foreach ($rules as $rule) {
            HouseRule::create(['name' => $rule]);
        }
    }
}
