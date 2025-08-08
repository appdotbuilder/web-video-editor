<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'width' => fake()->randomElement([1920, 1280, 3840]),
            'height' => fake()->randomElement([1080, 720, 2160]),
            'fps' => fake()->randomElement([24, 30, 60]),
            'settings' => [
                'quality' => 'high',
                'format' => 'mp4',
            ],
            'status' => 'active',
        ];
    }

    /**
     * Indicate that the project is archived.
     */
    public function archived(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'archived',
        ]);
    }
}