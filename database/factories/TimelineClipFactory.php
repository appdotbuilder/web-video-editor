<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\MediaFile;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TimelineClip>
 */
class TimelineClipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $duration = fake()->randomFloat(2, 1, 30);
        
        return [
            'project_id' => Project::factory(),
            'media_file_id' => MediaFile::factory(),
            'track' => fake()->numberBetween(0, 4),
            'start_time' => fake()->randomFloat(2, 0, 60),
            'duration' => $duration,
            'media_start' => 0,
            'media_end' => $duration,
            'properties' => [
                'opacity' => 1.0,
                'volume' => 1.0,
                'effects' => [],
            ],
            'z_index' => fake()->numberBetween(0, 10),
        ];
    }

    /**
     * Indicate that the clip is on a specific track.
     */
    public function onTrack(int $track): static
    {
        return $this->state(fn (array $attributes) => [
            'track' => $track,
        ]);
    }

    /**
     * Indicate that the clip starts at a specific time.
     */
    public function startsAt(float $time): static
    {
        return $this->state(fn (array $attributes) => [
            'start_time' => $time,
        ]);
    }

    /**
     * Indicate that the clip has a specific duration.
     */
    public function withDuration(float $duration): static
    {
        return $this->state(fn (array $attributes) => [
            'duration' => $duration,
            'media_end' => $duration,
        ]);
    }
}