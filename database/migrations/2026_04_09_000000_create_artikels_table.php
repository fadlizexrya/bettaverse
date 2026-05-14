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
    	Schema::create('artikels', function (Blueprint $table) {
           $table->id();
           $table->foreignId('user_id')->constrained()->onDelete('cascade');
           $table->string('judul');
           $table->string('slug')->unique(); // Untuk URL ramah SEO
           $table->text('ringkasan');
           $table->longText('isi');
           $table->string('gambar')->nullable();
           $table->integer('waktu_baca')->default(5);
           $table->timestamps();
	   $table->string('status');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artikels');
    }
};
