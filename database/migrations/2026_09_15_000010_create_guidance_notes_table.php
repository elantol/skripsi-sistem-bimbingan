<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guidance_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guidance_request_id')->constrained('guidance_requests')->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->text('note');
            $table->enum('result', ['lanjut_revisi', 'lanjut_bimbingan', 'disetujui']);
            $table->decimal('progress_percentage', 5, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guidance_notes');
    }
};
