<?php

namespace App\Services;

use App\Models\Project;
use App\Models\MediaFile;
use App\Models\TimelineClip;
use App\Models\User;

class AuthorizationService
{
    /**
     * Check if user can update project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return bool
     */
    public function canUpdateProject(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Check if user can delete project.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return bool
     */
    public function canDeleteProject(User $user, Project $project): bool
    {
        return $user->id === $project->user_id;
    }

    /**
     * Check if user can delete media file.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MediaFile  $mediaFile
     * @return bool
     */
    public function canDeleteMediaFile(User $user, MediaFile $mediaFile): bool
    {
        return $user->id === $mediaFile->user_id;
    }

    /**
     * Check if user can update timeline clip.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TimelineClip  $clip
     * @return bool
     */
    public function canUpdateTimelineClip(User $user, TimelineClip $clip): bool
    {
        return $user->id === $clip->project->user_id;
    }

    /**
     * Check if user can delete timeline clip.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TimelineClip  $clip
     * @return bool
     */
    public function canDeleteTimelineClip(User $user, TimelineClip $clip): bool
    {
        return $user->id === $clip->project->user_id;
    }
}