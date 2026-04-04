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
        Schema::create('outlet_ikm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ikm_id')
                ->constrained('profil_ikm')
                ->onDelete('cascade');
            $table->string('alamat');
            $table->string('lokasi_googlemap');
            $table->string('foto_lokasi_tampak_depan');
            $table->longText('cara_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlet_ikm');
    }
};
