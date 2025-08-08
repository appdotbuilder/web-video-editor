import React from 'react';
import { Head, Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { AppShell } from '@/components/app-shell';

export default function Welcome() {
    return (
        <>
            <Head title="Welcome to Video Editor Pro" />
            <AppShell>
                <div className="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
                    <div className="absolute inset-0 opacity-20" style={{ backgroundImage: `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")` }}></div>
                    
                    <div className="relative z-10 flex flex-col items-center justify-center min-h-screen px-4 py-16 text-center">
                        {/* Hero Section */}
                        <div className="max-w-4xl mx-auto mb-16">
                            <div className="mb-8">
                                <span className="inline-flex items-center px-4 py-2 text-sm font-medium text-purple-300 bg-purple-900/50 border border-purple-500/20 rounded-full backdrop-blur-sm">
                                    🎬 Professional Video Editing Platform
                                </span>
                            </div>
                            
                            <h1 className="text-5xl md:text-7xl font-bold bg-gradient-to-r from-white via-purple-200 to-pink-200 bg-clip-text text-transparent mb-8">
                                Video Editor Pro
                            </h1>
                            
                            <p className="text-xl md:text-2xl text-gray-300 mb-12 leading-relaxed">
                                Create stunning videos with our powerful web-based editor.<br />
                                Upload, edit, and export professional-quality content right from your browser.
                            </p>
                        </div>

                        {/* Feature Preview */}
                        <div className="w-full max-w-6xl mx-auto mb-16">
                            <div className="bg-black/40 backdrop-blur-xl border border-white/10 rounded-2xl p-8 shadow-2xl">
                                <h2 className="text-2xl font-bold text-white mb-8">✨ Powerful Features</h2>
                                
                                <div className="grid md:grid-cols-3 gap-8 mb-8">
                                    <div className="text-center">
                                        <div className="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                                            <span className="text-2xl">📁</span>
                                        </div>
                                        <h3 className="text-lg font-semibold text-white mb-2">Media Library</h3>
                                        <p className="text-gray-400">Upload and organize videos, audio, and images in your personal media library</p>
                                    </div>
                                    
                                    <div className="text-center">
                                        <div className="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                                            <span className="text-2xl">🎞️</span>
                                        </div>
                                        <h3 className="text-lg font-semibold text-white mb-2">Multi-Track Timeline</h3>
                                        <p className="text-gray-400">Drag & drop clips onto multiple timeline tracks for complex editing</p>
                                    </div>
                                    
                                    <div className="text-center">
                                        <div className="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                                            <span className="text-2xl">▶️</span>
                                        </div>
                                        <h3 className="text-lg font-semibold text-white mb-2">Real-time Preview</h3>
                                        <p className="text-gray-400">See your edits instantly with our responsive preview panel</p>
                                    </div>
                                </div>

                                <div className="grid md:grid-cols-3 gap-8">
                                    <div className="text-center">
                                        <div className="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                                            <span className="text-2xl">✂️</span>
                                        </div>
                                        <h3 className="text-lg font-semibold text-white mb-2">Precision Tools</h3>
                                        <p className="text-gray-400">Cut, trim, split and move clips with frame-perfect accuracy</p>
                                    </div>
                                    
                                    <div className="text-center">
                                        <div className="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center">
                                            <span className="text-2xl">🎛️</span>
                                        </div>
                                        <h3 className="text-lg font-semibold text-white mb-2">Properties Panel</h3>
                                        <p className="text-gray-400">Fine-tune project settings and clip properties with ease</p>
                                    </div>
                                    
                                    <div className="text-center">
                                        <div className="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-pink-500 to-rose-500 rounded-xl flex items-center justify-center">
                                            <span className="text-2xl">💾</span>
                                        </div>
                                        <h3 className="text-lg font-semibold text-white mb-2">Export & Render</h3>
                                        <p className="text-gray-400">Export your finished videos in multiple formats and resolutions</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Screenshot Mockup */}
                        <div className="w-full max-w-6xl mx-auto mb-16">
                            <div className="bg-gradient-to-r from-purple-500/20 to-pink-500/20 border border-white/10 rounded-2xl p-8">
                                <div className="bg-gray-900 rounded-xl border border-gray-700 overflow-hidden">
                                    <div className="bg-gray-800 px-4 py-2 border-b border-gray-700 flex items-center space-x-2">
                                        <div className="flex space-x-1">
                                            <div className="w-3 h-3 bg-red-500 rounded-full"></div>
                                            <div className="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                            <div className="w-3 h-3 bg-green-500 rounded-full"></div>
                                        </div>
                                        <div className="flex-1 text-center text-sm text-gray-400">Video Editor Pro</div>
                                    </div>
                                    
                                    <div className="p-6 space-y-4">
                                        {/* Media Library */}
                                        <div className="grid grid-cols-6 gap-2">
                                            {[...Array(6)].map((_, i) => (
                                                <div key={i} className="aspect-video bg-gradient-to-br from-purple-600 to-pink-600 rounded opacity-60"></div>
                                            ))}
                                        </div>
                                        
                                        {/* Preview Panel */}
                                        <div className="aspect-video bg-black rounded border-2 border-dashed border-gray-600 flex items-center justify-center">
                                            <span className="text-gray-400 text-lg">🎥 Preview Panel</span>
                                        </div>
                                        
                                        {/* Timeline */}
                                        <div className="space-y-2">
                                            <div className="h-8 bg-gradient-to-r from-blue-600 to-purple-600 rounded opacity-70"></div>
                                            <div className="h-8 bg-gradient-to-r from-green-600 to-blue-600 rounded opacity-70"></div>
                                            <div className="h-8 bg-gradient-to-r from-orange-600 to-red-600 rounded opacity-70"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* Call to Action */}
                        <div className="flex flex-col sm:flex-row gap-4 justify-center">
                            <Link href="/login">
                                <Button size="lg" className="px-8 py-4 text-lg bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white border-0 shadow-lg shadow-purple-500/25">
                                    🚀 Start Editing Now
                                </Button>
                            </Link>
                            <Link href="/register">
                                <Button size="lg" variant="outline" className="px-8 py-4 text-lg border-white/20 text-white hover:bg-white/10">
                                    ✨ Create Free Account
                                </Button>
                            </Link>
                        </div>
                        
                        <p className="text-gray-400 text-sm mt-6">
                            No downloads required • Works in any modern browser • Professional results
                        </p>
                    </div>
                </div>
            </AppShell>
        </>
    );
}