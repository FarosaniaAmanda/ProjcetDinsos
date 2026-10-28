<?php

use App\Casts\EncryptedIdentifier;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
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

        if (! Schema::hasTable('part1_keluarga')) {
            throw new RuntimeException('The part1_keluarga table must exist before encrypting its identifiers.');
        }

        $this->widenIdentifierColumns();
        $this->transformIdentifiers(encrypt: true);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->transformIdentifiers(encrypt: false);
    }

    private function transformIdentifiers(bool $encrypt): void
    {
        DB::table('part1_keluarga')
            ->select(['id', 'nik', 'no_kk'])
            ->orderBy('id')
            ->chunkById(500, function ($records) use ($encrypt): void {
                foreach ($records as $record) {
                    $values = [];

                    foreach (['nik', 'no_kk'] as $column) {
                        $value = $record->{$column};

                        if ($value === null) {
                            continue;
                        }

                        $value = (string) $value;

                        if ($encrypt) {
                            $values[$column] = EncryptedIdentifier::encrypt($value);

                            continue;
                        }

                        if (str_starts_with($value, 'enc:v1:')) {
                            $values[$column] = Crypt::decryptString(
                                substr($value, strlen('enc:v1:'))
                            );
                        }
                    }

                    if ($values !== []) {
                        DB::table('part1_keluarga')
                            ->where('id', $record->id)
                            ->update($values);
                    }
                }
            });
    }

    private function widenIdentifierColumns(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `part1_keluarga` MODIFY `nik` TEXT NULL');
            DB::statement('ALTER TABLE `part1_keluarga` MODIFY `no_kk` TEXT NULL');

            return;
        }

        Schema::table('part1_keluarga', function (Blueprint $table): void {
            $table->text('nik')->nullable()->change();
            $table->text('no_kk')->nullable()->change();
        });
    }
};
