<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTimelineClipRequest;
use App\Http\Requests\UpdateTimelineClipRequest;
use App\Models\TimelineClip;
use App\Services\AuthorizationService;
use Illuminate\Http\Request;

class TimelineClipController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTimelineClipRequest $request)
    {
        $clip = TimelineClip::create($request->validated());

        return redirect()->back()
            ->with('success', 'Clip added to timeline successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTimelineClipRequest $request, TimelineClip $timelineClip, AuthorizationService $auth)
    {
        if (!$auth->canUpdateTimelineClip(auth()->user(), $timelineClip)) {
            abort(403);
        }
        
        $timelineClip->update($request->validated());

        return redirect()->back()
            ->with('success', 'Clip updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TimelineClip $timelineClip, AuthorizationService $auth)
    {
        if (!$auth->canDeleteTimelineClip(auth()->user(), $timelineClip)) {
            abort(403);
        }
        
        $timelineClip->delete();

        return redirect()->back()
            ->with('success', 'Clip removed from timeline.');
    }
}