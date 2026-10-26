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
        Schema::table('safety_reports', function (Blueprint $table): void {
            $table->json('pic_checklist')->nullable()->after('risk_level');
            $table->string('actual_condition_image_path')->nullable()->after('pic_checklist');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('safety_reports', function (Blueprint $table): void {
            $table->dropColumn(['pic_checklist', 'actual_condition_image_path']);
        });
    }
};
