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
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ikm_id')->constrained('profil_ikm')->onDelete('cascade');
            $table->string('nama_produk');
            $table->string('jenis_produk');
            $table->integer('harga');
            $table->longText('deskripsi');
            $table->string('varian')->nullable();
            $table->string('ukuran')->nullable();
            $table->string('foto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
