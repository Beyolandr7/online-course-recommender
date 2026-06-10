<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_paths', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('major', 100);
            $table->string('initial_level', 60);
            $table->string('target_level', 60);
            $table->string('interest', 180);
            $table->string('status', 30)->default('in_progress');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->json('recommended_courses')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_paths');
    }
};
