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
            $table->json('minimal_skor_kelulusan')->nullable()->after('durasi_menit_kategori');
        });

        DB::table('tryout_pengaturans')
            ->whereNull('minimal_skor_kelulusan')
            ->update(['minimal_skor_kelulusan' => json_encode(['TWK' => 0, 'TIU' => 0, 'TKP' => 0])]);
    }

    public function down(): void
    {
        Schema::table('tryout_pengaturans', function (Blueprint $table) {
            $table->dropColumn('minimal_skor_kelulusan');
        });
    }
};
