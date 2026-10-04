<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nim', 50)->unique();
            $table->string('name', 150);
            $table->string('study_program', 150);
            $table->unsignedSmallInteger('generation');
            $table->string('phone', 30)->nullable();
            $table->timestamps();

            $table->index('nim');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
