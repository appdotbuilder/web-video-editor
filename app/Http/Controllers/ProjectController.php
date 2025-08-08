<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Services\AuthorizationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProjectRequest $request)
    {
        $userId = auth()->id();
        if (!$userId) {
            abort(401, 'User not authenticated');
        }

        $data = $request->validated();
        $data['user_id'] = $userId;
        
        $project = Project::create($data);

        return redirect()->route('home')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, Project $project, AuthorizationService $auth)
    {
        if (!$auth->canUpdateProject(auth()->user(), $project)) {
            abort(403);
        }
        
        $project->update($request->validated());

        return redirect()->back()
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project, AuthorizationService $auth)
    {
        if (!$auth->canDeleteProject(auth()->user(), $project)) {
            abort(403);
        }
        
        $project->delete();

        return redirect()->route('home')
            ->with('success', 'Project deleted successfully.');
    }
}