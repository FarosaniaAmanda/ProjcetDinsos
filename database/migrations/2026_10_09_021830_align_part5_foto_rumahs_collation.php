<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (
            Schema::hasTable('part5_foto_rumahs') &&
            DB::connection()->getDriverName() === 'mysql'
        ) {
            DB::statement(
                'ALTER TABLE `part5_foto_rumahs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci'
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (
            Schema::hasTable('part5_foto_rumahs') &&
            DB::connection()->getDriverName() === 'mysql'
        ) {
            DB::statement(
                'ALTER TABLE `part5_foto_rumahs` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci'
            );
        }
    }
};
