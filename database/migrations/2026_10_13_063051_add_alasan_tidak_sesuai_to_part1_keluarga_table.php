<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('part1_keluarga', 'alasan_tidak_sesuai')) {
            Schema::table('part1_keluarga', function (Blueprint $table) {
                $table->text('alasan_tidak_sesuai')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('part1_keluarga', 'alasan_tidak_sesuai')) {
            Schema::table('part1_keluarga', function (Blueprint $table) {
                $table->dropColumn('alasan_tidak_sesuai');
            });
        }
    }
};
