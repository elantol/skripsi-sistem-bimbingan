<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supervision_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->foreignId('academic_period_id')->constrained('academic_periods')->onDelete('cascade');
            $table->enum('supervisor_type', ['pembimbing_1', 'pembimbing_2']);
            $table->date('assigned_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['student_id', 'academic_period_id', 'supervisor_type'], 'idx_sup_assign_std_period_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supervision_assignments');
    }
};
