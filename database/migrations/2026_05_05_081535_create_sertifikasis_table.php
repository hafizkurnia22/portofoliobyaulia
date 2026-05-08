<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('sertifikasis', function (Blueprint $table) {
        $table->id();
        $table->string('nama_sertifikat');
        $table->string('penyelenggara');
        $table->string('tahun');
        $table->text('deskripsi')->nullable();
        $table->string('file_pdf')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikasis');
    }
};
