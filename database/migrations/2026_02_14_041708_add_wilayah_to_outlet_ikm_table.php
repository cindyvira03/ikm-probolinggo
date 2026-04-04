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
        Schema::table('outlet_ikm', function (Blueprint $table) {
            $table->string('provinsi')->nullable();
            $table->string('kota_kab')->nullable();
            $table->string('kecamatan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outlet_ikm', function (Blueprint $table) {
            $table->dropColumn('provinsi');
            $table->dropColumn('kota_kab');
            $table->dropColumn('kecamatan');
        });
    }
};
