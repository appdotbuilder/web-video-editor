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
        Schema::create('timeline_clips', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_file_id')->constrained()->cascadeOnDelete();
            $table->integer('track')->default(0)->comment('Timeline track number');
            $table->decimal('start_time', 10, 3)->comment('Start time on timeline in seconds');
            $table->decimal('duration', 10, 3)->comment('Clip duration in seconds');
            $table->decimal('media_start', 10, 3)->default(0)->comment('Start position in source media');
            $table->decimal('media_end', 10, 3)->nullable()->comment('End position in source media');
            $table->json('properties')->nullable()->comment('Clip properties and effects');
            $table->integer('z_index')->default(0)->comment('Layer order on same track');
            $table->timestamps();
            
            $table->index('project_id');
            $table->index('media_file_id');
            $table->index(['project_id', 'track']);
            $table->index(['project_id', 'start_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timeline_clips');
    }
};