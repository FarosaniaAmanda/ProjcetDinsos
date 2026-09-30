<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RespondenWorkflowTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('keluarga_anggotas');
        Schema::dropIfExists('keluargas');
        Schema::dropIfExists('part1_keluarga');
        Schema::dropIfExists('keluarga_periodes');
        Schema::dropIfExists('kelurahans');
        Schema::dropIfExists('kecamatans');

        Schema::create('kecamatans', function (Blueprint $table): void {
            $table->string('kecamatan_id')->primary();
            $table->string('deskripsi');
        });

        Schema::create('kelurahans', function (Blueprint $table): void {
            $table->string('kelurahan_id')->primary();
            $table->string('kecamatan_id');
            $table->string('deskripsi');
        });

        Schema::create('keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('no_kk', 18);
            $table->string('nik', 18);
            $table->string('nama_lengkap');
            $table->string('status_keluarga');
            $table->string('kecamatan_id')->nullable();
            $table->string('kelurahan_id')->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('keluarga_periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode')->unique();
            $table->string('keluarga_kode');
            $table->string('periode_kode');
            $table->string('status_kuisoner');
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

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
            $table->string('alamat_lengkap', 255)->nullable();
            $table->string('jalan_rumah')->nullable();
            $table->string('nomor_rumah')->nullable();
            $table->boolean('is_alamat_sesuai')->nullable();
            $table->string('geotangging')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedTinyInteger('current_part')->default(1);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('keluarga_anggotas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('keluarga_kode');
            $table->string('nik');
            $table->string('nama_lengkap');
            $table->string('status_keluarga');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function test_valid_responden_is_saved_and_shown_in_the_list(): void
    {
        $this->seedWilayah();

        $response = $this->post(route('responden.store'), [
            'nomor_kk' => '3575020101010001',
            'nik_kepala_keluarga' => '3575020101010002',
            'nama_kepala_keluarga' => 'Kepala Keluarga Tes',
            'status_kepala_keluarga' => 'Kepala Keluarga',
            'kecamatan_id' => '3575020',
            'kelurahan_id' => '3575020001',
            'kode_pos' => '67127',
            'alamat_lengkap' => 'Jl. Mawar No. 5, RT 001/RW 002',
            'anggota' => [
                [
                    'nik' => '3575020101010003',
                    'nama_lengkap' => 'Anak Tes',
                    'status_keluarga' => 'Anak',
                ],
                [
                    'nik' => '3575020101010004',
                    'nama_lengkap' => 'Istri Tes',
                    'status_keluarga' => 'Istri',
                ],
            ],
        ]);

        $response->assertRedirect(route('responden.index'));

        $this->assertDatabaseHas('keluargas', [
            'no_kk' => '3575020101010001',
            'nik' => '3575020101010002',
            'nama_lengkap' => 'Kepala Keluarga Tes',
            'status_keluarga' => 'Kepala Keluarga',
            'kecamatan_id' => '3575020',
            'kelurahan_id' => '3575020001',
            'kode_pos' => '67127',
            'alamat_lengkap' => 'Jl. Mawar No. 5, RT 001/RW 002',
        ]);

        $this->assertDatabaseHas('part1_keluarga', [
            'no_kk' => '3575020101010001',
            'alamat_lengkap' => 'Jl. Mawar No. 5, RT 001/RW 002',
        ]);

        $this->assertDatabaseHas('keluarga_anggotas', [
            'nik' => '3575020101010003',
            'nama_lengkap' => 'Anak Tes',
            'status_keluarga' => 'Anak',
        ]);

        $this->assertDatabaseMissing('keluarga_anggotas', [
            'nik' => '3575020101010002',
            'status_keluarga' => 'Kepala Keluarga',
        ]);

        $this->assertDatabaseHas('keluarga_anggotas', [
            'nik' => '3575020101010004',
            'nama_lengkap' => 'Istri Tes',
            'status_keluarga' => 'Istri',
        ]);

        $this->withoutVite()
            ->get(route('responden.index'))
            ->assertSeeInOrder([
                'Kepala Keluarga Tes',
                'Istri Tes',
                'Anak Tes',
            ]);
    }

    public function test_region_lookup_uses_cached_nominatim_coordinates(): void
    {
        $this->seedWilayah();
        Cache::flush();
        config([
            'services.geocoding.endpoint' => 'https://nominatim.test/search',
            'services.geocoding.user_agent' => 'ProjcetDinsos/1.0',
            'services.geocoding.email' => null,
        ]);

        Http::preventStrayRequests();
        Http::fake([
            'nominatim.test/search*' => Http::response([
                ['lat' => '-7.657', 'lon' => '112.899'],
            ]),
        ]);

        $path = route('responden.map', '3575020001');

        $this->getJson($path)
            ->assertOk()
            ->assertJsonPath('data.latitude', -7.657)
            ->assertJsonPath('data.longitude', 112.899);

        $this->getJson($path)->assertOk();

        Http::assertSentCount(1);
        Http::assertSent(fn (Request $request): bool => $request['q'] === 'Bakalan, Bugul Kidul, Kota Pasuruan, Jawa Timur, Indonesia'
            && $request->hasHeader('User-Agent', 'ProjcetDinsos/1.0')
        );
    }

    private function seedWilayah(): void
    {
        DB::table('kecamatans')->insert([
            'kecamatan_id' => '3575020',
            'deskripsi' => 'Bugul Kidul',
        ]);

        DB::table('kelurahans')->insert([
            'kelurahan_id' => '3575020001',
            'kecamatan_id' => '3575020',
            'deskripsi' => 'Bakalan',
        ]);
    }
}
