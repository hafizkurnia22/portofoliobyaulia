<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_kategori_soals', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'draft'])->default('aktif');
            $table->timestamps();
        });

        DB::table('tryout_kategori_soals')->insert([
            [
                'kode' => 'TWK',
                'nama' => 'Tes Wawasan Kebangsaan',
                'deskripsi' => 'Materi nasionalisme, integritas, bela negara, pilar negara, dan bahasa Indonesia.',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'TIU',
                'nama' => 'Tes Intelegent Umum',
                'deskripsi' => 'Materi kemampuan verbal, numerik, figural, logika, dan analitis.',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'TKP',
                'nama' => 'Tes Kepribadian',
                'deskripsi' => 'Materi pelayanan publik, jejaring kerja, sosial budaya, profesionalisme, dan TIK.',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_kategori_soals');
    }
};
