<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tryout_pesertas', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('username')->unique();
            $table->string('pin_hash');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tryout_pesertas');
    }
};
