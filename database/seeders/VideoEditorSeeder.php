<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use App\Models\MediaFile;
use App\Models\TimelineClip;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VideoEditorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a sample user if no users exist
        $user = User::first() ?? User::factory()->create([
            'name' => 'Video Editor',
            'email' => 'editor@example.com',
        ]);

        // Create sample projects
        $project1 = Project::factory()->create([
            'user_id' => $user->id,
            'name' => 'My First Video',
            'description' => 'A sample video editing project',
        ]);

        $project2 = Project::factory()->create([
            'user_id' => $user->id,
            'name' => 'Product Demo',
            'description' => 'Marketing video for new product',
            'width' => 3840,
            'height' => 2160,
        ]);

        // Create sample media files
        $videoFile1 = MediaFile::factory()->video()->create([
            'user_id' => $user->id,
            'name' => 'Intro Video',
            'duration' => 15.5,
        ]);

        $audioFile1 = MediaFile::factory()->audio()->create([
            'user_id' => $user->id,
            'name' => 'Background Music',
            'duration' => 120.0,
        ]);

        $imageFile1 = MediaFile::factory()->image()->create([
            'user_id' => $user->id,
            'name' => 'Logo Image',
        ]);

        $videoFile2 = MediaFile::factory()->video()->create([
            'user_id' => $user->id,
            'name' => 'Main Content',
            'duration' => 45.0,
        ]);

        // Create additional media files
        MediaFile::factory()->count(5)->video()->create(['user_id' => $user->id]);
        MediaFile::factory()->count(3)->audio()->create(['user_id' => $user->id]);
        MediaFile::factory()->count(4)->image()->create(['user_id' => $user->id]);

        // Create sample timeline clips for project1
        TimelineClip::factory()->create([
            'project_id' => $project1->id,
            'media_file_id' => $videoFile1->id,
            'track' => 0,
            'start_time' => 0,
            'duration' => 15.5,
        ]);

        TimelineClip::factory()->create([
            'project_id' => $project1->id,
            'media_file_id' => $videoFile2->id,
            'track' => 0,
            'start_time' => 15.5,
            'duration' => 30.0,
        ]);

        TimelineClip::factory()->create([
            'project_id' => $project1->id,
            'media_file_id' => $audioFile1->id,
            'track' => 1,
            'start_time' => 0,
            'duration' => 45.5,
        ]);

        TimelineClip::factory()->create([
            'project_id' => $project1->id,
            'media_file_id' => $imageFile1->id,
            'track' => 2,
            'start_time' => 20,
            'duration' => 5.0,
        ]);
    }
}