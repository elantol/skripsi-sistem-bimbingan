<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('defense_examiners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('defense_id')->constrained('defenses')->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->enum('role', ['ketua_penguji', 'penguji_1', 'penguji_2', 'pembimbing_1', 'pembimbing_2']);
            $table->boolean('is_present')->default(false);
            $table->timestamps();

            $table->unique(['defense_id', 'lecturer_id'], 'uk_def_lecturer');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('defense_examiners');
    }
};
