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
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul feedback/masukan
            $table->text('content'); // Isi feedback untuk manajemen
            $table->enum('category', ['kebijakan', 'kondisi_kerja', 'fasilitas', 'komunikasi', 'lainnya']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->enum('status', ['draft', 'submitted', 'under_review', 'responded', 'closed']);
            $table->text('management_response')->nullable(); // Tanggapan dari manajemen
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->foreignId('created_by')->constrained('users'); // Pengurus serikat yang membuat
            $table->foreignId('responded_by')->nullable()->constrained('users'); // Admin/manajemen yang menanggapi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedback');
    }
};
