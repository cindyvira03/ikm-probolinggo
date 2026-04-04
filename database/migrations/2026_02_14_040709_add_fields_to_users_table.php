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
            $table->unsignedBigInteger('pembeli_id')->nullable()->after('password');
            $table->enum('role', ['admin', 'ikm', 'pembeli'])->default('pembeli')->change();

            $table->foreign('pembeli_id')->references('id')->on('pembeli')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pembeli_id']);
            $table->dropColumn('pembeli_id');
            $table->enum('role', ['admin', 'ikm', 'pembeli'])->default('pembeli')->change();
        });
    }
};
