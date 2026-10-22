<?php

namespace Tests\Feature;

use App\Models\Keluarga;
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
            $table->string('no_kk', 18);
            $table->string('nik', 18);
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

        $this->assertDatabaseHas('keluargas', [
            'no_kk' => '3575020101010001',
            'nik' => '3575020101010002',
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
            Keluarga::query()
                ->where('no_kk', '3575020101010001')
                ->firstOrFail()
                ->tanggal_entri
                ->toDateString()
        );

        $this->assertDatabaseHas('keluargas', [
            'no_kk' => '3575020101010001',
            'rt' => '001',
            'rw' => '002',
            'geotagging' => '-7.645321, 112.906543',
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
            ])
            ->assertSee('Aktif');
    }

    public function test_search_finds_respondent_beyond_the_first_page(): void
    {
        $this->selectPeriod('Aktif');
        $createdAt = now();

        foreach (range(1, 12) as $number) {
            DB::table('keluargas')->insert([
                'kode' => 'KLG-'.str_pad((string) $number, 10, '0', STR_PAD_LEFT),
                'no_kk' => '357502010101'.str_pad((string) $number, 4, '0', STR_PAD_LEFT),
                'nik' => '357502010102'.str_pad((string) $number, 4, '0', STR_PAD_LEFT),
                'nama_lengkap' => $number === 1 ? 'Nama Responden Terpencil' : 'Responden '.$number,
                'status_keluarga' => 'Kepala Keluarga',
                'created_by' => 'admin',
                'created_at' => $createdAt->copy()->subMinutes($number),
                'updated_at' => $createdAt->copy()->subMinutes($number),
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
            ->assertSee('10/09/2026');

        $periodeSelesaiId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-SELESAI',
            'nama' => 'Periode Selesai',
            'status_periode' => 'Selesai',
        ]);

        $this->withSession(['periode_id' => $periodeSelesaiId])
            ->withoutVite()
            ->get(route('responden.index'))
            ->assertSee('Non Aktif');
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

        $this->assertDatabaseHas('keluargas', [
            'no_kk' => '3575020101010001',
            'rt' => '001',
            'rw' => '002',
            'geotagging' => '-7.645321, 112.906543',
        ]);
    }

    public function test_duplicate_check_reports_existing_and_available_family_numbers(): void
    {
        $familyId = DB::table('keluargas')->insertGetId([
            'kode' => 'KLG-DUPLICATE-TEST',
            'no_kk' => '3575090901850009',
            'nik' => '3575090901850010',
            'nama_lengkap' => 'Kepala Keluarga Tes',
            'status_keluarga' => 'Kepala Keluarga',
            'created_by' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('keluarga_anggotas')->insert([
            'kode' => 'ANG-DUPLICATE-TEST',
            'keluarga_kode' => 'KLG-DUPLICATE-TEST',
            'nik' => '3575090901850011',
            'nama_lengkap' => 'Anggota Keluarga Tes',
            'status_keluarga' => 'Anak',
            'created_at' => now(),
            'updated_at' => now(),
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

    public function test_adding_a_respondent_in_a_non_active_period_shows_non_active_status(): void
    {
        $this->selectPeriod('Selesai');

        $this->post(route('responden.store'), [
            'nomor_kk' => '3575020101010051',
            'nik_kepala_keluarga' => '3575020101010052',
            'nama_kepala_keluarga' => 'Keluarga Periode Selesai',
        ])->assertRedirect(route('responden.index'));

        $this->withoutVite()
            ->get(route('responden.index'))
            ->assertSee('Keluarga Periode Selesai')
            ->assertSee('Non Aktif');
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
        $this->selectPeriod('Aktif');
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
        DB::table('part1_keluarga')->insert([
            'keluarga_periode_kode' => 'KLG-DRAFT-TEST',
            'status' => 'draft',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->put(route('responden.update', $familyId), [
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

        $this->assertDatabaseHas('keluargas', [
            'id' => $familyId,
            'no_kk' => '3575090901850009',
            'nik' => '3575090901850010',
            'nama_lengkap' => 'Kepala Keluarga Diperbarui',
        ]);
        $this->assertDatabaseHas('keluarga_anggotas', [
            'id' => $memberId,
            'nik' => '3575090901850011',
            'nama_lengkap' => 'Anggota Lama',
        ]);
        $this->assertDatabaseHas('keluarga_anggotas', [
            'keluarga_kode' => 'KLG-DRAFT-TEST',
            'nik' => '3575090901850012',
            'nama_lengkap' => 'Anggota Baru',
        ]);
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
                'nomor_kk' => '3575090901850020',
                'nik_kepala_keluarga' => '3575090901850021',
                'nama_kepala_keluarga' => 'Kepala Keluarga Saat Ini',
                'anggota' => [],
            ])
            ->assertSessionHasErrors(['nomor_kk', 'nik_kepala_keluarga']);

        $this->assertDatabaseHas('keluargas', [
            'id' => $familyId,
            'nik' => '3575090901850010',
        ]);
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
            'menunggu_verifikasi' => 'WAITING',
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
            'nik' => '3575090901890004',
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
