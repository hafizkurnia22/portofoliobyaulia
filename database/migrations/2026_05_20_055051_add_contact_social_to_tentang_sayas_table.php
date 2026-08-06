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
    Schema::table('tentang_sayas', function (Blueprint $table) {
        $table->string('whatsapp')->nullable();
        $table->string('email_kontak')->nullable();
        $table->string('facebook')->nullable();
        $table->string('instagram')->nullable();
        $table->string('tiktok')->nullable();
    });
}

public function down(): void
{
    Schema::table('tentang_sayas', function (Blueprint $table) {
        $table->dropColumn([
            'whatsapp',
            'email_kontak',
            'facebook',
            'instagram',
            'tiktok'
        ]);
    });
}
};
