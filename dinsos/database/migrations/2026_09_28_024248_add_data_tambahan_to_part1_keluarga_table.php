<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('part1_keluarga', function (Blueprint $table) {
            $table->string('nama_kepala_keluarga')->nullable()->after('nik');
            $table->string('nomor_rumah')->nullable()->after('jalan_rumah');
        });
    }

    public function down(): void
    {
        Schema::table('part1_keluarga', function (Blueprint $table) {
            $table->dropColumn([
                'nama_kepala_keluarga',
                'nomor_rumah',
            ]);
        });
    }
};