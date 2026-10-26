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
        Schema::create('safety_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_number')->unique()->nullable();
            $table->string('reporter_name');
            $table->string('employee_number', 50);
            $table->string('section');
            $table->string('location');
            $table->text('hazard_description');
            $table->boolean('reported_to_supervisor');
            $table->boolean('ky_created');
            $table->string('initial_image_path');
            $table->string('risk_level')->nullable();
            $table->string('status')->default('awaiting_pic')->index();
            $table->string('return_to_stage')->nullable();
            $table->text('pic_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('verification_due_at')->nullable();
            $table->string('ir_decision')->nullable();
            $table->text('ir_note')->nullable();
            $table->foreignId('ir_reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('ir_reviewed_at')->nullable();
            $table->string('she_decision')->nullable();
            $table->text('she_note')->nullable();
            $table->foreignId('she_reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('she_reviewed_at')->nullable();
            $table->timestamp('plan_sla_due_at')->nullable();
            $table->text('plan_description')->nullable();
            $table->string('plan_owner')->nullable();
            $table->date('plan_due_date')->nullable();
            $table->timestamp('plan_created_at')->nullable();
            $table->timestamp('repair_sla_due_at')->nullable();
            $table->string('repair_status')->nullable();
            $table->string('repair_proof_path')->nullable();
            $table->date('repair_completed_on')->nullable();
            $table->timestamp('closure_sla_due_at')->nullable();
            $table->foreignId('closed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index(['risk_level', 'created_at']);
            $table->index(['section', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('safety_reports');
    }
};
