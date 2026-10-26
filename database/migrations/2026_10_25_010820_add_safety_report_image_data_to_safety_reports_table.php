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
        Schema::table('safety_reports', function (Blueprint $table) {
            $table->mediumText('initial_image_data')->charset('binary')->nullable();
            $table->mediumText('actual_condition_image_data')->charset('binary')->nullable();
            $table->mediumText('repair_proof_data')->charset('binary')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safety_reports', function (Blueprint $table) {
            $table->dropColumn([
                'initial_image_data',
                'actual_condition_image_data',
                'repair_proof_data',
            ]);
        });
    }
};
