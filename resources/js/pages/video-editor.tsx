import React, { useState } from 'react';
import { Head, router, useForm } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { AppShell } from '@/components/app-shell';

interface MediaFile {
    id: number;
    name: string;
    type: 'video' | 'audio' | 'image';
    path: string;
    duration?: number;
    width?: number;
    height?: number;
    size: number;
    created_at: string;
    [key: string]: unknown;
}

interface Project {
    id: number;
    name: string;
    description?: string;
    width: number;
    height: number;
    fps: number;
    status: string;
    created_at: string;
    [key: string]: unknown;
}

interface TimelineClip {
    id: number;
    project_id: number;
    media_file_id: number;
    track: number;
    start_time: number;
    duration: number;
    media_start: number;
    media_file: MediaFile;
    [key: string]: unknown;
}

interface Props {
    projects: Project[];
    currentProject?: Project;
    mediaFiles: MediaFile[];
    timelineClips: TimelineClip[];
    [key: string]: unknown;
}

export default function VideoEditor({ currentProject, mediaFiles, timelineClips }: Props) {
    const [selectedClip, setSelectedClip] = useState<TimelineClip | null>(null);
    const [isPlaying, setIsPlaying] = useState(false);
    const [currentTime] = useState(0);
    const [showUploadModal, setShowUploadModal] = useState(false);
    const [showProjectModal, setShowProjectModal] = useState(false);

    const { data: uploadData, setData: setUploadData, processing: uploadProcessing } = useForm({
        file: null as File | null,
        name: '',
    });

    const { data: projectData, setData: setProjectData, post: projectPost, processing: projectProcessing } = useForm({
        name: 'New Project',
        description: '',
        width: 1920,
        height: 1080,
        fps: 30,
    });

    const handleFileUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (file) {
            setUploadData({
                file: file,
                name: file.name.split('.')[0],
            });
        }
    };

    const submitUpload = () => {
        if (!uploadData.file) return;
        
        const formData = new FormData();
        formData.append('file', uploadData.file);
        formData.append('name', uploadData.name);

        router.post('/media-files', formData, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                setShowUploadModal(false);
                setUploadData({ file: null, name: '' });
            }
        });
    };

    const submitProject = () => {
        projectPost('/projects', {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                setShowProjectModal(false);
                setProjectData({
                    name: 'New Project',
                    description: '',
                    width: 1920,
                    height: 1080,
                    fps: 30,
                });
            }
        });
    };

    const addToTimeline = (mediaFile: MediaFile, track: number = 0) => {
        if (!currentProject) return;
        
        router.post('/timeline-clips', {
            project_id: currentProject.id,
            media_file_id: mediaFile.id,
            track,
            start_time: 0,
            duration: mediaFile.duration || 5,
            media_start: 0,
        }, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const removeFromTimeline = (clipId: number) => {
        router.delete(`/timeline-clips/${clipId}`, {
            preserveState: true,
            preserveScroll: true,
        });
    };

    const formatTime = (seconds: number) => {
        const mins = Math.floor(seconds / 60);
        const secs = Math.floor(seconds % 60);
        return `${mins}:${secs.toString().padStart(2, '0')}`;
    };

    const formatFileSize = (bytes: number) => {
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        if (bytes === 0) return '0 Bytes';
        const i = Math.floor(Math.log(bytes) / Math.log(1024));
        return Math.round(bytes / Math.pow(1024, i) * 100) / 100 + ' ' + sizes[i];
    };

    const getMediaIcon = (type: string) => {
        switch (type) {
            case 'video': return '🎥';
            case 'audio': return '🎵';
            case 'image': return '🖼️';
            default: return '📁';
        }
    };

    // If no current project, show create project prompt
    if (!currentProject) {
        return (
            <>
                <Head title="Video Editor Pro - Create Project" />
                <AppShell>
                    <div className="min-h-screen bg-gray-900 text-white flex items-center justify-center">
                        <div className="text-center">
                            <div className="text-6xl mb-8">🎬</div>
                            <h1 className="text-3xl font-bold mb-4">Welcome to Video Editor Pro</h1>
                            <p className="text-gray-400 mb-8">Create your first project to get started</p>
                            <Button onClick={() => setShowProjectModal(true)} size="lg">
                                ✨ Create New Project
                            </Button>
                        </div>
                        
                        {/* Project Modal */}
                        {showProjectModal && (
                            <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                                <div className="bg-gray-800 rounded-lg p-6 w-96">
                                    <h2 className="text-lg font-semibold mb-4">✨ Create New Project</h2>
                                    <div className="space-y-4">
                                        <div>
                                            <label className="block text-sm font-medium mb-2">Project Name</label>
                                            <input
                                                type="text"
                                                value={projectData.name}
                                                onChange={(e) => setProjectData('name', e.target.value)}
                                                className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium mb-2">Description</label>
                                            <textarea
                                                value={projectData.description}
                                                onChange={(e) => setProjectData('description', e.target.value)}
                                                className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                                rows={3}
                                            />
                                        </div>
                                        <div className="grid grid-cols-2 gap-3">
                                            <div>
                                                <label className="block text-sm font-medium mb-2">Width</label>
                                                <input
                                                    type="number"
                                                    value={projectData.width}
                                                    onChange={(e) => setProjectData('width', parseInt(e.target.value))}
                                                    className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                                />
                                            </div>
                                            <div>
                                                <label className="block text-sm font-medium mb-2">Height</label>
                                                <input
                                                    type="number"
                                                    value={projectData.height}
                                                    onChange={(e) => setProjectData('height', parseInt(e.target.value))}
                                                    className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                                />
                                            </div>
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium mb-2">Frame Rate (FPS)</label>
                                            <select
                                                value={projectData.fps}
                                                onChange={(e) => setProjectData('fps', parseFloat(e.target.value))}
                                                className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                            >
                                                <option value={24}>24 fps</option>
                                                <option value={30}>30 fps</option>
                                                <option value={60}>60 fps</option>
                                            </select>
                                        </div>
                                        <div className="flex space-x-3">
                                            <Button
                                                onClick={submitProject}
                                                disabled={projectProcessing}
                                                className="flex-1"
                                            >
                                                {projectProcessing ? 'Creating...' : '✨ Create Project'}
                                            </Button>
                                            <Button
                                                variant="outline"
                                                onClick={() => setShowProjectModal(false)}
                                                className="flex-1"
                                            >
                                                Cancel
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )}
                    </div>
                </AppShell>
            </>
        );
    }

    return (
        <>
            <Head title="Video Editor Pro" />
            <AppShell>
                <div className="min-h-screen bg-gray-900 text-white">
                    {/* Header */}
                    <div className="bg-gray-800 border-b border-gray-700 px-6 py-4">
                        <div className="flex items-center justify-between">
                            <div className="flex items-center space-x-4">
                                <h1 className="text-2xl font-bold">🎬 Video Editor Pro</h1>
                                <span className="text-gray-400">|</span>
                                <div className="flex items-center space-x-2">
                                    <span className="text-lg font-medium">{currentProject.name}</span>
                                    <span className="text-sm text-gray-400">
                                        {currentProject.width}×{currentProject.height} @ {currentProject.fps}fps
                                    </span>
                                </div>
                            </div>
                            <div className="flex items-center space-x-3">
                                <Button onClick={() => setShowProjectModal(true)} variant="outline" size="sm">
                                    ➕ New Project
                                </Button>
                                <Button onClick={() => setShowUploadModal(true)} size="sm">
                                    📁 Upload Media
                                </Button>
                                <Button variant="outline" size="sm">
                                    💾 Export
                                </Button>
                            </div>
                        </div>
                    </div>

                    <div className="flex h-[calc(100vh-80px)]">
                        {/* Left Panel - Media Library */}
                        <div className="w-80 bg-gray-800 border-r border-gray-700 flex flex-col">
                            <div className="p-4 border-b border-gray-700">
                                <h2 className="text-lg font-semibold mb-3">📚 Media Library</h2>
                                <div className="flex space-x-2 mb-3">
                                    <Button size="sm" variant="outline" className="flex-1 text-xs">
                                        All
                                    </Button>
                                    <Button size="sm" variant="ghost" className="flex-1 text-xs">
                                        🎥 Videos
                                    </Button>
                                    <Button size="sm" variant="ghost" className="flex-1 text-xs">
                                        🎵 Audio
                                    </Button>
                                    <Button size="sm" variant="ghost" className="flex-1 text-xs">
                                        🖼️ Images
                                    </Button>
                                </div>
                            </div>
                            
                            <div className="flex-1 overflow-y-auto p-4 space-y-2">
                                {mediaFiles.map((file) => (
                                    <div
                                        key={file.id}
                                        className="bg-gray-700 rounded-lg p-3 hover:bg-gray-600 cursor-pointer transition-colors group"
                                        draggable
                                        onDragStart={(e) => {
                                            e.dataTransfer.setData('application/json', JSON.stringify(file));
                                        }}
                                    >
                                        <div className="flex items-center justify-between mb-2">
                                            <div className="flex items-center space-x-2">
                                                <span className="text-lg">{getMediaIcon(file.type)}</span>
                                                <span className="text-sm font-medium truncate">{file.name}</span>
                                            </div>
                                            <Button
                                                size="sm"
                                                variant="ghost"
                                                className="opacity-0 group-hover:opacity-100 text-xs px-2 py-1"
                                                onClick={() => addToTimeline(file)}
                                            >
                                                ➕
                                            </Button>
                                        </div>
                                        <div className="text-xs text-gray-400 space-y-1">
                                            <div>Size: {formatFileSize(file.size)}</div>
                                            {file.duration && <div>Duration: {formatTime(file.duration)}</div>}
                                            {file.width && file.height && (
                                                <div>Resolution: {file.width}×{file.height}</div>
                                            )}
                                        </div>
                                    </div>
                                ))}
                                
                                {mediaFiles.length === 0 && (
                                    <div className="text-center py-12 text-gray-400">
                                        <div className="text-4xl mb-4">📁</div>
                                        <p>No media files yet</p>
                                        <Button
                                            size="sm"
                                            onClick={() => setShowUploadModal(true)}
                                            className="mt-3"
                                        >
                                            Upload First File
                                        </Button>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Main Content */}
                        <div className="flex-1 flex flex-col">
                            {/* Preview Panel */}
                            <div className="h-2/3 bg-black border-b border-gray-700 flex items-center justify-center">
                                <div className="text-center">
                                    <div className="w-96 h-54 bg-gray-800 rounded-lg border-2 border-dashed border-gray-600 flex items-center justify-center mb-4">
                                        <div>
                                            <div className="text-6xl mb-4">🎥</div>
                                            <p className="text-gray-400">Video Preview</p>
                                            <p className="text-sm text-gray-500">
                                                {currentProject.width}×{currentProject.height}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    {/* Playback Controls */}
                                    <div className="flex items-center justify-center space-x-4">
                                        <Button size="sm" variant="outline">⏮️</Button>
                                        <Button 
                                            size="sm"
                                            onClick={() => setIsPlaying(!isPlaying)}
                                            className="w-12 h-12 rounded-full"
                                        >
                                            {isPlaying ? '⏸️' : '▶️'}
                                        </Button>
                                        <Button size="sm" variant="outline">⏭️</Button>
                                        <span className="text-sm text-gray-400 ml-4">
                                            {formatTime(currentTime)} / {formatTime(120)}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {/* Timeline */}
                            <div className="h-1/3 bg-gray-800 p-4">
                                <div className="flex items-center justify-between mb-3">
                                    <h3 className="text-lg font-semibold">🎞️ Timeline</h3>
                                    <div className="flex space-x-2">
                                        <Button size="sm" variant="outline">🔍➖</Button>
                                        <Button size="sm" variant="outline">🔍➕</Button>
                                        <Button size="sm" variant="outline">✂️ Split</Button>
                                    </div>
                                </div>
                                
                                <div className="space-y-2">
                                    {[0, 1, 2].map((track) => (
                                        <div
                                            key={track}
                                            className="h-12 bg-gray-700 rounded border border-gray-600 relative flex items-center px-2"
                                            onDrop={(e) => {
                                                e.preventDefault();
                                                try {
                                                    const fileData = JSON.parse(e.dataTransfer.getData('application/json'));
                                                    addToTimeline(fileData, track);
                                                } catch (error) {
                                                    console.error('Invalid drop data:', error);
                                                }
                                            }}
                                            onDragOver={(e) => e.preventDefault()}
                                        >
                                            <span className="text-sm text-gray-400 mr-3">Track {track + 1}</span>
                                            
                                            {timelineClips
                                                .filter(clip => clip.track === track)
                                                .map((clip) => (
                                                <div
                                                    key={clip.id}
                                                    className={`absolute h-8 bg-gradient-to-r rounded cursor-pointer flex items-center px-2 text-xs font-medium group ${
                                                        clip.media_file.type === 'video' 
                                                            ? 'from-blue-600 to-purple-600' 
                                                            : clip.media_file.type === 'audio'
                                                            ? 'from-green-600 to-blue-600'
                                                            : 'from-orange-600 to-red-600'
                                                    } ${selectedClip?.id === clip.id ? 'ring-2 ring-white' : ''}`}
                                                    style={{
                                                        left: `${(clip.start_time / 120) * 100}%`,
                                                        width: `${(clip.duration / 120) * 100}%`,
                                                        minWidth: '60px'
                                                    }}
                                                    onClick={() => setSelectedClip(clip)}
                                                >
                                                    <span className="truncate">
                                                        {getMediaIcon(clip.media_file.type)} {clip.media_file.name}
                                                    </span>
                                                    <Button
                                                        size="sm"
                                                        variant="ghost"
                                                        className="opacity-0 group-hover:opacity-100 ml-auto w-4 h-4 p-0 text-xs"
                                                        onClick={(e) => {
                                                            e.stopPropagation();
                                                            removeFromTimeline(clip.id);
                                                        }}
                                                    >
                                                        ✕
                                                    </Button>
                                                </div>
                                            ))}
                                        </div>
                                    ))}
                                </div>
                                
                                {timelineClips.length === 0 && (
                                    <div className="text-center py-8 text-gray-400">
                                        <p>Drag media files from the library to start editing</p>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Right Panel - Properties */}
                        <div className="w-80 bg-gray-800 border-l border-gray-700 p-4">
                            <h2 className="text-lg font-semibold mb-4">🎛️ Properties</h2>
                            
                            {selectedClip ? (
                                <div className="space-y-4">
                                    <div>
                                        <h3 className="font-medium mb-2">Selected Clip</h3>
                                        <div className="bg-gray-700 rounded p-3">
                                            <div className="text-sm space-y-1">
                                                <div><span className="text-gray-400">Name:</span> {selectedClip.media_file.name}</div>
                                                <div><span className="text-gray-400">Type:</span> {selectedClip.media_file.type}</div>
                                                <div><span className="text-gray-400">Track:</span> {selectedClip.track + 1}</div>
                                                <div><span className="text-gray-400">Start:</span> {formatTime(selectedClip.start_time)}</div>
                                                <div><span className="text-gray-400">Duration:</span> {formatTime(selectedClip.duration)}</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h3 className="font-medium mb-2">Transform</h3>
                                        <div className="space-y-2 text-sm">
                                            <div>
                                                <label className="block text-gray-400 mb-1">Opacity</label>
                                                <input type="range" className="w-full" min="0" max="100" defaultValue="100" />
                                            </div>
                                            <div>
                                                <label className="block text-gray-400 mb-1">Volume</label>
                                                <input type="range" className="w-full" min="0" max="100" defaultValue="100" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ) : (
                                <div className="space-y-4">
                                    <div>
                                        <h3 className="font-medium mb-2">Project Settings</h3>
                                        <div className="bg-gray-700 rounded p-3 text-sm space-y-1">
                                            <div><span className="text-gray-400">Name:</span> {currentProject.name}</div>
                                            <div><span className="text-gray-400">Resolution:</span> {currentProject.width}×{currentProject.height}</div>
                                            <div><span className="text-gray-400">Frame Rate:</span> {currentProject.fps} fps</div>
                                            <div><span className="text-gray-400">Status:</span> {currentProject.status}</div>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h3 className="font-medium mb-2">Export Settings</h3>
                                        <div className="space-y-2 text-sm">
                                            <div>
                                                <label className="block text-gray-400 mb-1">Format</label>
                                                <select className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2">
                                                    <option>MP4</option>
                                                    <option>MOV</option>
                                                    <option>AVI</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label className="block text-gray-400 mb-1">Quality</label>
                                                <select className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2">
                                                    <option>High</option>
                                                    <option>Medium</option>
                                                    <option>Low</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Upload Modal */}
                {showUploadModal && (
                    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div className="bg-gray-800 rounded-lg p-6 w-96">
                            <h2 className="text-lg font-semibold mb-4">📁 Upload Media File</h2>
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium mb-2">Select File</label>
                                    <input
                                        type="file"
                                        accept="video/*,audio/*,image/*"
                                        onChange={handleFileUpload}
                                        className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-2">File Name</label>
                                    <input
                                        type="text"
                                        value={uploadData.name}
                                        onChange={(e) => setUploadData('name', e.target.value)}
                                        className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                        placeholder="Enter file name"
                                    />
                                </div>
                                <div className="flex space-x-3">
                                    <Button
                                        onClick={submitUpload}
                                        disabled={!uploadData.file || uploadProcessing}
                                        className="flex-1"
                                    >
                                        {uploadProcessing ? 'Uploading...' : '📤 Upload'}
                                    </Button>
                                    <Button
                                        variant="outline"
                                        onClick={() => setShowUploadModal(false)}
                                        className="flex-1"
                                    >
                                        Cancel
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}

                {/* Project Modal */}
                {showProjectModal && (
                    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                        <div className="bg-gray-800 rounded-lg p-6 w-96">
                            <h2 className="text-lg font-semibold mb-4">➕ Create New Project</h2>
                            <div className="space-y-4">
                                <div>
                                    <label className="block text-sm font-medium mb-2">Project Name</label>
                                    <input
                                        type="text"
                                        value={projectData.name}
                                        onChange={(e) => setProjectData('name', e.target.value)}
                                        className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                    />
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-2">Description</label>
                                    <textarea
                                        value={projectData.description}
                                        onChange={(e) => setProjectData('description', e.target.value)}
                                        className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                        rows={3}
                                    />
                                </div>
                                <div className="grid grid-cols-2 gap-3">
                                    <div>
                                        <label className="block text-sm font-medium mb-2">Width</label>
                                        <input
                                            type="number"
                                            value={projectData.width}
                                            onChange={(e) => setProjectData('width', parseInt(e.target.value))}
                                            className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                        />
                                    </div>
                                    <div>
                                        <label className="block text-sm font-medium mb-2">Height</label>
                                        <input
                                            type="number"
                                            value={projectData.height}
                                            onChange={(e) => setProjectData('height', parseInt(e.target.value))}
                                            className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                        />
                                    </div>
                                </div>
                                <div>
                                    <label className="block text-sm font-medium mb-2">Frame Rate (FPS)</label>
                                    <select
                                        value={projectData.fps}
                                        onChange={(e) => setProjectData('fps', parseFloat(e.target.value))}
                                        className="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2"
                                    >
                                        <option value={24}>24 fps</option>
                                        <option value={30}>30 fps</option>
                                        <option value={60}>60 fps</option>
                                    </select>
                                </div>
                                <div className="flex space-x-3">
                                    <Button
                                        onClick={submitProject}
                                        disabled={projectProcessing}
                                        className="flex-1"
                                    >
                                        {projectProcessing ? 'Creating...' : '✨ Create Project'}
                                    </Button>
                                    <Button
                                        variant="outline"
                                        onClick={() => setShowProjectModal(false)}
                                        className="flex-1"
                                    >
                                        Cancel
                                    </Button>
                                </div>
                            </div>
                        </div>
                    </div>
                )}
            </AppShell>
        </>
    );
}