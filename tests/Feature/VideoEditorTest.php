<?php

use App\Models\User;
use App\Models\Project;
use App\Models\MediaFile;
use App\Models\TimelineClip;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

test('video editor page loads for guests', function () {
    $response = $this->get('/');
    
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('welcome')
    );
});

test('video editor loads for authenticated users', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->get('/');
    
    $response->assertStatus(200);
    $response->assertInertia(fn ($page) => $page
        ->component('video-editor')
        ->has('projects')
        ->has('currentProject')
        ->has('mediaFiles')
        ->has('timelineClips')
    );
});

test('user can create a new project', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->post('/projects', [
        'name' => 'Test Project',
        'description' => 'A test project',
        'width' => 1920,
        'height' => 1080,
        'fps' => 30,
    ]);
    
    $response->assertRedirect('/');
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('projects', [
        'user_id' => $user->id,
        'name' => 'Test Project',
        'width' => 1920,
        'height' => 1080,
        'fps' => 30,
    ]);
});

test('user can upload media file', function () {
    $user = User::factory()->create();
    
    Storage::fake('public');
    $file = UploadedFile::fake()->create('test-video.mp4', 1000, 'video/mp4');
    
    $response = $this->actingAs($user)->post('/media-files', [
        'file' => $file,
        'name' => 'Test Video',
    ]);
    
    $response->assertRedirect();
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('media_files', [
        'user_id' => $user->id,
        'name' => 'Test Video',
        'type' => 'video',
        'mime_type' => 'video/mp4',
    ]);
    
    Storage::disk('public')->assertExists('media/' . MediaFile::latest()->first()->filename);
});

test('user can add clip to timeline', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $mediaFile = MediaFile::factory()->video()->create(['user_id' => $user->id]);
    
    $response = $this->actingAs($user)->post('/timeline-clips', [
        'project_id' => $project->id,
        'media_file_id' => $mediaFile->id,
        'track' => 0,
        'start_time' => 10.5,
        'duration' => 15.0,
        'media_start' => 0,
    ]);
    
    $response->assertRedirect();
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('timeline_clips', [
        'project_id' => $project->id,
        'media_file_id' => $mediaFile->id,
        'track' => 0,
        'start_time' => 10.5,
        'duration' => 15.0,
    ]);
});

test('user can delete their own project', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    
    $response = $this->actingAs($user)->delete("/projects/{$project->id}");
    
    $response->assertRedirect('/');
    $response->assertSessionHas('success');
    
    $this->assertDatabaseMissing('projects', ['id' => $project->id]);
});

test('user cannot delete another users project', function () {
    $user1 = User::factory()->create();
    $user2 = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user1->id]);
    
    $response = $this->actingAs($user2)->delete("/projects/{$project->id}");
    
    $response->assertStatus(403);
    $this->assertDatabaseHas('projects', ['id' => $project->id]);
});

test('user can remove clip from timeline', function () {
    $user = User::factory()->create();
    $project = Project::factory()->create(['user_id' => $user->id]);
    $mediaFile = MediaFile::factory()->video()->create(['user_id' => $user->id]);
    $clip = TimelineClip::factory()->create([
        'project_id' => $project->id,
        'media_file_id' => $mediaFile->id,
    ]);
    
    $response = $this->actingAs($user)->delete("/timeline-clips/{$clip->id}");
    
    $response->assertRedirect();
    $response->assertSessionHas('success');
    
    $this->assertDatabaseMissing('timeline_clips', ['id' => $clip->id]);
});

test('project validation requires valid dimensions', function () {
    $user = User::factory()->create();
    
    $response = $this->actingAs($user)->post('/projects', [
        'name' => 'Test Project',
        'width' => 100, // Too small
        'height' => 100, // Too small
        'fps' => 10, // Too low
    ]);
    
    $response->assertSessionHasErrors(['width', 'height', 'fps']);
});

test('media file upload validates file size', function () {
    $user = User::factory()->create();
    
    Storage::fake('public');
    $file = UploadedFile::fake()->create('huge-video.mp4', 2000000, 'video/mp4'); // 2GB
    
    $response = $this->actingAs($user)->post('/media-files', [
        'file' => $file,
        'name' => 'Huge Video',
    ]);
    
    $response->assertSessionHasErrors(['file']);
});