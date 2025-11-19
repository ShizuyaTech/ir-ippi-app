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
        Schema::create('dashboard_scores', function (Blueprint $table) {
            $table->id();
            $table->decimal('ir_partnership', 5, 2)->default(0);
            $table->decimal('conductive_working_climate', 5, 2)->default(0);
            $table->decimal('ess', 5, 2)->default(0);
            $table->decimal('airsi', 5, 2)->default(0);
            $table->foreignId('updated_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_scores');
    }
};
