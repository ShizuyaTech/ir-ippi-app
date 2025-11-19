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
        Schema::create('response_assessments', function (Blueprint $table) {
            $table->id();
            $table->string('user_code'); // User ID dari assessment_users
            $table->foreignId('user_id')->nullable(); // Opsional, untuk relasi ke users table
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->enum('rating', ['sangat_buruk', 'buruk', 'cukup', 'baik', 'sangat_baik']);
            $table->timestamps();

            // Prevent duplicate responses from same user for same question
            $table->unique(['user_id', 'assessment_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_assessments');
    }
};
