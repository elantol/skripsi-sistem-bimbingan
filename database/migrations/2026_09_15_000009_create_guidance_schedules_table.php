<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guidance_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guidance_request_id')->constrained('guidance_requests')->onDelete('cascade');
            $table->foreignId('lecturer_id')->constrained('lecturers')->onDelete('cascade');
            $table->date('scheduled_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('method', ['tatap_muka', 'online']);
            $table->string('location', 255)->nullable();
            $table->string('meeting_url', 500)->nullable();
            $table->enum('status', ['terjadwal', 'selesai', 'dibatalkan'])->default('terjadwal');
            $table->timestamps();

            $table->index('scheduled_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guidance_schedules');
    }
};
