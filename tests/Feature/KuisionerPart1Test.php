<?php

namespace Tests\Feature;

use App\Models\KeluargaPart1;
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
            'nik' => '3575020101010001',
            'no_kk' => '3575020101010002',
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

        $part1 = KeluargaPart1::query()
            ->where('keluarga_periode_kode', 'KLP-TEST-0001')
            ->firstOrFail();
        $storedIdentifiers = DB::table('part1_keluarga')
            ->where('id', $part1->id)
            ->first(['nik', 'no_kk']);

        $this->assertStringStartsWith('enc:v1:', $storedIdentifiers->nik);
        $this->assertStringStartsWith('enc:v1:', $storedIdentifiers->no_kk);
        $this->assertSame('3575020101010001', $part1->nik);
        $this->assertSame('3575020101010002', $part1->no_kk);

        $this->post(route('kuisioner.part1.store'), [
            'keluarga_kode' => 'KLG-TEST-0001',
            'nik' => '3575020101010003',
            'no_kk' => '3575020101010004',
            'action' => 'draft',
        ])->assertRedirect(route('kuisioner.index'));

        $updatedPart1 = KeluargaPart1::findOrFail($part1->id);

        $this->assertSame('3575020101010003', $updatedPart1->nik);
        $this->assertSame('3575020101010004', $updatedPart1->no_kk);
    }

    public function test_migration_encrypts_legacy_part1_identifiers_only_once(): void
    {
        Schema::dropIfExists('part1_keluarga');
        Schema::create('part1_keluarga', function (Blueprint $table): void {
            $table->id();
            $table->string('nik')->nullable();
            $table->string('no_kk')->nullable();
            $table->timestamps();
        });

        $id = DB::table('part1_keluarga')->insertGetId([
            'nik' => '3575020101010005',
            'no_kk' => '3575020101010006',
        ]);
        $migration = require database_path(
            'migrations/2026_10_28_041500_encrypt_part1_family_identifiers.php'
        );

        $migration->up();
        $encryptedIdentifiers = DB::table('part1_keluarga')
            ->where('id', $id)
            ->first(['nik', 'no_kk']);

        $this->assertSame('text', Schema::getColumnType('part1_keluarga', 'nik'));
        $this->assertSame('text', Schema::getColumnType('part1_keluarga', 'no_kk'));
        $this->assertStringStartsWith('enc:v1:', $encryptedIdentifiers->nik);
        $this->assertStringStartsWith('enc:v1:', $encryptedIdentifiers->no_kk);
        $this->assertSame('3575020101010005', KeluargaPart1::findOrFail($id)->nik);
        $this->assertSame('3575020101010006', KeluargaPart1::findOrFail($id)->no_kk);

        $migration->up();

        $this->assertDatabaseHas('part1_keluarga', [
            'id' => $id,
            'nik' => $encryptedIdentifiers->nik,
            'no_kk' => $encryptedIdentifiers->no_kk,
        ]);
    }

    public function test_identifier_search_matches_encrypted_part1_values(): void
    {
        Schema::dropIfExists('part1_keluarga');
        Schema::create('part1_keluarga', function (Blueprint $table): void {
            $table->id();
            $table->string('nik')->nullable();
            $table->string('no_kk')->nullable();
            $table->timestamps();
        });

        $part1 = KeluargaPart1::query()->create([
            'nik' => '3575020101010007',
            'no_kk' => '3575020101010008',
        ]);

        $this->assertSame(
            [$part1->id],
            KeluargaPart1::idsMatchingIdentifierSearch('01010007')
        );
        $this->assertSame(
            [$part1->id],
            KeluargaPart1::idsMatchingIdentifierValues('no_kk', ['3575020101010008'])
        );
    }
}
