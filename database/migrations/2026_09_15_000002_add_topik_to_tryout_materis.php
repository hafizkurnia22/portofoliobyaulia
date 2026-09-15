<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tryout_materis', function (Blueprint $table) {
            $table->json('topik')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('tryout_materis', fn (Blueprint $table) => $table->dropColumn('topik'));
    }
};
