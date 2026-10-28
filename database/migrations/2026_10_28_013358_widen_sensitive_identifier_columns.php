<?php

use App\Casts\EncryptedIdentifier;
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
        if (blank(config('app.key'))) {
            throw new RuntimeException('APP_KEY must be configured before encrypting stored identifiers.');
        }

        $this->changeIdentifierColumns(255);

        $this->encryptLegacyIdentifiers('keluargas', ['no_kk', 'nik']);
        $this->encryptLegacyIdentifiers('keluarga_anggotas', ['nik']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ([
            'keluargas' => ['no_kk', 'nik'],
            'keluarga_anggotas' => ['nik'],
        ] as $table => $columns) {
            foreach ($columns as $column) {
                if (DB::table($table)->whereRaw("LENGTH(`{$column}`) > 18")->exists()) {
                    throw new RuntimeException(
                        'Sensitive identifier columns cannot be narrowed without risking data loss.'
                    );
                }
            }
        }

        $this->changeIdentifierColumns(18);
    }

    /**
     * Encrypt existing plaintext values in bounded batches.
     *
     * @param  array<int, string>  $columns
     */
    private function encryptLegacyIdentifiers(string $table, array $columns): void
    {
        DB::table($table)
            ->select(['id', ...$columns])
            ->orderBy('id')
            ->chunkById(500, function ($records) use ($table, $columns): void {
                foreach ($records as $record) {
                    $encryptedValues = [];

                    foreach ($columns as $column) {
                        $value = $record->{$column};

                        if ($value !== null) {
                            $encryptedValues[$column] = EncryptedIdentifier::encrypt((string) $value);
                        }
                    }

                    if ($encryptedValues !== []) {
                        DB::table($table)
                            ->where('id', $record->id)
                            ->update($encryptedValues);
                    }
                }
            });
    }

    private function changeIdentifierColumns(int $length): void
    {
        if (DB::getDriverName() === 'mysql') {
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
                DB::statement("ALTER TABLE `keluargas` MODIFY `no_kk` VARCHAR({$length}) NOT NULL");
                DB::statement("ALTER TABLE `keluargas` MODIFY `nik` VARCHAR({$length}) NOT NULL");
                DB::statement("ALTER TABLE `keluarga_anggotas` MODIFY `nik` VARCHAR({$length}) NOT NULL");
            } finally {
                DB::statement('SET SESSION sql_mode = ?', [$sqlMode]);
            }

            return;
        }

        Schema::table('keluargas', function (Blueprint $table) use ($length) {
            $table->string('no_kk', $length)->change();
            $table->string('nik', $length)->change();
        });

        Schema::table('keluarga_anggotas', function (Blueprint $table) use ($length) {
            $table->string('nik', $length)->change();
        });
    }
};
