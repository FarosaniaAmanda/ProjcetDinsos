<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {

            if (!Schema::hasColumn('keluargas', 'nomor_kk')) {
                $table->string('nomor_kk')->unique()->after('id');
            }

            if (!Schema::hasColumn('keluargas', 'nama_kepala_keluarga')) {
                $table->string('nama_kepala_keluarga')->after('nomor_kk');
            }

            if (!Schema::hasColumn('keluargas', 'provinsi')) {
                $table->string('provinsi')->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'daerah')) {
                $table->string('daerah')->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'kecamatan')) {
                $table->string('kecamatan')->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'kelurahan')) {
                $table->string('kelurahan')->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'kode_pos')) {
                $table->string('kode_pos', 10)->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'rt_rw')) {
                $table->string('rt_rw', 20)->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'alamat_lengkap')) {
                $table->text('alamat_lengkap')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('keluargas', function (Blueprint $table) {

            $columns = [
                'nomor_kk',
                'nama_kepala_keluarga',
                'provinsi',
                'daerah',
                'kecamatan',
                'kelurahan',
                'kode_pos',
                'rt_rw',
                'alamat_lengkap',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('keluargas', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};