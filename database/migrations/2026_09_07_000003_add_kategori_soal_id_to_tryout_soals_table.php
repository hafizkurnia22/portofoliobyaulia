<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tryout_soals', function (Blueprint $table) {
            $table->foreignId('tryout_kategori_soal_id')
                ->nullable()
                ->after('kode_soal')
                ->constrained('tryout_kategori_soals')
                ->nullOnDelete();
        });

        DB::table('tryout_soals')
            ->join('tryout_kategori_soals', 'tryout_soals.kategori', '=', 'tryout_kategori_soals.kode')
            ->update([
                'tryout_soals.tryout_kategori_soal_id' => DB::raw('tryout_kategori_soals.id'),
            ]);
    }

    public function down(): void
    {
        Schema::table('tryout_soals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tryout_kategori_soal_id');
        });
    }
};
