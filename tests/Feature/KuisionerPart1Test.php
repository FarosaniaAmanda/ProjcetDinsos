<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class KuisionerPart1Test extends TestCase
{
    public function test_saving_part1_persists_the_address_mismatch_reason(): void
    {
        Schema::dropIfExists('keluarga_periodes');
        Schema::dropIfExists('periodes');
        Schema::dropIfExists('part1_keluarga');
        Schema::create('part1_keluarga', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode')->nullable();
            $table->string('nik')->nullable();
            $table->string('nama_kepala_keluarga')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('jml_keluarga')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('daerah')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->string('alamat_lengkap')->nullable();
            $table->string('jalan_rumah')->nullable();
            $table->string('nomor_rumah')->nullable();
            $table->boolean('is_alamat_sesuai')->nullable();
            $table->string('geotangging')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedTinyInteger('current_part')->default(1);
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        $migration = require database_path(
            'migrations/2026_10_13_063051_add_alasan_tidak_sesuai_to_part1_keluarga_table.php'
        );
        $migration->up();

        Schema::create('periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->string('status_periode');
        });

        Schema::create('keluarga_periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('keluarga_kode');
            $table->string('periode_kode');
            $table->string('status_kuisoner')->nullable();
            $table->timestamps();
        });

        $periodeId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-TEST',
            'nama' => 'Periode Tes',
            'status_periode' => 'Aktif',
        ]);
        DB::table('keluarga_periodes')->insert([
            'kode' => 'KLP-TEST-0001',
            'keluarga_kode' => 'KLG-TEST-0001',
            'periode_kode' => 'PERIODE-TEST',
            'status_kuisoner' => 'belum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withSession(['periode_id' => $periodeId]);

        $response = $this->post(route('kuisioner.part1.store'), [
            'keluarga_kode' => 'KLG-TEST-0001',
            'is_alamat_sesuai' => '0',
            'alasan_tidak_sesuai' => 'Alamat tidak sesuai hasil verifikasi.',
            'action' => 'draft',
        ]);

        $response->assertRedirect(route('kuisioner.index'));
        $this->assertDatabaseHas('part1_keluarga', [
            'keluarga_periode_kode' => 'KLP-TEST-0001',
            'is_alamat_sesuai' => 0,
            'alasan_tidak_sesuai' => 'Alamat tidak sesuai hasil verifikasi.',
        ]);
    }
}
