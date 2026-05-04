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
    Schema::table('komentar_artikels', function (Blueprint $table) {
        $table->dropColumn('isi');
    });
}

public function down(): void
{
    Schema::table('komentar_artikels', function (Blueprint $table) {
        $table->text('isi')->nullable();
    });
}
};
