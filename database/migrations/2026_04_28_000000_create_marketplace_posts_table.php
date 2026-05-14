<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
    	Schema::create('marketplace_posts', function (Blueprint $table) {
           $table->id();
           $table->foreignId('user_id')->constrained()->onDelete('cascade');
           $table->string('nama_cupang');
           $table->string('jenis_cupang');
           $table->integer('harga');
           $table->integer('stok')->default(1);
           $table->string('foto_cupang')->nullable();
           $table->text('deskripsi');
           $table->string('no_wa'); // Memudahkan UC-03: Menghubungi Penjual
           $table->timestamps();
    	});
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketplace_posts');
    }
};
