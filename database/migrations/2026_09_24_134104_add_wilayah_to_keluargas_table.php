<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {

            if (!Schema::hasColumn('keluargas', 'kecamatan_id')) {
                $table->string('kecamatan_id')->nullable()->after('status_keluarga');
            }

            if (!Schema::hasColumn('keluargas', 'kelurahan_id')) {
                $table->string('kelurahan_id')->nullable()->after('kecamatan_id');
            }

            if (!Schema::hasColumn('keluargas', 'rt_rw_id')) {
                $table->unsignedBigInteger('rt_rw_id')->nullable()->after('kelurahan_id');
            }

        });
    }

    public function down(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {

            if (Schema::hasColumn('keluargas', 'rt_rw_id')) {
                $table->dropColumn('rt_rw_id');
            }

            if (Schema::hasColumn('keluargas', 'kelurahan_id')) {
                $table->dropColumn('kelurahan_id');
            }

            if (Schema::hasColumn('keluargas', 'kecamatan_id')) {
                $table->dropColumn('kecamatan_id');
            }

        });
    }
};