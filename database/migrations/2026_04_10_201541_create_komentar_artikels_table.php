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
    Schema::create('komentar_artikels', function (Blueprint $table) {
        $table->id();
        $table->foreignId('artikel_id')->constrained()->cascadeOnDelete();
        $table->text('isi');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar_artikels');
    }
};
