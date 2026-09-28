<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Perbaiki default updated_at terlebih dahulu
        DB::statement("
            ALTER TABLE part1_keluarga
            MODIFY updated_at TIMESTAMP NULL DEFAULT NULL
        ");

        // Tambahkan kolom draft
        Schema::table('part1_keluarga', function (Blueprint $table) {

            $table->string('status')
                ->default('draft')
                ->after('geotangging');

            $table->unsignedTinyInteger('current_part')
                ->default(1)
                ->after('status');

        });
    }

    public function down(): void
    {
        Schema::table('part1_keluarga', function (Blueprint $table) {

            $table->dropColumn([
                'status',
                'current_part'
            ]);

        });
    }
};