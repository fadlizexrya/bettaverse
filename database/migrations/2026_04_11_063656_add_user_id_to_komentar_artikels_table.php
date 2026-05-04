<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('komentar_artikels', function (Blueprint $table) {
            // ✅ TAMBAH USER_ID
            $table->foreignId('user_id')
                ->after('artikel_id')
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('komentar_artikels', function (Blueprint $table) {
            // ❌ HAPUS SAAT ROLLBACK
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};