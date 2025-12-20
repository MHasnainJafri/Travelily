<?php

namespace Database\Factories;

use App\Models\TaskAssignee;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskAssigneeFactory extends Factory
{
    protected $model = TaskAssignee::class;

    public function definition()
    {
        return [
            'task_id' => Task::factory(),
            'user_id' => User::factory(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}