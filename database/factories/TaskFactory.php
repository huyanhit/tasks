<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->title,
            'content' => fake()->text(100),
            'status_id' => rand(1, 4),
            'project_id' => rand(1, 4),
            'auth_id' => rand(1, 4),
            'comment'=> fake()->text(50),
            'date_start'=>Carbon::now(),
            'date_end'=>Carbon::now()->addDays(rand(0, 2)),
        ];
    }
}
