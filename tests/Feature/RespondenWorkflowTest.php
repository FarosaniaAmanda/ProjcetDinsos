<?php

namespace Tests\Feature;

use App\Models\Keluarga;
use App\Models\KeluargaAnggota;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
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
        Schema::dropIfExists('periodes');
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
            $table->string('no_kk', 255);
            $table->string('nik', 255);
            $table->string('nama_lengkap');
            $table->string('status_keluarga');
            $table->string('kecamatan_id')->nullable();
            $table->string('kelurahan_id')->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('geotagging', 255)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->date('tanggal_entri')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->dateTime('tgl_awal')->nullable();
            $table->dateTime('tgl_akhir')->nullable();
            $table->string('status_periode');
            $table->timestamps();
        });

        Schema::create('keluarga_periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode')->unique();
            $table->string('keluarga_kode');
            $table->string('periode_kode');
            $table->string('status_kuisoner');
            $table->boolean('is_active')->default(true);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->unique(['keluarga_kode', 'periode_kode']);
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
            $table->text('alasan_tidak_sesuai')->nullable();
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
            $table->string('nik', 255);
            $table->string('nama_lengkap');
            $table->string('status_keluarga');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    public function test_valid_responden_is_saved_and_shown_in_the_list(): void
    {
        $this->selectPeriod('Aktif');
        $this->travelTo('2026-01-02 03:04:05');
        $this->seedWilayah();

        $response = $this->post(route('responden.store'), [
            'nomor_kk' => '3575020101010001',
            'nik_kepala_keluarga' => '3575020101010002',
            'nama_kepala_keluarga' => 'Kepala Keluarga Tes',
            'status_kepala_keluarga' => 'Kepala Keluarga',
            'kecamatan_id' => '3575020',
            'kelurahan_id' => '3575020001',
            'rt' => '001',
            'rw' => '002',
            'kode_pos' => '67127',
            'alamat_lengkap' => 'Jl. Mawar No. 5, RT 001/RW 002',
            'geotangging' => '-7.645321, 112.906543',
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

        $family = Keluarga::query()
            ->get()
            ->first(
                static fn (Keluarga $keluarga): bool => $keluarga->no_kk === '3575020101010001'
            );

        $this->assertNotNull($family);
        $this->assertSame('3575020101010002', $family->nik);
        $this->assertSame(
            '3575020101010001',
            Crypt::decryptString(substr(
                DB::table('keluargas')->where('id', $family->id)->value('no_kk'),
                strlen('enc:v1:')
            ))
        );
        $this->assertSame(
            '3575020101010002',
            Crypt::decryptString(substr(
                DB::table('keluargas')->where('id', $family->id)->value('nik'),
                strlen('enc:v1:')
            ))
        );

        $this->assertDatabaseHas('keluargas', [
            'id' => $family->id,
            'nama_lengkap' => 'Kepala Keluarga Tes',
            'status_keluarga' => 'Kepala Keluarga',
            'kecamatan_id' => '3575020',
            'kelurahan_id' => '3575020001',
            'rt' => '001',
            'rw' => '002',
            'kode_pos' => '67127',
            'alamat_lengkap' => 'Jl. Mawar No. 5, RT 001/RW 002',
        ]);

        $this->assertSame(
            '2026-01-02',
            $family->tanggal_entri
                ->toDateString()
        );

        $this->assertDatabaseHas('keluargas', [
            'id' => $family->id,
            'rt' => '001',
            'rw' => '002',
            'geotagging' => '-7.645321, 112.906543',
        ]);

        $this->assertDatabaseHas('keluarga_periodes', [
            'keluarga_kode' => $family->kode,
            'periode_kode' => 'PERIODE-AKTIF',
            'is_active' => true,
        ]);

        $this->assertDatabaseMissing('part1_keluarga', [
            'keluarga_periode_kode' => $family->kode,
        ]);

        $members = KeluargaAnggota::query()->get();
        $this->assertSame(
            ['3575020101010003', '3575020101010004'],
            $members->pluck('nik')->sort()->values()->all()
        );

        foreach (['3575020101010003', '3575020101010004'] as $memberNik) {
            $storedNik = DB::table('keluarga_anggotas')
                ->where('keluarga_kode', $family->kode)
                ->where('nama_lengkap', $memberNik === '3575020101010003' ? 'Anak Tes' : 'Istri Tes')
                ->value('nik');

            $this->assertSame(
                $memberNik,
                Crypt::decryptString(substr($storedNik, strlen('enc:v1:')))
            );
        }

        $this->getJson(route('responden.editData', $family->id))
            ->assertJsonPath('no_kk', '3575020101010001')
            ->assertJsonPath('nik_kepala_keluarga', '3575020101010002')
            ->assertJsonPath('anggota.1.nik', '3575020101010003');

        $this->withoutVite()
            ->get(route('responden.index'))
            ->assertSeeInOrder([
                'Kepala Keluarga Tes',
                'Anak Tes',
                'Istri Tes',
            ])
            ->assertSee('Aktif');

        $this->withoutVite()
            ->get(route('responden.index', ['search' => '3575020101010001']))
            ->assertSee('Kepala Keluarga Tes');

        $this->withoutVite()
            ->get(route('responden.index', ['search' => '3575020101010003']))
            ->assertSee('Kepala Keluarga Tes');
    }

    public function test_search_finds_respondent_beyond_the_first_page(): void
    {
        $this->selectPeriod('Aktif');
        $createdAt = now();

        foreach (range(1, 12) as $number) {
            $familyCode = 'KLG-'.str_pad((string) $number, 10, '0', STR_PAD_LEFT);

            DB::table('keluargas')->insert([
                'kode' => $familyCode,
                'no_kk' => '357502010101'.str_pad((string) $number, 4, '0', STR_PAD_LEFT),
                'nik' => '357502010102'.str_pad((string) $number, 4, '0', STR_PAD_LEFT),
                'nama_lengkap' => $number === 1 ? 'Nama Responden Terpencil' : 'Responden '.$number,
                'status_keluarga' => 'Kepala Keluarga',
                'created_by' => 'admin',
                'created_at' => $createdAt->copy()->subMinutes($number),
                'updated_at' => $createdAt->copy()->subMinutes($number),
            ]);

            DB::table('keluarga_periodes')->insert([
                'kode' => 'KLP-'.str_pad((string) $number, 10, '0', STR_PAD_LEFT),
                'keluarga_kode' => $familyCode,
                'periode_kode' => 'PERIODE-AKTIF',
                'status_kuisoner' => 'belum',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->withoutVite()
            ->get(route('responden.index', ['search' => 'Nama Responden Terpencil']))
            ->assertSee('Nama Responden Terpencil');
    }

    public function test_list_shows_status_for_the_selected_period_and_entry_date_after_address(): void
    {
        $this->seedWilayah();

        $periodeId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-2026',
            'nama' => '2026',
            'status_periode' => 'Aktif',
        ]);

        DB::table('keluargas')->insert([
            'kode' => 'KLG-PERIODE-AKTIF',
            'no_kk' => '3575020101010031',
            'nik' => '3575020101010032',
            'nama_lengkap' => 'Keluarga Aktif',
            'status_keluarga' => 'Kepala Keluarga',
            'tanggal_entri' => '2026-09-10',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('keluargas')->insert([
            'kode' => 'KLG-PERIODE-NONAKTIF',
            'no_kk' => '3575020101010041',
            'nik' => '3575020101010042',
            'nama_lengkap' => 'Keluarga Nonaktif',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('keluarga_periodes')->insert([
            [
                'kode' => 'KLP-PERIODE-AKTIF',
                'keluarga_kode' => 'KLG-PERIODE-AKTIF',
                'periode_kode' => 'PERIODE-2026',
                'status_kuisoner' => 'belum',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'KLP-PERIODE-SELESAI',
                'keluarga_kode' => 'KLG-PERIODE-NONAKTIF',
                'periode_kode' => 'PERIODE-SELESAI',
                'status_kuisoner' => 'belum',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->withSession(['periode_id' => $periodeId])
            ->withoutVite()
            ->get(route('responden.index'))
            ->assertSeeInOrder([
                'Alamat Lengkap',
                'Status',
                'Tanggal Entry',
                'Aksi',
            ])
            ->assertSee('Aktif')
            ->assertSee('10/09/2026')
            ->assertSee('Keluarga Aktif')
            ->assertDontSee('Keluarga Nonaktif');

        $periodeSelesaiId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-SELESAI',
            'nama' => 'Periode Selesai',
            'status_periode' => 'Selesai',
        ]);

        $this->withSession(['periode_id' => $periodeSelesaiId])
            ->withoutVite()
            ->get(route('responden.index'))
            ->assertSee('Non Aktif')
            ->assertSee('Keluarga Nonaktif')
            ->assertDontSee('Keluarga Aktif');
    }

    public function test_store_saves_rt_rw_and_geotagging(): void
    {
        $this->selectPeriod('Aktif');

        $response = $this->post(route('responden.store'), [
            'nomor_kk' => '3575020101010001',
            'nik_kepala_keluarga' => '3575020101010002',
            'nama_kepala_keluarga' => 'Kepala Keluarga Tes',
            'rt' => '001',
            'rw' => '002',
            'geotangging' => '-7.645321, 112.906543',
        ]);

        $response->assertRedirect(route('responden.index'));

        $family = Keluarga::query()
            ->get()
            ->first(
                static fn (Keluarga $keluarga): bool => $keluarga->no_kk === '3575020101010001'
            );

        $this->assertNotNull($family);
        $this->assertDatabaseHas('keluargas', [
            'id' => $family->id,
            'rt' => '001',
            'rw' => '002',
            'geotagging' => '-7.645321, 112.906543',
        ]);
    }

    public function test_duplicate_check_reports_existing_and_available_family_numbers(): void
    {
        $family = Keluarga::query()->create([
            'kode' => 'KLG-DUPLICATE-TEST',
            'no_kk' => '3575090901850009',
            'nik' => '3575090901850010',
            'nama_lengkap' => 'Kepala Keluarga Tes',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
        ]);
        $familyId = $family->id;
        KeluargaAnggota::query()->create([
            'kode' => 'ANG-DUPLICATE-TEST',
            'keluarga_kode' => 'KLG-DUPLICATE-TEST',
            'nik' => '3575090901850011',
            'nama_lengkap' => 'Anggota Keluarga Tes',
            'status_keluarga' => 'Anak',
        ]);

        $this->postJson(route('responden.cekDuplikat'), [
            'jenis' => 'kk',
            'nomor' => '3575090901850009',
        ])->assertExactJson([
            'exists' => true,
            'message' => 'Nomor KK sudah terdaftar.',
        ]);

        $this->postJson(route('responden.cekDuplikat'), [
            'jenis' => 'kk',
            'nomor' => '3575070701870007',
        ])->assertExactJson([
            'exists' => false,
            'message' => '',
        ]);

        $this->postJson(route('responden.cekDuplikat'), [
            'jenis' => 'kk',
            'nomor' => '3575090901850009',
            'ignore_id' => $familyId,
        ])->assertExactJson([
            'exists' => false,
            'message' => '',
        ]);

        $this->postJson(route('responden.cekDuplikat'), [
            'jenis' => 'nik',
            'nomor' => '3575090901850011',
            'ignore_id' => $familyId,
        ])->assertExactJson([
            'exists' => false,
            'message' => '',
        ]);
    }

    public function test_legacy_identifiers_are_readable_and_encrypted_once_when_edited(): void
    {
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => 'KLG-LEGACY-ENCRYPTION',
            'no_kk' => '3575090901850029',
            'nik' => '3575090901850030',
            'nama_lengkap' => 'Kepala Keluarga Lama',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $memberId = DB::table('keluarga_anggotas')->insertGetId([
            'kode' => 'ANG-LEGACY-ENCRYPTION',
            'keluarga_kode' => 'KLG-LEGACY-ENCRYPTION',
            'nik' => '3575090901850031',
            'nama_lengkap' => 'Anggota Lama',
            'status_keluarga' => 'Anak',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $family = Keluarga::findOrFail($familyId);
        $member = KeluargaAnggota::findOrFail($memberId);
        $this->assertSame('3575090901850029', $family->no_kk);
        $this->assertSame('3575090901850030', $family->nik);
        $this->assertSame('3575090901850031', $member->nik);

        $this->postJson(route('responden.cekDuplikat'), [
            'jenis' => 'kk',
            'nomor' => '3575090901850029',
        ])->assertExactJson([
            'exists' => true,
            'message' => 'Nomor KK sudah terdaftar.',
        ]);

        $this->postJson(route('responden.cekDuplikat'), [
            'jenis' => 'nik',
            'nomor' => '3575090901850031',
        ])->assertExactJson([
            'exists' => true,
            'message' => 'NIK sudah terdaftar.',
        ]);

        $family->no_kk = $family->no_kk;
        $family->nik = $family->nik;
        $family->save();
        $member->nik = $member->nik;
        $member->save();

        $storedFamily = DB::table('keluargas')->where('id', $familyId)->first();
        $storedMemberNik = DB::table('keluarga_anggotas')->where('id', $memberId)->value('nik');

        $this->assertTrue(str_starts_with($storedFamily->no_kk, 'enc:v1:'));
        $this->assertTrue(str_starts_with($storedFamily->nik, 'enc:v1:'));
        $this->assertTrue(str_starts_with($storedMemberNik, 'enc:v1:'));
        $this->assertFalse(str_starts_with($storedFamily->no_kk, 'enc:v1:enc:v1:'));
        $this->assertSame(
            '3575090901850029',
            Crypt::decryptString(substr($storedFamily->no_kk, strlen('enc:v1:')))
        );
        $this->assertSame(
            '3575090901850031',
            Crypt::decryptString(substr($storedMemberNik, strlen('enc:v1:')))
        );
    }

    public function test_responden_requires_a_period_and_allows_selecting_non_active_periods(): void
    {
        $this->withoutVite()
            ->get(route('responden.index'))
            ->assertRedirect(route('periode.pilih', ['tujuan' => 'responden.index']));

        $this->post(route('responden.store'), [
            'nomor_kk' => '3575020101010061',
            'nik_kepala_keluarga' => '3575020101010062',
            'nama_kepala_keluarga' => 'Responden Tanpa Periode',
        ])->assertRedirect(route('periode.pilih', ['tujuan' => 'responden.index']));

        $this->assertDatabaseCount('keluargas', 0);

        $periodeId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-BELUM-AKTIF',
            'nama' => 'Periode Belum Aktif',
            'status_periode' => 'Akan Datang',
        ]);

        $this->withoutVite()
            ->get(route('periode.pilih', ['tujuan' => 'responden.index']))
            ->assertSee('Periode Belum Aktif')
            ->assertSee('responden.index')
            ->assertSee((string) $periodeId);

        $this->withoutVite()
            ->get(route('periode.pilih', ['tujuan' => 'kuisioner.index']))
            ->assertSee('periode-option disabled');

        $this->post(route('periode.set'), [
            'periode_id' => $periodeId,
            'tujuan' => 'responden.index',
        ])
            ->assertRedirect(route('responden.index'))
            ->assertSessionHas('periode_id', $periodeId);

        $this->from(route('responden.index'))
            ->post(route('periode.set'), [
                'periode_id' => $periodeId,
                'tujuan' => 'kuisioner.index',
            ])
            ->assertSessionHasErrors('periode_id');
    }

    public function test_adding_a_respondent_in_a_non_active_period_starts_with_active_status(): void
    {
        $this->selectPeriod('Selesai');

        $this->post(route('responden.store'), [
            'nomor_kk' => '3575020101010051',
            'nik_kepala_keluarga' => '3575020101010052',
            'nama_kepala_keluarga' => 'Keluarga Periode Selesai',
        ])->assertRedirect(route('responden.index'));

        $family = Keluarga::query()
            ->get()
            ->first(
                static fn (Keluarga $keluarga): bool => $keluarga->no_kk === '3575020101010051'
            );

        $this->assertDatabaseHas('keluarga_periodes', [
            'keluarga_kode' => $family->kode,
            'periode_kode' => 'PERIODE-SELESAI',
            'is_active' => true,
        ]);

        $this->withoutVite()
            ->get(route('responden.index'))
            ->assertSee('Keluarga Periode Selesai')
            ->assertSee('Aktif');
    }

    public function test_questionnaire_selection_shows_only_respondents_linked_to_the_selected_period(): void
    {
        $this->selectPeriod('Aktif');

        DB::table('keluargas')->insert([
            [
                'kode' => 'KLG-LINKED',
                'no_kk' => '3575020101010071',
                'nik' => '3575020101010072',
                'nama_lengkap' => 'Responden Periode Ini',
                'status_keluarga' => 'Kepala Keluarga',
                'created_by' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'KLG-OTHER-PERIOD',
                'no_kk' => '3575020101010081',
                'nik' => '3575020101010082',
                'nama_lengkap' => 'Responden Periode Lain',
                'status_keluarga' => 'Kepala Keluarga',
                'created_by' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'KLG-UNLINKED',
                'no_kk' => '3575020101010091',
                'nik' => '3575020101010092',
                'nama_lengkap' => 'Responden Tanpa Relasi',
                'status_keluarga' => 'Kepala Keluarga',
                'created_by' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('keluarga_periodes')->insert([
            [
                'kode' => 'KLP-LINKED',
                'keluarga_kode' => 'KLG-LINKED',
                'periode_kode' => 'PERIODE-AKTIF',
                'status_kuisoner' => 'belum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'KLP-OTHER-PERIOD',
                'keluarga_kode' => 'KLG-OTHER-PERIOD',
                'periode_kode' => 'PERIODE-SELESAI',
                'status_kuisoner' => 'belum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->withoutVite()
            ->get(route('kuisioner.pilih-responden'))
            ->assertSee('Responden Periode Ini')
            ->assertDontSee('Responden Periode Lain')
            ->assertDontSee('Responden Tanpa Relasi');
    }

    public function test_questionnaire_url_rejects_a_selected_non_active_period(): void
    {
        $this->selectPeriod('Selesai');

        $this->get(route('kuisioner.index'))
            ->assertRedirect(route('periode.pilih', ['tujuan' => 'kuisioner.index']))
            ->assertSessionHas('error');
    }

    public function test_questionnaire_draft_uses_the_selected_family_period_relation_code(): void
    {
        $periodeId = $this->selectPeriod('Aktif');

        DB::table('keluargas')->insert([
            'kode' => 'KLG-PART1',
            'no_kk' => '3575020101010101',
            'nik' => '3575020101010102',
            'nama_lengkap' => 'Kepala Keluarga Periode',
            'status_keluarga' => 'Kepala Keluarga',
            'rt' => '001',
            'rw' => '002',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('keluarga_periodes')->insert([
            'kode' => 'KLP-PART1',
            'keluarga_kode' => 'KLG-PART1',
            'periode_kode' => 'PERIODE-AKTIF',
            'status_kuisoner' => 'belum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withSession(['periode_id' => $periodeId])
            ->withoutVite()
            ->get(route('kuisioner.part1', ['keluarga' => 'KLG-PART1']))
            ->assertSee('Kepala Keluarga Periode');

        $this->withSession(['periode_id' => $periodeId])
            ->post(route('kuisioner.part1.store'), [
                'keluarga_kode' => 'KLG-PART1',
                'nik' => '3575020101010102',
                'nama_kepala_keluarga' => 'Kepala Keluarga Periode',
                'no_kk' => '3575020101010101',
                'jml_keluarga' => '1',
                'provinsi' => 'Jawa Timur',
                'daerah' => 'Kota Pasuruan',
                'kecamatan' => 'Bugul Kidul',
                'kelurahan' => 'Bakalan',
                'kode_pos' => '67127',
                'rt_rw' => '001/002',
                'alamat_lengkap' => 'Jl. Mawar',
                'jalan_rumah' => 'Jl. Mawar',
                'nomor_rumah' => '5',
                'is_alamat_sesuai' => '1',
                'action' => 'draft',
            ])
            ->assertRedirect(route('kuisioner.index'))
            ->assertSessionHas('selected_keluarga_kode', 'KLP-PART1');

        $this->assertDatabaseHas('part1_keluarga', [
            'keluarga_periode_kode' => 'KLP-PART1',
            'status' => 'draft',
        ]);

        $draftId = DB::table('part1_keluarga')
            ->where('keluarga_periode_kode', 'KLP-PART1')
            ->value('id');

        $this->get(route('kuisioner.draft.resume', $draftId))
            ->assertRedirect(route('kuisioner.part1'))
            ->assertSessionHas('selected_keluarga_kode', 'KLP-PART1');

        Schema::create('part2_kondisi_rumahs', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode');
        });
        Schema::create('part3_keuangan_keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode');
        });
        Schema::create('part4_aset_keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode');
            $table->string('aset_keluarga');
        });
        Schema::create('part5_anggota_keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode');
            $table->string('keluarga_anggota_kode');
        });
        Schema::create('part5_foto_rumahs', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode');
            $table->string('jenis_foto');
        });

        $this->post(route('kuisioner.part1.store'), [
            'keluarga_kode' => 'KLG-PART1',
            'nik' => '3575020101010102',
            'nama_kepala_keluarga' => 'Kepala Keluarga Periode',
            'no_kk' => '3575020101010101',
            'jml_keluarga' => '1',
            'provinsi' => 'Jawa Timur',
            'daerah' => 'Kota Pasuruan',
            'kecamatan' => 'Bugul Kidul',
            'kelurahan' => 'Bakalan',
            'kode_pos' => '67127',
            'rt_rw' => '001/002',
            'alamat_lengkap' => 'Jl. Mawar',
            'jalan_rumah' => 'Jl. Mawar',
            'nomor_rumah' => '5',
            'is_alamat_sesuai' => '1',
            'action' => 'next',
        ])->assertRedirect(route('kuisioner.part2'));

        $this->assertDatabaseHas('part1_keluarga', [
            'id' => $draftId,
            'keluarga_periode_kode' => 'KLP-PART1',
            'current_part' => 2,
        ]);
    }

    public function test_responden_with_questionnaire_relation_cannot_be_deleted(): void
    {
        $this->selectPeriod('Aktif');
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => 'KLG-DELETE-GUARD',
            'no_kk' => '3575090901850200',
            'nik' => '3575090901850201',
            'nama_lengkap' => 'Kepala Keluarga Tes',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('keluarga_periodes')->insert([
            'kode' => 'KP-DELETE-GUARD',
            'keluarga_kode' => 'KLG-DELETE-GUARD',
            'periode_kode' => 'PERIODE-AKTIF',
            'status_kuisoner' => 'draft',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('part1_keluarga')->insert([
            'keluarga_periode_kode' => 'KP-DELETE-GUARD',
            'status' => 'draft',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->delete(route('responden.destroy', $familyId))
            ->assertRedirect(route('responden.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('keluargas', ['id' => $familyId]);
    }

    public function test_update_rejects_a_new_member_with_an_existing_family_nik(): void
    {
        $this->selectPeriod('Aktif');
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => 'KLG-SAME-FAMILY-NIK',
            'no_kk' => '3575090901850090',
            'nik' => '3575090901850091',
            'nama_lengkap' => 'Kepala Keluarga Tes',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $memberId = DB::table('keluarga_anggotas')->insertGetId([
            'kode' => 'ANG-SAME-FAMILY-NIK',
            'keluarga_kode' => 'KLG-SAME-FAMILY-NIK',
            'nik' => '3575090901850092',
            'nama_lengkap' => 'Anggota Terdaftar',
            'status_keluarga' => 'Anak',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->from(route('responden.index'))
            ->put(route('responden.update', $familyId), [
                'nomor_kk' => '3575090901850090',
                'nik_kepala_keluarga' => '3575090901850091',
                'nama_kepala_keluarga' => 'Kepala Keluarga Tes',
                'anggota' => [
                    [
                        'id' => $memberId,
                        'nik' => '3575090901850092',
                        'nama_lengkap' => 'Anggota Terdaftar',
                        'status_keluarga' => 'Anak',
                    ],
                    [
                        'nik' => '3575090901850092',
                        'nama_lengkap' => 'Anggota Baru',
                        'status_keluarga' => 'Anak',
                    ],
                ],
            ])
            ->assertSessionHasErrors('anggota');

        $this->assertDatabaseCount('keluarga_anggotas', 1);
    }

    public function test_update_allows_existing_numbers_and_preserves_members_for_draft_questionnaire(): void
    {
        $periodeId = $this->selectPeriod('Aktif');
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => 'KLG-DRAFT-TEST',
            'no_kk' => '3575090901850009',
            'nik' => '3575090901850010',
            'nama_lengkap' => 'Kepala Keluarga Lama',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $memberId = DB::table('keluarga_anggotas')->insertGetId([
            'kode' => 'ANG-DRAFT-TEST',
            'keluarga_kode' => 'KLG-DRAFT-TEST',
            'nik' => '3575090901850011',
            'nama_lengkap' => 'Anggota Lama',
            'status_keluarga' => 'Anak',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('keluarga_periodes')->insert([
            'kode' => 'KP-DRAFT-TEST',
            'keluarga_kode' => 'KLG-DRAFT-TEST',
            'periode_kode' => 'PERIODE-AKTIF',
            'status_kuisoner' => 'belum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('part1_keluarga')->insert([
            'keluarga_periode_kode' => 'KP-DRAFT-TEST',
            'status' => 'draft',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withSession(['periode_id' => $periodeId])
            ->getJson(route('responden.editData', $familyId))
            ->assertJsonPath('status_kuisioner', 'draft')
            ->assertJsonPath('anggota_terkunci', true)
            ->assertJsonPath('anggota_penambahan_terkunci', false);

        $this->withSession(['periode_id' => $periodeId])
            ->put(route('responden.update', $familyId), [
                'nomor_kk' => '3575090901850009',
                'nik_kepala_keluarga' => '3575090901850010',
                'nama_kepala_keluarga' => 'Kepala Keluarga Diperbarui',
                'anggota' => [
                    [
                        'id' => $memberId,
                        'nik' => '3575090901850011',
                        'nama_lengkap' => 'Anggota Lama',
                        'status_keluarga' => 'Anak',
                    ],
                    [
                        'nik' => '3575090901850012',
                        'nama_lengkap' => 'Anggota Baru',
                        'status_keluarga' => 'Anak',
                    ],
                ],
            ])->assertRedirect(route('responden.index'));

        $updatedFamily = Keluarga::findOrFail($familyId);
        $this->assertSame('3575090901850009', $updatedFamily->no_kk);
        $this->assertSame('3575090901850010', $updatedFamily->nik);
        $this->assertSame('Kepala Keluarga Diperbarui', $updatedFamily->nama_lengkap);
        $updatedMember = KeluargaAnggota::findOrFail($memberId);
        $this->assertSame('3575090901850011', $updatedMember->nik);
        $this->assertSame('Anggota Lama', $updatedMember->nama_lengkap);
        $this->assertDatabaseHas('keluarga_anggotas', [
            'keluarga_kode' => 'KLG-DRAFT-TEST',
            'nama_lengkap' => 'Anggota Baru',
        ]);
        $this->assertTrue(
            KeluargaAnggota::query()
                ->get()
                ->contains(
                    static fn (KeluargaAnggota $anggota): bool => $anggota->nik === '3575090901850012'
                        && $anggota->keluarga_kode === 'KLG-DRAFT-TEST'
                )
        );
    }

    public function test_update_allows_adding_members_when_draft_uses_legacy_family_code(): void
    {
        $periodeId = $this->selectPeriod('Aktif');
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => 'KLG-LEGACY-DRAFT',
            'no_kk' => '3575090901850100',
            'nik' => '3575090901850101',
            'nama_lengkap' => 'Kepala Keluarga Draft',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $memberId = DB::table('keluarga_anggotas')->insertGetId([
            'kode' => 'ANG-LEGACY-DRAFT',
            'keluarga_kode' => 'KLG-LEGACY-DRAFT',
            'nik' => '3575090901850102',
            'nama_lengkap' => 'Anggota Terdaftar',
            'status_keluarga' => 'Anak',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('keluarga_periodes')->insert([
            'kode' => 'KP-LEGACY-DRAFT',
            'keluarga_kode' => 'KLG-LEGACY-DRAFT',
            'periode_kode' => 'PERIODE-AKTIF',
            'status_kuisoner' => 'belum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('part1_keluarga')->insert([
            'keluarga_periode_kode' => 'KLG-LEGACY-DRAFT',
            'status' => 'draft',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withSession(['periode_id' => $periodeId])
            ->getJson(route('responden.editData', $familyId))
            ->assertJsonPath('status_kuisioner', 'draft')
            ->assertJsonPath('anggota_terkunci', true)
            ->assertJsonPath('anggota_penambahan_terkunci', false);

        $this->withSession(['periode_id' => $periodeId])
            ->put(route('responden.update', $familyId), [
                'nomor_kk' => '3575090901850100',
                'nik_kepala_keluarga' => '3575090901850101',
                'nama_kepala_keluarga' => 'Kepala Keluarga Draft',
                'anggota' => [
                    [
                        'id' => $memberId,
                        'nik' => '3575090901850102',
                        'nama_lengkap' => 'Anggota Terdaftar',
                        'status_keluarga' => 'Anak',
                    ],
                    [
                        'nik' => '3575090901850103',
                        'nama_lengkap' => 'Anggota Baru Draft',
                        'status_keluarga' => 'Anak',
                    ],
                ],
            ])
            ->assertRedirect(route('responden.index'));

        $this->assertDatabaseHas('keluarga_anggotas', [
            'id' => $memberId,
            'keluarga_kode' => 'KLG-LEGACY-DRAFT',
        ]);
        $this->assertDatabaseHas('keluarga_anggotas', [
            'keluarga_kode' => 'KLG-LEGACY-DRAFT',
            'nama_lengkap' => 'Anggota Baru Draft',
        ]);
        $this->assertTrue(
            KeluargaAnggota::query()
                ->get()
                ->contains(
                    static fn (KeluargaAnggota $anggota): bool => $anggota->nik === '3575090901850103'
                        && $anggota->keluarga_kode === 'KLG-LEGACY-DRAFT'
                )
        );
    }

    public function test_update_uses_questionnaire_status_from_the_selected_period(): void
    {
        $periodeDraftId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-DRAFT-SELECTED',
            'nama' => 'Periode Draft',
            'status_periode' => 'Aktif',
        ]);
        $periodeApprovedId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-APPROVED-SELECTED',
            'nama' => 'Periode Approved',
            'status_periode' => 'Aktif',
        ]);
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => 'KLG-MULTI-PERIOD-TEST',
            'no_kk' => '3575090901880010',
            'nik' => '3575090901880011',
            'nama_lengkap' => 'Kepala Keluarga Periode',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $memberId = DB::table('keluarga_anggotas')->insertGetId([
            'kode' => 'ANG-MULTI-PERIOD-TEST',
            'keluarga_kode' => 'KLG-MULTI-PERIOD-TEST',
            'nik' => '3575090901880012',
            'nama_lengkap' => 'Anggota Terdaftar',
            'status_keluarga' => 'Anak',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('keluarga_periodes')->insert([
            [
                'kode' => 'KP-MULTI-PERIOD-DRAFT',
                'keluarga_kode' => 'KLG-MULTI-PERIOD-TEST',
                'periode_kode' => 'PERIODE-DRAFT-SELECTED',
                'status_kuisoner' => 'belum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'KP-MULTI-PERIOD-APPROVED',
                'keluarga_kode' => 'KLG-MULTI-PERIOD-TEST',
                'periode_kode' => 'PERIODE-APPROVED-SELECTED',
                'status_kuisoner' => 'belum',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
        DB::table('part1_keluarga')->insert([
            [
                'keluarga_periode_kode' => 'KP-MULTI-PERIOD-DRAFT',
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keluarga_periode_kode' => 'KP-MULTI-PERIOD-APPROVED',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->withSession(['periode_id' => $periodeDraftId])
            ->getJson(route('responden.editData', $familyId))
            ->assertJsonPath('status_kuisioner', 'draft')
            ->assertJsonPath('anggota_penambahan_terkunci', false);

        $this->withSession(['periode_id' => $periodeDraftId])
            ->put(route('responden.update', $familyId), [
                'nomor_kk' => '3575090901880010',
                'nik_kepala_keluarga' => '3575090901880011',
                'nama_kepala_keluarga' => 'Kepala Keluarga Periode',
                'anggota' => [
                    [
                        'id' => $memberId,
                        'nik' => '3575090901880012',
                        'nama_lengkap' => 'Anggota Terdaftar',
                        'status_keluarga' => 'Anak',
                    ],
                    [
                        'nik' => '3575090901880013',
                        'nama_lengkap' => 'Anggota Baru Draft',
                        'status_keluarga' => 'Anak',
                    ],
                ],
            ])
            ->assertRedirect(route('responden.index'));

        $this->withSession(['periode_id' => $periodeApprovedId])
            ->getJson(route('responden.editData', $familyId))
            ->assertJsonPath('status_kuisioner', 'approved')
            ->assertJsonPath('anggota_penambahan_terkunci', true);

        $this->withSession(['periode_id' => $periodeApprovedId])
            ->put(route('responden.update', $familyId), [
                'nomor_kk' => '3575090901880010',
                'nik_kepala_keluarga' => '3575090901880011',
                'nama_kepala_keluarga' => 'Kepala Keluarga Periode',
                'anggota' => [],
            ])
            ->assertRedirect(route('responden.index'));

        $this->assertDatabaseHas('keluarga_anggotas', [
            'id' => $memberId,
            'keluarga_kode' => 'KLG-MULTI-PERIOD-TEST',
        ]);

        $this->withSession(['periode_id' => $periodeApprovedId])
            ->put(route('responden.update', $familyId), [
                'nomor_kk' => '3575090901880010',
                'nik_kepala_keluarga' => '3575090901880011',
                'nama_kepala_keluarga' => 'Kepala Keluarga Periode',
                'anggota' => [
                    [
                        'id' => $memberId,
                        'nik' => '3575090901880012',
                        'nama_lengkap' => 'Anggota Terdaftar',
                        'status_keluarga' => 'Anak',
                    ],
                    [
                        'nik' => '3575090901880014',
                        'nama_lengkap' => 'Anggota Baru Ditolak',
                        'status_keluarga' => 'Anak',
                    ],
                ],
            ])
            ->assertSessionHasErrors('anggota');

        $this->assertDatabaseHas('keluarga_anggotas', [
            'id' => $memberId,
            'keluarga_kode' => 'KLG-MULTI-PERIOD-TEST',
        ]);
        $this->assertDatabaseHas('keluarga_anggotas', [
            'keluarga_kode' => 'KLG-MULTI-PERIOD-TEST',
        ]);
        $this->assertTrue(
            KeluargaAnggota::query()
                ->get()
                ->contains(
                    static fn (KeluargaAnggota $anggota): bool => $anggota->nik === '3575090901880013'
                        && $anggota->keluarga_kode === 'KLG-MULTI-PERIOD-TEST'
                )
        );
        $this->assertFalse(
            KeluargaAnggota::query()
                ->get()
                ->contains(
                    static fn (KeluargaAnggota $anggota): bool => $anggota->nik === '3575090901880014'
                        && $anggota->keluarga_kode === 'KLG-MULTI-PERIOD-TEST'
                )
        );
    }

    public function test_update_rejects_numbers_used_by_another_family(): void
    {
        $this->selectPeriod('Aktif');
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => 'KLG-CURRENT-TEST',
            'no_kk' => '3575090901850009',
            'nik' => '3575090901850010',
            'nama_lengkap' => 'Kepala Keluarga Saat Ini',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('keluargas')->insert([
            'kode' => 'KLG-OTHER-TEST',
            'no_kk' => '3575090901850020',
            'nik' => '3575090901850021',
            'nama_lengkap' => 'Keluarga Lain',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('part1_keluarga')->insert([
            'keluarga_periode_kode' => 'KLG-CURRENT-TEST',
            'status' => 'menunggu_verifikasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->from(route('responden.index'))
            ->put(route('responden.update', $familyId), [
                'nomor_kk' => '3575090901850009',
                'nik_kepala_keluarga' => '3575090901850021',
                'nama_kepala_keluarga' => 'Kepala Keluarga Saat Ini',
                'anggota' => [],
            ])
            ->assertSessionHasErrors('nik_kepala_keluarga');

        $this->from(route('responden.index'))
            ->put(route('responden.update', $familyId), [
                'nomor_kk' => '3575090901850020',
                'nik_kepala_keluarga' => '3575090901850030',
                'nama_kepala_keluarga' => 'Kepala Keluarga Saat Ini',
                'anggota' => [],
            ])
            ->assertSessionHasErrors('nomor_kk');

        $this->assertSame('3575090901850010', Keluarga::findOrFail($familyId)->nik);
    }

    public function test_update_preserves_existing_members_for_pending_verification(): void
    {
        $this->selectPeriod('Aktif');
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => 'KLG-PENDING-TEST',
            'no_kk' => '3575090901850030',
            'nik' => '3575090901850031',
            'nama_lengkap' => 'Kepala Keluarga Menunggu',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $memberId = DB::table('keluarga_anggotas')->insertGetId([
            'kode' => 'ANG-PENDING-TEST',
            'keluarga_kode' => 'KLG-PENDING-TEST',
            'nik' => '3575090901850032',
            'nama_lengkap' => 'Anggota Lama',
            'status_keluarga' => 'Anak',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('part1_keluarga')->insert([
            'keluarga_periode_kode' => 'KLG-PENDING-TEST',
            'status' => 'menunggu_verifikasi',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->put(route('responden.update', $familyId), [
            'nomor_kk' => '3575090901850030',
            'nik_kepala_keluarga' => '3575090901850031',
            'nama_kepala_keluarga' => 'Kepala Keluarga Menunggu',
            'anggota' => [],
        ])->assertRedirect(route('responden.index'));

        $this->assertDatabaseHas('keluarga_anggotas', [
            'id' => $memberId,
            'keluarga_kode' => 'KLG-PENDING-TEST',
            'nik' => '3575090901850032',
        ]);
    }

    public function test_update_rejects_adding_members_for_waiting_or_approved_questionnaires(): void
    {
        $periodeId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-TERKUNCI',
            'nama' => '2026',
            'status_periode' => 'Aktif',
        ]);

        $this->withSession(['periode_id' => $periodeId]);

        foreach ([
            'Menunggu Verifikasi' => 'WAITING',
            'approved' => 'APPROVED',
        ] as $status => $suffix) {
            $familyCode = 'KLG-'.$suffix.'-TEST';
            $familyId = DB::table('keluargas')->insertGetId([
                'kode' => $familyCode,
                'no_kk' => '357509090186'.($suffix === 'WAITING' ? '0001' : '0002'),
                'nik' => '357509090186'.($suffix === 'WAITING' ? '0011' : '0012'),
                'nama_lengkap' => 'Kepala Keluarga '.$suffix,
                'status_keluarga' => 'Kepala Keluarga',
                'created_by' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $memberId = DB::table('keluarga_anggotas')->insertGetId([
                'kode' => 'ANG-'.$suffix.'-EXISTING',
                'keluarga_kode' => $familyCode,
                'nik' => '357509090187'.($suffix === 'WAITING' ? '0001' : '0002'),
                'nama_lengkap' => 'Anggota Terdaftar '.$suffix,
                'status_keluarga' => 'Anak',
                'created_by' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $familyPeriodCode = 'KP-'.$suffix.'-TEST';

            DB::table('keluarga_periodes')->insert([
                'kode' => $familyPeriodCode,
                'keluarga_kode' => $familyCode,
                'periode_kode' => 'PERIODE-TERKUNCI',
                'status_kuisoner' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('part1_keluarga')->insert([
                'keluarga_periode_kode' => $familyPeriodCode,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->getJson(route('responden.editData', $familyId))
                ->assertOk()
                ->assertJsonPath('anggota_penambahan_terkunci', true);

            $newMemberNik = '357509090188'.($suffix === 'WAITING' ? '0001' : '0002');

            $this->put(route('responden.update', $familyId), [
                'nomor_kk' => '357509090186'.($suffix === 'WAITING' ? '0001' : '0002'),
                'nik_kepala_keluarga' => '357509090186'.($suffix === 'WAITING' ? '0011' : '0012'),
                'nama_kepala_keluarga' => 'Kepala Keluarga '.$suffix,
                'anggota' => [
                    [
                        'nik' => $newMemberNik,
                        'nama_lengkap' => 'Anggota Baru '.$suffix,
                        'status_keluarga' => 'Anak',
                    ],
                ],
            ])->assertSessionHasErrors('anggota');

            $this->assertDatabaseHas('keluarga_anggotas', [
                'id' => $memberId,
                'keluarga_kode' => $familyCode,
            ]);
            $this->assertDatabaseMissing('keluarga_anggotas', [
                'keluarga_kode' => $familyCode,
                'nik' => $newMemberNik,
            ]);
        }
    }

    public function test_update_allows_adding_members_to_a_rejected_questionnaire_without_removing_registered_members(): void
    {
        $periodeId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-DITOLAK',
            'nama' => '2026',
            'status_periode' => 'Aktif',
        ]);
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => 'KLG-REJECTED-TEST',
            'no_kk' => '3575090901890001',
            'nik' => '3575090901890002',
            'nama_lengkap' => 'Kepala Keluarga Ditolak',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $memberId = DB::table('keluarga_anggotas')->insertGetId([
            'kode' => 'ANG-REJECTED-EXISTING',
            'keluarga_kode' => 'KLG-REJECTED-TEST',
            'nik' => '3575090901890003',
            'nama_lengkap' => 'Anggota Terdaftar',
            'status_keluarga' => 'Anak',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('keluarga_periodes')->insert([
            'kode' => 'KP-REJECTED-TEST',
            'keluarga_kode' => 'KLG-REJECTED-TEST',
            'periode_kode' => 'PERIODE-DITOLAK',
            'status_kuisoner' => 'ditolak',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('part1_keluarga')->insert([
            'keluarga_periode_kode' => 'KP-REJECTED-TEST',
            'status' => 'ditolak',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withSession(['periode_id' => $periodeId])
            ->put(route('responden.update', $familyId), [
                'nomor_kk' => '3575090901890001',
                'nik_kepala_keluarga' => '3575090901890002',
                'nama_kepala_keluarga' => 'Kepala Keluarga Ditolak',
                'anggota' => [
                    [
                        'id' => $memberId,
                        'nik' => '3575090901890003',
                        'nama_lengkap' => 'Anggota Terdaftar',
                        'status_keluarga' => 'Anak',
                    ],
                    [
                        'nik' => '3575090901890004',
                        'nama_lengkap' => 'Anggota Baru',
                        'status_keluarga' => 'Anak',
                    ],
                ],
            ])
            ->assertRedirect(route('responden.index'));

        $this->assertDatabaseHas('keluarga_anggotas', [
            'id' => $memberId,
            'keluarga_kode' => 'KLG-REJECTED-TEST',
        ]);
        $this->assertDatabaseHas('keluarga_anggotas', [
            'keluarga_kode' => 'KLG-REJECTED-TEST',
        ]);
        $this->assertTrue(
            KeluargaAnggota::query()
                ->get()
                ->contains(
                    static fn (KeluargaAnggota $anggota): bool => $anggota->nik === '3575090901890004'
                        && $anggota->keluarga_kode === 'KLG-REJECTED-TEST'
                )
        );
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

    public function test_index_shows_statistics_and_status_for_the_selected_period_without_back_button(): void
    {
        $this->selectPeriod('Aktif');
        $this->createFamilyInPeriod('KLG-STAT-ACTIVE', 'PERIODE-AKTIF', true);
        $this->createFamilyInPeriod('KLG-STAT-INACTIVE', 'PERIODE-AKTIF', false);

        $this->withoutVite()
            ->get(route('responden.index', ['status' => 'semua']))
            ->assertViewHas('totalResponden', 2)
            ->assertViewHas('respondenAktif', 1)
            ->assertViewHas('respondenNonaktif', 1)
            ->assertSee('Total Responden')
            ->assertSee('Responden Aktif')
            ->assertSee('Responden Nonaktif')
            ->assertSee('Aktif')
            ->assertSee('Non Aktif')
            ->assertDontSee('Kembali');
    }

    public function test_status_update_only_changes_the_selected_period_relation(): void
    {
        $periodeId = $this->selectPeriod('Aktif');
        DB::table('periodes')->insert([
            'kode' => 'PERIODE-LAIN',
            'nama' => 'Periode Lain',
            'status_periode' => 'Selesai',
        ]);
        $familyId = $this->createFamilyInPeriod('KLG-STATUS-PERIOD', 'PERIODE-AKTIF', true);
        DB::table('keluarga_periodes')->insert([
            'kode' => 'KLP-STATUS-PERIOD-OTHER',
            'keluarga_kode' => 'KLG-STATUS-PERIOD',
            'periode_kode' => 'PERIODE-LAIN',
            'status_kuisoner' => 'belum',
            'is_active' => true,
        ]);

        $this->withSession(['periode_id' => $periodeId])
            ->patchJson(route('responden.status.update', $familyId), [
                'is_active' => false,
            ])
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonPath('is_active', false);

        $this->assertDatabaseHas('keluarga_periodes', [
            'keluarga_kode' => 'KLG-STATUS-PERIOD',
            'periode_kode' => 'PERIODE-AKTIF',
            'is_active' => false,
        ]);
        $this->assertDatabaseHas('keluarga_periodes', [
            'keluarga_kode' => 'KLG-STATUS-PERIOD',
            'periode_kode' => 'PERIODE-LAIN',
            'is_active' => true,
        ]);
    }

    public function test_invalid_status_update_returns_validation_error_without_changing_database_state(): void
    {
        $this->selectPeriod('Aktif');
        $familyId = $this->createFamilyInPeriod('KLG-INVALID-STATUS', 'PERIODE-AKTIF');

        $this->patchJson(route('responden.status.update', $familyId), [
            'is_active' => 'invalid',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('is_active');

        $this->assertDatabaseHas('keluarga_periodes', [
            'keluarga_kode' => 'KLG-INVALID-STATUS',
            'periode_kode' => 'PERIODE-AKTIF',
            'is_active' => true,
        ]);
    }

    public function test_bulk_status_update_only_changes_respondents_matching_all_filters(): void
    {
        $this->selectPeriod('Aktif');
        $this->seedWilayah();
        $targetOneId = $this->createFamilyInPeriod(
            'KLG-BULK-TARGET-1',
            'PERIODE-AKTIF',
            true,
            'Target Satu',
            '3575020',
            '3575020001'
        );
        $targetTwoId = $this->createFamilyInPeriod(
            'KLG-BULK-TARGET-2',
            'PERIODE-AKTIF',
            true,
            'Target Dua',
            '3575020',
            '3575020001'
        );
        $outsideId = $this->createFamilyInPeriod(
            'KLG-BULK-OUTSIDE',
            'PERIODE-AKTIF',
            true,
            'Target Luar',
            '3575021',
            '3575021001'
        );

        $this->patch(route('responden.status.massal'), [
            'target_status' => 'nonaktif',
            'status' => 'semua',
            'kecamatan_id' => '3575020',
            'kelurahan_id' => '3575020001',
            'search' => 'Target',
        ])->assertRedirect();

        $this->assertDatabaseHas('keluarga_periodes', [
            'keluarga_kode' => 'KLG-BULK-TARGET-1',
            'periode_kode' => 'PERIODE-AKTIF',
            'is_active' => false,
        ]);
        $this->assertDatabaseHas('keluarga_periodes', [
            'keluarga_kode' => 'KLG-BULK-TARGET-2',
            'periode_kode' => 'PERIODE-AKTIF',
            'is_active' => false,
        ]);
        $this->assertDatabaseHas('keluarga_periodes', [
            'keluarga_kode' => 'KLG-BULK-OUTSIDE',
            'periode_kode' => 'PERIODE-AKTIF',
            'is_active' => true,
        ]);
        $this->assertNotSame($targetOneId, $outsideId);
        $this->assertNotSame($targetTwoId, $outsideId);

        $this->withoutVite()
            ->get(route('responden.index', [
                'status' => 'nonaktif',
                'kecamatan_id' => '3575020',
                'kelurahan_id' => '3575020001',
                'search' => 'Target',
            ]))
            ->assertSee('Target Satu')
            ->assertSee('Target Dua')
            ->assertDontSee('Target Luar');
    }

    public function test_import_is_idempotent_and_only_adds_relations_to_the_selected_period(): void
    {
        $targetPeriodId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-TARGET',
            'nama' => 'Periode Target',
            'status_periode' => 'Aktif',
        ]);
        DB::table('periodes')->insert([
            'kode' => 'PERIODE-SOURCE',
            'nama' => 'Periode Sumber',
            'status_periode' => 'Selesai',
        ]);
        $this->createFamilyInPeriod('KLG-IMPORT-SOURCE', 'PERIODE-SOURCE');
        DB::table('keluarga_anggotas')->insert([
            'kode' => 'ANG-IMPORT-SOURCE',
            'keluarga_kode' => 'KLG-IMPORT-SOURCE',
            'nik' => '3575020101010003',
            'nama_lengkap' => 'Anggota Import',
            'status_keluarga' => 'Anak',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withSession(['periode_id' => $targetPeriodId])
            ->post(route('responden.ambilData'), ['periode_sumber' => 'PERIODE-SOURCE'])
            ->assertRedirect(route('responden.index', ['imported_from' => 'PERIODE-SOURCE']));

        $this->assertDatabaseHas('keluarga_periodes', [
            'keluarga_kode' => 'KLG-IMPORT-SOURCE',
            'periode_kode' => 'PERIODE-TARGET',
            'is_active' => true,
        ]);

        $this->post(route('responden.ambilData'), ['periode_sumber' => 'PERIODE-SOURCE'])
            ->assertRedirect(route('responden.index', ['imported_from' => 'PERIODE-SOURCE']));

        $this->assertSame(
            1,
            DB::table('keluarga_periodes')
                ->where('keluarga_kode', 'KLG-IMPORT-SOURCE')
                ->where('periode_kode', 'PERIODE-TARGET')
                ->count()
        );
        $this->assertSame(
            1,
            DB::table('keluarga_periodes')
                ->where('keluarga_kode', 'KLG-IMPORT-SOURCE')
                ->where('periode_kode', 'PERIODE-SOURCE')
                ->count()
        );

        $this->withoutVite()
            ->get(route('responden.index', ['imported_from' => 'PERIODE-SOURCE']))
            ->assertSee('Data dari Periode Sumber')
            ->assertSee('Responden Tes')
            ->assertSee('Anggota Import');
    }

    private function createFamilyInPeriod(
        string $familyCode,
        string $periodCode,
        bool $isActive = true,
        string $name = 'Responden Tes',
        ?string $kecamatanId = null,
        ?string $kelurahanId = null
    ): int {
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => $familyCode,
            'no_kk' => '3575020101010001',
            'nik' => '3575020101010002',
            'nama_lengkap' => $name,
            'status_keluarga' => 'Kepala Keluarga',
            'kecamatan_id' => $kecamatanId,
            'kelurahan_id' => $kelurahanId,
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('keluarga_periodes')->insert([
            'kode' => 'KLP-'.$familyCode.'-'.$periodCode,
            'keluarga_kode' => $familyCode,
            'periode_kode' => $periodCode,
            'status_kuisoner' => 'belum',
            'is_active' => $isActive,
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return $familyId;
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

    private function selectPeriod(string $status): int
    {
        $periodeId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-'.strtoupper(str_replace(' ', '-', $status)),
            'nama' => 'Periode '.$status,
            'status_periode' => $status,
        ]);

        $this->withSession(['periode_id' => $periodeId]);

        return $periodeId;
    }
}
