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
            $table->unsignedSmallInteger('durasi_menit')->default(45)->after('acak_jawaban');
        });

        DB::table('tryout_pengaturans')
            ->whereNull('durasi_menit')
            ->update(['durasi_menit' => 45]);
    }

    public function down(): void
    {
        Schema::table('tryout_pengaturans', function (Blueprint $table) {
            $table->dropColumn('durasi_menit');
        });
    }
};
