<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'status' => TaskStatus::DRAFT->name,
            'computing_resource_id' => Post::factory(),
            'files' => "[]",
        ];
    }
}
