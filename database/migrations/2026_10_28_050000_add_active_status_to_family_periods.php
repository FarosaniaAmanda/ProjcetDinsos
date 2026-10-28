<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $duplicateRelationExists = DB::table('keluarga_periodes')
            ->select(['keluarga_kode', 'periode_kode'])
            ->groupBy('keluarga_kode', 'periode_kode')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($duplicateRelationExists) {
            throw new RuntimeException(
                'Duplicate family-period relations must be resolved before enabling respondent status.'
            );
        }

        if (DB::getDriverName() === 'mysql') {
            $this->updateMysqlSchema();

            return;
        }

        Schema::table('keluarga_periodes', function (Blueprint $table): void {
            $table->boolean('is_active')->default(true)->after('status_kuisoner');
            $table->unique(
                ['keluarga_kode', 'periode_kode'],
                'keluarga_periodes_family_period_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('keluarga_periodes', function (Blueprint $table): void {
            $table->dropUnique('keluarga_periodes_family_period_unique');
            $table->dropColumn('is_active');
        });
    }

    private function updateMysqlSchema(): void
    {
        $sqlMode = DB::selectOne('SELECT @@SESSION.sql_mode AS sql_mode')->sql_mode;
        $compatibleSqlMode = collect(explode(',', $sqlMode))
            ->reject(static fn (string $mode): bool => in_array(
                $mode,
                ['NO_ZERO_DATE', 'NO_ZERO_IN_DATE'],
                true
            ))
            ->implode(',');

        DB::statement('SET SESSION sql_mode = ?', [$compatibleSqlMode]);

        try {
            DB::statement(
                'ALTER TABLE `keluarga_periodes`
                ADD `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `status_kuisoner`,
                ADD UNIQUE KEY `keluarga_periodes_family_period_unique` (`keluarga_kode`, `periode_kode`)'
            );
        } finally {
            DB::statement('SET SESSION sql_mode = ?', [$sqlMode]);
        }
    }
};
