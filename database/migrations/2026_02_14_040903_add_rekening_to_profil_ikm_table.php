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
        Schema::table('profil_ikm', function (Blueprint $table) {
            $table->string('no_rekening')->nullable();
            $table->string('jenis_rekening')->nullable();
            $table->string('nama_rekening')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profil_ikm', function (Blueprint $table) {
            $table->dropColumn('no_rekening');
            $table->dropColumn('jenis_rekening');
            $table->dropColumn('nama_rekening');
        });
    }
};
