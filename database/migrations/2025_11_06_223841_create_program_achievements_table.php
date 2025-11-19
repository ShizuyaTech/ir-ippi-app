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
        Schema::create('program_achievements', function (Blueprint $table) {
            $table->id();
           $table->string('title');
            $table->text('description');
            $table->string('image')->nullable();
            $table->enum('type', ['program', 'achievement']);
            $table->string('category')->nullable();
            $table->year('year')->default(2024);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            // Indexes for performance
            $table->index(['type', 'is_active', 'published_at']);
            $table->index(['year', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_achievements');
    }
};
