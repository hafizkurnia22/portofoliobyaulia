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
            $table->json('jumlah_soal_kategori')->nullable()->after('jumlah_soal');
            $table->json('durasi_menit_kategori')->nullable()->after('jumlah_soal_kategori');
        });

        DB::table('tryout_pengaturans')->update([
            'jumlah_soal_kategori' => json_encode(['TWK' => 10, 'TIU' => 10, 'TKP' => 10]),
            'durasi_menit_kategori' => json_encode(['TWK' => 15, 'TIU' => 15, 'TKP' => 15]),
        ]);
    }

    public function down(): void
    {
        Schema::table('tryout_pengaturans', function (Blueprint $table) {
            $table->dropColumn(['jumlah_soal_kategori', 'durasi_menit_kategori']);
        });
    }
};
