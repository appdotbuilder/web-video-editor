<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MediaFile>
 */
class MediaFileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = fake()->randomElement(['video', 'audio', 'image']);
        
        return [
            'user_id' => User::factory(),
            'name' => fake()->words(3, true),
            'filename' => fake()->uuid() . '.' . $this->getFileExtension($type),
            'path' => 'media/' . fake()->uuid() . '.' . $this->getFileExtension($type),
            'type' => $type,
            'mime_type' => $this->getMimeType($type),
            'size' => fake()->numberBetween(1000000, 100000000), // 1MB to 100MB
            'duration' => $type !== 'image' ? fake()->randomFloat(2, 10, 300) : null,
            'width' => $type !== 'audio' ? fake()->randomElement([1920, 1280, 3840]) : null,
            'height' => $type !== 'audio' ? fake()->randomElement([1080, 720, 2160]) : null,
            'thumbnail_path' => $type !== 'audio' ? 'thumbnails/' . fake()->uuid() . '.jpg' : null,
            'metadata' => [
                'bitrate' => fake()->numberBetween(1000, 50000),
                'codec' => fake()->randomElement(['h264', 'h265', 'vp9']),
            ],
        ];
    }

    /**
     * Indicate that the media file is a video.
     */
    public function video(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'video',
            'mime_type' => 'video/mp4',
            'filename' => fake()->uuid() . '.mp4',
            'path' => 'media/' . fake()->uuid() . '.mp4',
            'duration' => fake()->randomFloat(2, 30, 1800), // 30s to 30min
            'width' => fake()->randomElement([1920, 1280, 3840]),
            'height' => fake()->randomElement([1080, 720, 2160]),
        ]);
    }

    /**
     * Indicate that the media file is audio.
     */
    public function audio(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'audio',
            'mime_type' => 'audio/mpeg',
            'filename' => fake()->uuid() . '.mp3',
            'path' => 'media/' . fake()->uuid() . '.mp3',
            'duration' => fake()->randomFloat(2, 60, 3600), // 1min to 1hour
            'width' => null,
            'height' => null,
            'thumbnail_path' => null,
        ]);
    }

    /**
     * Indicate that the media file is an image.
     */
    public function image(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'image',
            'mime_type' => 'image/jpeg',
            'filename' => fake()->uuid() . '.jpg',
            'path' => 'media/' . fake()->uuid() . '.jpg',
            'duration' => null,
            'width' => fake()->randomElement([1920, 1280, 3840]),
            'height' => fake()->randomElement([1080, 720, 2160]),
        ]);
    }

    /**
     * Get file extension for media type.
     *
     * @param  string  $type
     * @return string
     */
    protected function getFileExtension(string $type): string
    {
        return match ($type) {
            'video' => fake()->randomElement(['mp4', 'mov', 'avi']),
            'audio' => fake()->randomElement(['mp3', 'wav', 'aac']),
            'image' => fake()->randomElement(['jpg', 'png', 'gif']),
            default => 'bin',
        };
    }

    /**
     * Get MIME type for media type.
     *
     * @param  string  $type
     * @return string
     */
    protected function getMimeType(string $type): string
    {
        return match ($type) {
            'video' => 'video/mp4',
            'audio' => 'audio/mpeg',
            'image' => 'image/jpeg',
            default => 'application/octet-stream',
        };
    }
}