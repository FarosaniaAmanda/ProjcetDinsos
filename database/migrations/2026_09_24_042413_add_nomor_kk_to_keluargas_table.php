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
        Schema::table('keluargas', function (Blueprint $table) {
            $table->string('nomor_kk', 16)
                ->unique()
                ->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {
            $table->dropUnique(['nomor_kk']);
            $table->dropColumn('nomor_kk');
        });
    }
};