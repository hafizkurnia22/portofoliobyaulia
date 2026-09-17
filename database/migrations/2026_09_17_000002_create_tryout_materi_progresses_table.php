<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_materi_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tryout_peserta_id')->constrained('tryout_pesertas')->cascadeOnDelete();
            $table->foreignId('tryout_materi_id')->constrained('tryout_materis')->cascadeOnDelete();
            $table->boolean('is_bookmarked')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            $table->unique(['tryout_peserta_id', 'tryout_materi_id'], 'tryout_materi_progress_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_materi_progresses');
    }
};
