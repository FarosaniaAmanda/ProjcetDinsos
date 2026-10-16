<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('part5_anggota_keluargas')) {
            return;
        }

        Schema::table('part5_anggota_keluargas', function (Blueprint $table) {
            if (! Schema::hasColumn('part5_anggota_keluargas', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable()->after('jenis_kelamin');
            }

            if (! Schema::hasColumn('part5_anggota_keluargas', 'ijazah_lainnya')) {
                $table->string('ijazah_lainnya')->nullable()->after('ijazah_tertinggi');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                'ALTER TABLE `part5_anggota_keluargas` MODIFY `updated_at` timestamp NULL DEFAULT NULL'
            );
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('part5_anggota_keluargas')) {
            return;
        }

        Schema::table('part5_anggota_keluargas', function (Blueprint $table) {
            if (Schema::hasColumn('part5_anggota_keluargas', 'tanggal_lahir')) {
                $table->dropColumn('tanggal_lahir');
            }

            if (Schema::hasColumn('part5_anggota_keluargas', 'ijazah_lainnya')) {
                $table->dropColumn('ijazah_lainnya');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement(
                "ALTER TABLE `part5_anggota_keluargas` MODIFY `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'"
            );
        }
    }
};
