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
        if (!Schema::hasTable('keluargas')) {
            Schema::create('keluargas', function (Blueprint $table) {
                $table->id();
                $table->string('kode')->nullable();
                $table->string('no_kk', 18)->nullable();
                $table->string('nik', 18)->nullable();
                $table->string('nama_lengkap')->nullable();
                $table->string('status_keluarga')->nullable();
                $table->string('kecamatan_id', 13)->nullable();
                $table->string('kelurahan_id', 13)->nullable();
                $table->unsignedBigInteger('rt_rw_id')->nullable();
                $table->string('kode_pos', 10)->nullable();
                $table->text('alamat_lengkap')->nullable();
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('keluarga_anggotas')) {
            Schema::create('keluarga_anggotas', function (Blueprint $table) {
                $table->id();
                $table->string('kode')->nullable();
                $table->string('keluarga_kode')->nullable();
                $table->string('nik', 18)->nullable();
                $table->string('nama_lengkap')->nullable();
                $table->string('status_keluarga')->nullable();
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->timestamps();
            });
        }

        Schema::table('keluargas', function (Blueprint $table) {
            if (!Schema::hasColumn('keluargas', 'no_kk')) {
                $table->string('no_kk', 18)->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'kecamatan_id')) {
                $table->string('kecamatan_id', 13)->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'kelurahan_id')) {
                $table->string('kelurahan_id', 13)->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'rt_rw_id')) {
                $table->unsignedBigInteger('rt_rw_id')->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'kode_pos')) {
                $table->string('kode_pos', 10)->nullable();
            }

            if (!Schema::hasColumn('keluargas', 'alamat_lengkap')) {
                $table->text('alamat_lengkap')->nullable();
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE `keluargas` MODIFY `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
            DB::statement('ALTER TABLE `keluargas` MODIFY `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        }

        if (Schema::hasColumn('keluargas', 'nomor_kk') && !Schema::hasColumn('keluargas', 'no_kk')) {
            DB::statement('ALTER TABLE `keluargas` CHANGE COLUMN `nomor_kk` `no_kk` VARCHAR(18) NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('keluargas')) {
            return;
        }

        Schema::table('keluargas', function (Blueprint $table) {
            $columns = [
                'kecamatan_id',
                'kelurahan_id',
                'rt_rw_id',
                'kode_pos',
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
