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
    Schema::create('tentang_sayas', function (Blueprint $table) {
        $table->id();
        $table->string('nama');
        $table->string('bidang')->nullable();
        $table->string('status')->nullable();
        $table->text('deskripsi_1')->nullable();
        $table->text('deskripsi_2')->nullable();
        $table->string('foto')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tentang_sayas');
    }
};
