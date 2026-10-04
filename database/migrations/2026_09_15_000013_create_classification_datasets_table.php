<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classification_datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained('students')->onDelete('set null');
            $table->integer('guidance_count');
            $table->integer('revision_count');
            $table->integer('submission_count');
            $table->decimal('progress_percentage', 5, 2);
            $table->integer('late_days');
            $table->string('guidance_frequency', 50); // Tinggi, Sedang, Rendah
            $table->string('label', 50); // LANCAR, PERLU_PERHATIAN, TERLAMBAT
            $table->string('dataset_period', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classification_datasets');
    }
};
