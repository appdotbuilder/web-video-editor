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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('width')->default(1920)->comment('Video width in pixels');
            $table->integer('height')->default(1080)->comment('Video height in pixels');
            $table->decimal('fps', 5, 2)->default(30.00)->comment('Frames per second');
            $table->json('settings')->nullable()->comment('Additional project settings');
            $table->enum('status', ['active', 'archived'])->default('active');
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('status');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};