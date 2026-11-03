<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('part1_keluarga', function (Blueprint $table) {
            $table->uuid('uuid')->nullable();
        });

        DB::table('part1_keluarga')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(500, function ($questionnaires): void {
                foreach ($questionnaires as $questionnaire) {
                    DB::table('part1_keluarga')
                        ->where('id', $questionnaire->id)
                        ->update(['uuid' => (string) Str::uuid()]);
                }
            });

        Schema::table('part1_keluarga', function (Blueprint $table): void {
            $table->uuid('uuid')->nullable(false)->change();
            $table->unique('uuid', 'part1_keluarga_uuid_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('part1_keluarga', function (Blueprint $table) {
            $table->dropUnique('part1_keluarga_uuid_unique');
            $table->dropColumn('uuid');
        });
    }
};
