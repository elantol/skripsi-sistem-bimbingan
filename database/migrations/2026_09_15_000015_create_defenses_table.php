<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('defenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('supervision_id')->constrained('supervisions')->onDelete('cascade');
            $table->enum('defense_type', ['proposal', 'hasil', 'skripsi_akhir'])->default('skripsi_akhir');
            $table->string('title', 500);
            $table->text('abstract')->nullable();
            $table->enum('status', [
                'MENUNGGU_VERIFIKASI', 'TERJADWAL', 'SEDANG_BERLANGSUNG', 'LULUS', 'LULUS_REVISI', 'TIDAK_LULUS', 'DITOLAK'
            ])->default('MENUNGGU_VERIFIKASI');
            $table->date('scheduled_date')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->enum('method', ['tatap_muka', 'online'])->default('tatap_muka');
            $table->string('room', 100)->nullable();
            $table->string('meeting_url', 500)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->string('grade', 5)->nullable(); // A, B, C, D, E
            $table->text('notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('defenses');
    }
};
