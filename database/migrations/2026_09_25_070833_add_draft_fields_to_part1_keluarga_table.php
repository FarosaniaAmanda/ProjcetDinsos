<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('part1_keluarga')) {
            return;
        }

        if (DB::getDriverName() === 'mysql') {
            DB::statement('
                ALTER TABLE part1_keluarga
                MODIFY updated_at TIMESTAMP NULL DEFAULT NULL
            ');
        }

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
        if (! Schema::hasTable('part1_keluarga')) {
            return;
        }

        Schema::table('part1_keluarga', function (Blueprint $table) {

            $table->dropColumn([
                'status',
                'current_part',
            ]);

        });
    }
};
