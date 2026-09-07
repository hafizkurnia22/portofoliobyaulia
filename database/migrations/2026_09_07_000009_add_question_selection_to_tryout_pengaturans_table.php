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
            $table->boolean('acak_seimbang_kategori')->default(false)->after('acak_soal');
            $table->unsignedSmallInteger('jumlah_soal')->default(30)->after('durasi_menit');
        });

        DB::table('tryout_pengaturans')
            ->whereNull('jumlah_soal')
            ->update(['jumlah_soal' => 30]);
    }

    public function down(): void
    {
        Schema::table('tryout_pengaturans', function (Blueprint $table) {
            $table->dropColumn(['acak_seimbang_kategori', 'jumlah_soal']);
        });
    }
};
