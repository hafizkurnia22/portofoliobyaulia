<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_soals', function (Blueprint $table) {
            $table->id();
            $table->string('kode_soal')->nullable();
            $table->enum('kategori', ['TWK', 'TIU', 'TKP'])->default('TWK');
            $table->text('pertanyaan');
            $table->text('opsi_a');
            $table->text('opsi_b');
            $table->text('opsi_c');
            $table->text('opsi_d');
            $table->text('opsi_e');
            $table->enum('jawaban_benar', ['A', 'B', 'C', 'D', 'E'])->nullable();
            $table->unsignedTinyInteger('skor_a')->default(0);
            $table->unsignedTinyInteger('skor_b')->default(0);
            $table->unsignedTinyInteger('skor_c')->default(0);
            $table->unsignedTinyInteger('skor_d')->default(0);
            $table->unsignedTinyInteger('skor_e')->default(0);
            $table->text('pembahasan')->nullable();
            $table->enum('status', ['aktif', 'draft'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_soals');
    }
};
