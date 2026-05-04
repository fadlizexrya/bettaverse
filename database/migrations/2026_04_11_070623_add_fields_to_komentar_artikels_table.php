<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('komentar_artikels', function (Blueprint $table) {

            if (!Schema::hasColumn('komentar_artikels', 'isi_komentar')) {
                $table->text('isi_komentar')->after('artikel_id');
            }

            if (!Schema::hasColumn('komentar_artikels', 'user_id')) {
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('komentar_artikels', function (Blueprint $table) {

            if (Schema::hasColumn('komentar_artikels', 'isi_komentar')) {
                $table->dropColumn('isi_komentar');
            }

            if (Schema::hasColumn('komentar_artikels', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }
        });
    }
};