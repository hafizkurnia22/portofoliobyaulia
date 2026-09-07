<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_pengaturans', function (Blueprint $table) {
            $table->id();
            $table->boolean('acak_soal')->default(false);
            $table->boolean('acak_jawaban')->default(false);
            $table->timestamps();
        });

        DB::table('tryout_pengaturans')->insert([
            'acak_soal' => false,
            'acak_jawaban' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_pengaturans');
    }
};
