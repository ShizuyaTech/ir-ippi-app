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
        Schema::create('assessment_users', function (Blueprint $table) {
            $table->id();
            $table->string('user_code')->unique(); // User ID yang digenerate admin
            $table->foreignId('assessment_id')->constrained()->onDelete('cascade');
            $table->boolean('has_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_users');
    }
};
