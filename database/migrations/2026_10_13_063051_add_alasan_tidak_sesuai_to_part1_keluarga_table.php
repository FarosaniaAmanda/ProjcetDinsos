<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = 'part1_keluarga';

        if (
            Schema::hasTable($tableName) &&
            ! Schema::hasColumn($tableName, 'alasan_tidak_sesuai')
        ) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->text('alasan_tidak_sesuai')->nullable();
            });
        }
    }

    public function down(): void
    {
        $tableName = 'part1_keluarga';

        if (
            Schema::hasTable($tableName) &&
            Schema::hasColumn($tableName, 'alasan_tidak_sesuai')
        ) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('alasan_tidak_sesuai');
            });
        }
    }
};
