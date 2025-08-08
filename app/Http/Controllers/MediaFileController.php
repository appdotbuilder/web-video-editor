<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMediaFileRequest;
use App\Models\MediaFile;
use App\Services\AuthorizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaFileController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMediaFileRequest $request)
    {
        $file = $request->file('file');
        $name = $request->input('name') ?? $file->getClientOriginalName();
        
        // Generate unique filename
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Store file
        $path = $file->storeAs('media', $filename, 'public');
        
        // Determine media type
        $mimeType = $file->getMimeType();
        $type = $this->getMediaType($mimeType);
        
        // Get file dimensions for images/videos (simplified)
        $width = null;
        $height = null;
        $duration = null;
        
        if ($type === 'image') {
            [$width, $height] = getimagesize($file->getPathname());
        }
        
        $userId = auth()->id();
        if (!$userId) {
            abort(401, 'User not authenticated');
        }

        $mediaFile = MediaFile::create([
            'user_id' => $userId,
            'name' => $name,
            'filename' => $filename,
            'path' => $path,
            'type' => $type,
            'mime_type' => $mimeType,
            'size' => $file->getSize(),
            'duration' => $duration,
            'width' => $width,
            'height' => $height,
        ]);

        return redirect()->back()
            ->with('success', 'Media file uploaded successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MediaFile $mediaFile, AuthorizationService $auth)
    {
        if (!$auth->canDeleteMediaFile(auth()->user(), $mediaFile)) {
            abort(403);
        }
        
        // Delete file from storage
        Storage::disk('public')->delete($mediaFile->path);
        
        $mediaFile->delete();

        return redirect()->back()
            ->with('success', 'Media file deleted successfully.');
    }

    /**
     * Determine media type from MIME type.
     *
     * @param  string  $mimeType
     * @return string
     */
    protected function getMediaType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        
        if (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }
        
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        
        return 'unknown';
    }
}