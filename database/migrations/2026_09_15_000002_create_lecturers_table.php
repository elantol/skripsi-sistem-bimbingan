<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nidn', 50)->unique();
            $table->string('name', 150);
            $table->string('academic_degree', 100)->nullable();
            $table->string('phone', 30)->nullable();
            $table->timestamps();

            $table->index('nidn');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};
