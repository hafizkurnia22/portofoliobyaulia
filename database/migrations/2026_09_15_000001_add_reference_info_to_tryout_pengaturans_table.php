<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tryout_pengaturans', function (Blueprint $table) {
            $table->text('kisi_kisi_deskripsi')->nullable()->after('jumlah_soal');
            $table->string('permenpan_file')->nullable()->after('kisi_kisi_deskripsi');
            $table->string('permenpan_nama')->nullable()->after('permenpan_file');
        });

        DB::table('tryout_pengaturans')
            ->whereNull('kisi_kisi_deskripsi')
            ->update([
                'kisi_kisi_deskripsi' => 'Materi dan simulasi Tryout CPNS disusun berdasarkan kisi-kisi seleksi kompetensi dasar yang berlaku. Admin dapat memperbarui keterangan ini dan mengunggah surat PermenPAN terbaru sebagai acuan belajar peserta.',
            ]);
    }

    public function down(): void
    {
        Schema::table('tryout_pengaturans', function (Blueprint $table) {
            $table->dropColumn(['kisi_kisi_deskripsi', 'permenpan_file', 'permenpan_nama']);
        });
    }
};
