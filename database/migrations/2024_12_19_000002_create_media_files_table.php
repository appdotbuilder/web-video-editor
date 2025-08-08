<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('filename');
            $table->string('path');
            $table->enum('type', ['video', 'audio', 'image'])->comment('Media file type');
            $table->string('mime_type');
            $table->bigInteger('size')->comment('File size in bytes');
            $table->decimal('duration', 10, 3)->nullable()->comment('Duration in seconds for video/audio');
            $table->integer('width')->nullable()->comment('Width for video/image');
            $table->integer('height')->nullable()->comment('Height for video/image');
            $table->string('thumbnail_path')->nullable();
            $table->json('metadata')->nullable()->comment('Additional file metadata');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('type');
            $table->index(['user_id', 'type']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};