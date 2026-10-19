<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('part5_anggota_keluargas')) {
            return;
        }

        Schema::table('part5_anggota_keluargas', function (Blueprint $table) {
            if (! Schema::hasColumn('part5_anggota_keluargas', 'pekerjaan_lainnya')) {
                $table->string('pekerjaan_lainnya')->nullable()->after('pekerjaan_utama');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('part5_anggota_keluargas')) {
            return;
        }

        Schema::table('part5_anggota_keluargas', function (Blueprint $table) {
            if (Schema::hasColumn('part5_anggota_keluargas', 'pekerjaan_lainnya')) {
                $table->dropColumn('pekerjaan_lainnya');
            }
        });
    }
};
