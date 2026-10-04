<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classification_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('model_version', 50)->default('C4.5-v1.0');
            $table->string('predicted_label', 50); // LANCAR, PERLU_PERHATIAN, TERLAMBAT
            $table->decimal('confidence', 5, 2)->nullable();
            $table->timestamp('calculated_at');
            $table->timestamps();

            $table->index('student_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classification_results');
    }
};
