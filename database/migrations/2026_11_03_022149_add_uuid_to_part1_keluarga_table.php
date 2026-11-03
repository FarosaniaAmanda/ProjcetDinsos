
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom UUID jika belum tersedia.
        if (!Schema::hasColumn('part1_keluarga', 'uuid')) {
            Schema::table('part1_keluarga', function (Blueprint $table) {
                $table->uuid('uuid')->nullable();
            });
        }

        // Isi UUID untuk data lama yang masih kosong.
        DB::table('part1_keluarga')
            ->whereNull('uuid')
            ->orderBy('id')
            ->chunkById(500, function ($questionnaires): void {
                foreach ($questionnaires as $questionnaire) {
                    DB::table('part1_keluarga')
                        ->where('id', $questionnaire->id)
                        ->whereNull('uuid')
                        ->update([
                            'uuid' => (string) Str::uuid()
                        ]);
                }
            });

        // Jadikan UUID wajib diisi.
        Schema::table('part1_keluarga', function (Blueprint $table): void {
            $table->uuid('uuid')->nullable(false)->change();
        });

        // Buat unique index jika belum tersedia.
        $indexes = DB::select('SHOW INDEX FROM part1_keluarga');

        $uniqueExists = collect($indexes)->contains(
            fn ($index) => $index->Key_name === 'part1_keluarga_uuid_unique'
        );

        if (!$uniqueExists) {
            Schema::table('part1_keluarga', function (Blueprint $table): void {
                $table->unique('uuid', 'part1_keluarga_uuid_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('part1_keluarga', 'uuid')) {
            $indexes = DB::select('SHOW INDEX FROM part1_keluarga');

            $uniqueExists = collect($indexes)->contains(
                fn ($index) => $index->Key_name === 'part1_keluarga_uuid_unique'
            );

            Schema::table('part1_keluarga', function (Blueprint $table) use ($uniqueExists): void {
                if ($uniqueExists) {
                    $table->dropUnique('part1_keluarga_uuid_unique');
                }

                $table->dropColumn('uuid');
            });
        }
    }
};
