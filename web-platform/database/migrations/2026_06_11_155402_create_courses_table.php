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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('dataset_index')->nullable()->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('skills')->nullable();
            $table->string('level')->nullable();
            $table->text('url')->nullable();
            $table->string('platform')->nullable();
            $table->text('combined_features')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
