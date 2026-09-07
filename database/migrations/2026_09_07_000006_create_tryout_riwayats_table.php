<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_riwayats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_peserta_id')->constrained('tryout_pesertas')->cascadeOnDelete();
            $table->unsignedInteger('total_soal')->default(0);
            $table->unsignedInteger('total_dijawab')->default(0);
            $table->unsignedInteger('total_benar')->default(0);
            $table->unsignedInteger('total_ragu')->default(0);
            $table->unsignedInteger('total_skor')->default(0);
            $table->unsignedInteger('durasi_detik')->default(0);
            $table->json('detail_jawaban')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_riwayats');
    }
};
