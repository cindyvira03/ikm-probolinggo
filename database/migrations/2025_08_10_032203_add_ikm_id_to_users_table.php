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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('ikm_id')
                ->nullable()
                ->constrained('profil_ikm')
                ->onDelete('cascade');
            $table->enum('role', ['Admin', 'Pengguna'])->default('Pengguna');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['ikm_id']);
            $table->dropColumn('ikm_id');
            $table->dropColumn('role');
        });
    }
};
