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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->id();
            $table->string('no_pesanan')->unique();
            $table->foreignId('pembeli_id')->constrained('pembeli')->cascadeOnDelete();
            $table->foreignId('ikm_id')->constrained('profil_ikm')->cascadeOnDelete();
            $table->foreignId('outlet_id')->nullable()->constrained('outlet_ikm')->nullOnDelete();

            $table->enum('metode_pengiriman', ['diambil', 'dikirim']);
            $table->integer('total_bayar');
            $table->enum('status_pesanan', ['pending', 'diproses', 'dikirim', 'selesai', 'batal'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
