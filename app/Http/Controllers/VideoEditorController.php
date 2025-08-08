<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\MediaFile;
use App\Models\TimelineClip;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VideoEditorController extends Controller
{
    /**
     * Display the video editor interface.
     */
    public function index()
    {
        $user = auth()->user();
        
        if (!$user) {
            return Inertia::render('welcome');
        }

        // Get user's active projects
        $projects = Project::where('user_id', $user->id)
            ->active()
            ->latest()
            ->take(5)
            ->get();

        // Get user's recent media files
        $mediaFiles = MediaFile::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        // Get current project (don't create one automatically)
        $currentProject = $projects->first();

        // Get timeline clips for current project
        $timelineClips = $currentProject 
            ? TimelineClip::where('project_id', $currentProject->id)
                ->with('mediaFile')
                ->orderBy('track')
                ->orderBy('start_time')
                ->get()
            : collect();

        return Inertia::render('video-editor', [
            'projects' => $projects,
            'currentProject' => $currentProject,
            'mediaFiles' => $mediaFiles,
            'timelineClips' => $timelineClips,
        ]);
    }
}