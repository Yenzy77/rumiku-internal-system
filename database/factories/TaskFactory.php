<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'assigned_to' => User::factory(),
            'status' => fake()->randomElement(['pending', 'in_progress', 'completed']),
            'due_date' => fake()->dateTimeBetween('now', '+1 month'),
        ];
    }
}
