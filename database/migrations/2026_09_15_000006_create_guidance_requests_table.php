<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guidance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supervision_id')->constrained('supervisions')->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->enum('request_type', [
                'proposal', 'bab_1', 'bab_2', 'bab_3', 'bab_4', 'bab_5', 'laporan', 'revisi', 'lainnya'
            ]);
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->enum('status', [
                'DRAFT', 'MENUNGGU_REVIEW', 'PERLU_REVISI', 'DISETUJUI', 'TERJADWAL', 'SELESAI', 'DITOLAK'
            ])->default('MENUNGGU_REVIEW');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('lecturer_id');
            $table->index('supervision_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guidance_requests');
    }
};
