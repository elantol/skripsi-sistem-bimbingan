<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('defense_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('defense_id')->constrained('defenses')->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->decimal('presentation_score', 5, 2)->default(0.00); // Bobot 20%
            $table->decimal('manuscript_score', 5, 2)->default(0.00);   // Bobot 25%
            $table->decimal('mastery_score', 5, 2)->default(0.00);      // Bobot 35%
            $table->decimal('product_score', 5, 2)->default(0.00);      // Bobot 20%
            $table->decimal('total_score', 5, 2)->default(0.00);
            $table->text('revision_notes')->nullable();
            $table->timestamps();

            $table->unique(['defense_id', 'lecturer_id'], 'uk_def_score_lecturer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('defense_scores');
    }
};
