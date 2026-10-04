<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('supervision_id')->constrained('supervisions')->onDelete('cascade');
            $table->integer('guidance_count')->default(0);
            $table->integer('revision_count')->default(0);
            $table->integer('submission_count')->default(0);
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->integer('late_days')->default(0);
            $table->timestamp('recorded_at');
            $table->timestamps();

            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_records');
    }
};
