<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class RekapitulasiControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'part5_foto_rumahs',
            'part5_anggota_keluargas',
            'keluarga_anggotas',
            'part4_aset_keluargas',
            'part3_keuangan_keluargas',
            'part2_kondisi_rumahs',
            'part1_keluarga',
            'keluarga_periodes',
            'keluargas',
            'periodes',
            'users',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('role');
            $table->timestamps();
        });

        Schema::create('periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->dateTime('tgl_awal');
            $table->dateTime('tgl_akhir');
            $table->string('status_periode');
            $table->timestamps();
        });

        Schema::create('part1_keluarga', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode')->nullable();
            $table->string('created_by')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('status');
            $table->unsignedTinyInteger('current_part')->default(1);
            $table->timestamps();
        });

        Schema::create('keluarga_periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('keluarga_kode');
            $table->string('periode_kode');
        });

        Schema::create('keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
        });

        foreach ([
            'part2_kondisi_rumahs',
            'part3_keuangan_keluargas',
            'part4_aset_keluargas',
            'part5_foto_rumahs',
        ] as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->string('keluarga_periode_kode');
            });
        }

        Schema::create('keluarga_anggotas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode')->nullable();
            $table->string('keluarga_kode');
        });

        Schema::create('part5_anggota_keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode');
            $table->string('keluarga_anggota_kode');
        });
    }

    public function test_selected_survey_period_counts_only_its_unique_respondents_and_current_status(): void
    {
        $this->seedPetugasAndQuestionnaires();
        $periodeId = DB::table('periodes')->where('kode', 'SENSUS-MARET')->value('id');

        $this->actingAs($this->operator());
        $this->post(route('periode.set'), [
            'periode_id' => $periodeId,
            'tujuan' => 'rekapitulasi.index',
        ])
            ->assertRedirect(route('rekapitulasi.index'))
            ->assertSessionHas('periode_id', $periodeId);

        $this->get(route('rekapitulasi.index'))
            ->assertSeeText('Rekapitulasi')
            ->assertSeeText('Sensus Maret')
            ->assertSeeText('Menunggu Verifikasi')
            ->assertSeeText('Selesai')
            ->assertDontSeeText('status lainnya')
            ->assertSeeText('Reset Filter')
            ->assertViewHas('rows', function (Collection $rows): bool {
                $alya = $rows->firstWhere('nama_petugas', 'Alya Petugas');
                $this->assertSame([
                    'nama_petugas' => 'Alya Petugas',
                    'total_responden' => 5,
                    'status_counts' => [
                        'draft' => 0,
                        'disetujui' => 1,
                        'ditolak' => 1,
                        'menunggu_verifikasi' => 2,
                        'status:selesai' => 1,
                    ],
                ], $alya);
                $this->assertSame(5, array_sum($alya['status_counts']));

                $this->assertSame([
                    'nama_petugas' => 'Budi Petugas',
                    'total_responden' => 0,
                    'status_counts' => [
                        'draft' => 0,
                        'disetujui' => 0,
                        'ditolak' => 0,
                        'menunggu_verifikasi' => 0,
                        'status:selesai' => 0,
                    ],
                ], $rows->firstWhere('nama_petugas', 'Budi Petugas'));

                return true;
            });

        $aprilId = DB::table('periodes')->where('kode', 'SENSUS-APRIL')->value('id');
        $this->post(route('periode.set'), [
            'periode_id' => $aprilId,
            'tujuan' => 'rekapitulasi.index',
        ])->assertRedirect(route('rekapitulasi.index'));

        $this->get(route('rekapitulasi.index'))
            ->assertSeeText('Sensus April')
            ->assertViewHas('rows', function (Collection $rows): bool {
                $this->assertSame([
                    'nama_petugas' => 'Alya Petugas',
                    'total_responden' => 1,
                    'status_counts' => [
                        'draft' => 1,
                        'disetujui' => 0,
                        'ditolak' => 0,
                    ],
                ], $rows->firstWhere('nama_petugas', 'Alya Petugas'));

                return true;
            });
    }

    public function test_recap_redirects_to_period_picker_when_no_period_is_selected(): void
    {
        $this->actingAs($this->operator())
            ->get(route('rekapitulasi.index'))
            ->assertRedirect(route('periode.pilih', ['tujuan' => 'rekapitulasi.index']));
    }

    public function test_guest_is_redirected_and_field_staff_cannot_open_admin_recap(): void
    {
        $this->get(route('rekapitulasi.index'))
            ->assertRedirect(route('login'));

        $this->actingAs(new User(['role' => 'petugas']))
            ->get(route('rekapitulasi.index'))
            ->assertRedirect(route('periode.pilih', ['tujuan' => 'kuisioner.index']));
    }

    public function test_period_picker_includes_all_period_statuses_for_recap(): void
    {
        DB::table('periodes')->insert([
            [
                'kode' => 'SENSUS-SELESAI',
                'nama' => 'Sensus Selesai',
                'tgl_awal' => '2026-09-01',
                'tgl_akhir' => '2026-09-30',
                'status_periode' => 'Selesai',
            ],
            [
                'kode' => 'SENSUS-AKAN-DATANG',
                'nama' => 'Sensus Akan Datang',
                'tgl_awal' => '2027-01-01',
                'tgl_akhir' => '2027-01-31',
                'status_periode' => 'Akan Datang',
            ],
        ]);
        $upcomingPeriodId = DB::table('periodes')
            ->where('kode', 'SENSUS-AKAN-DATANG')
            ->value('id');

        $this->actingAs($this->operator())
            ->get(route('periode.pilih', ['tujuan' => 'rekapitulasi.index']))
            ->assertSeeText('Sensus Selesai')
            ->assertSeeText('Sensus Akan Datang');

        $this->post(route('periode.set'), [
            'periode_id' => $upcomingPeriodId,
            'tujuan' => 'rekapitulasi.index',
        ])
            ->assertRedirect(route('rekapitulasi.index'))
            ->assertSessionHas('periode_id', $upcomingPeriodId);

        $this->get(route('rekapitulasi.index'))
            ->assertSeeText('Sensus Akan Datang');
    }

    public function test_empty_period_displays_zero_for_existing_field_staff(): void
    {
        $periodeId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-KOSONG',
            'nama' => 'Periode Kosong',
            'tgl_awal' => '2026-03-01',
            'tgl_akhir' => '2026-03-31',
            'status_periode' => 'Selesai',
        ]);

        DB::table('users')->insert([
            [
                'name' => 'Alya Petugas',
                'email' => 'alya@example.test',
                'password' => 'secret',
                'role' => 'petugas',
            ],
        ]);

        $this->actingAs($this->operator());
        $this->post(route('periode.set'), [
            'periode_id' => $periodeId,
            'tujuan' => 'rekapitulasi.index',
        ])->assertRedirect(route('rekapitulasi.index'));

        $this->get(route('rekapitulasi.index'))
            ->assertSeeText('Alya Petugas')
            ->assertSeeText('0');
    }

    public function test_combined_search_location_and_status_filters_recompute_counts(): void
    {
        $this->seedPetugasAndQuestionnaires();
        $periodeId = DB::table('periodes')->where('kode', 'SENSUS-MARET')->value('id');
        $this->actingAs($this->operator());
        $this->post(route('periode.set'), [
            'periode_id' => $periodeId,
            'tujuan' => 'rekapitulasi.index',
        ]);

        $this->get(route('rekapitulasi.index', [
            'search' => 'Alya',
            'kecamatan' => 'Bugul Kidul',
            'kelurahan' => 'Kepel',
            'status' => 'ditolak',
        ]))
            ->assertSeeText('Alya Petugas')
            ->assertDontSeeText('Budi Petugas')
            ->assertViewHas('rows', function (Collection $rows): bool {
                $this->assertSame([
                    'nama_petugas' => 'Alya Petugas',
                    'total_responden' => 1,
                    'status_counts' => [
                        'draft' => 0,
                        'disetujui' => 0,
                        'ditolak' => 1,
                    ],
                ], $rows->first());

                return true;
            });
    }

    public function test_village_options_are_limited_to_selected_district(): void
    {
        $this->seedPetugasAndQuestionnaires();
        $periodeId = DB::table('periodes')->where('kode', 'SENSUS-MARET')->value('id');
        $this->actingAs($this->operator());
        $this->post(route('periode.set'), [
            'periode_id' => $periodeId,
            'tujuan' => 'rekapitulasi.index',
        ]);

        $this->get(route('rekapitulasi.index', [
            'kecamatan' => 'Bugul Kidul',
        ]))
            ->assertViewHas('kelurahanOptions', function (Collection $options): bool {
                $this->assertContains('Kepel', $options->all());
                $this->assertNotContains('Gadingrejo', $options->all());

                return true;
            });
    }

    public function test_search_without_matches_shows_empty_results_message(): void
    {
        $this->seedPetugasAndQuestionnaires();
        $periodeId = DB::table('periodes')->where('kode', 'SENSUS-MARET')->value('id');
        $this->actingAs($this->operator());
        $this->post(route('periode.set'), [
            'periode_id' => $periodeId,
            'tujuan' => 'rekapitulasi.index',
        ]);

        $this->get(route('rekapitulasi.index', [
            'search' => 'Tidak Ada Petugas',
        ]))
            ->assertSeeText('Tidak ada hasil rekap yang sesuai dengan pencarian dan filter.')
            ->assertViewHas('rows', fn (Collection $rows): bool => $rows->isEmpty());
    }

    private function seedPetugasAndQuestionnaires(): void
    {
        DB::table('periodes')->insert([
            [
                'kode' => 'SENSUS-MARET',
                'nama' => 'Sensus Maret',
                'tgl_awal' => '2026-03-01',
                'tgl_akhir' => '2026-03-31',
                'status_periode' => 'Selesai',
            ],
            [
                'kode' => 'SENSUS-APRIL',
                'nama' => 'Sensus April',
                'tgl_awal' => '2026-04-01',
                'tgl_akhir' => '2026-04-30',
                'status_periode' => 'Akan Datang',
            ],
        ]);

        DB::table('users')->insert([
            [
                'name' => 'Alya Petugas',
                'email' => 'alya@example.test',
                'password' => 'secret',
                'role' => 'petugas',
            ],
            [
                'name' => 'Budi Petugas',
                'email' => 'budi@example.test',
                'password' => 'secret',
                'role' => 'petugas',
            ],
        ]);

        foreach (['FAMILY-A', 'FAMILY-B', 'FAMILY-C', 'FAMILY-D', 'FAMILY-E', 'FAMILY-F'] as $familyCode) {
            DB::table('keluargas')->insert(['kode' => $familyCode]);
        }

        foreach ([
            'KLP-A' => 'FAMILY-A',
            'KLP-B' => 'FAMILY-B',
            'KLP-C' => 'FAMILY-C',
            'KLP-D' => 'FAMILY-D',
            'KLP-E' => 'FAMILY-E',
            'KLP-F' => 'FAMILY-F',
        ] as $periodCode => $familyCode) {
            DB::table('keluarga_periodes')->insert([
                'kode' => $periodCode,
                'keluarga_kode' => $familyCode,
                'periode_kode' => $periodCode === 'KLP-C' ? 'SENSUS-APRIL' : 'SENSUS-MARET',
            ]);
        }

        foreach ([
            ['KLP-A', '2026-03-05 10:00:00', 'draft', 1, 'Bugul Kidul', 'Kepel'],
            ['KLP-A', '2026-03-20 10:00:00', 'rejected', 1, 'Bugul Kidul', 'Kepel'],
            ['KLP-B', '2026-03-31 23:59:59', 'approved', 1, 'Bugul Kidul', 'Blandongan'],
            ['KLP-C', '2026-04-01 00:00:00', 'draft', 1, 'Gadingrejo', 'Gadingrejo'],
            ['KLP-D', '2026-03-06 00:00:00', 'selesai', 1, 'Gadingrejo', 'Gadingrejo'],
            ['KLP-E', '2026-03-10 12:00:00', 'pending', 1, 'Bugul Kidul', 'Krampyangan'],
            ['KLP-F', '2027-01-12 12:00:00', 'pending', 5, 'Bugul Kidul', 'Bugul Kidul'],
        ] as [$periodCode, $createdAt, $status, $currentPart, $kecamatan, $kelurahan]) {
            DB::table('part1_keluarga')->insert([
                'keluarga_periode_kode' => $periodCode,
                'created_by' => 'Alya Petugas',
                'kecamatan' => $kecamatan,
                'kelurahan' => $kelurahan,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'status' => $status,
                'current_part' => $currentPart,
            ]);
        }

        foreach ([
            'part2_kondisi_rumahs',
            'part3_keuangan_keluargas',
            'part4_aset_keluargas',
            'part5_foto_rumahs',
        ] as $tableName) {
            DB::table($tableName)->insert([
                'keluarga_periode_kode' => 'KLP-F',
            ]);
        }

        DB::table('keluarga_anggotas')->insert([
            'kode' => 'MEMBER-F',
            'keluarga_kode' => 'FAMILY-F',
        ]);
        DB::table('part5_anggota_keluargas')->insert([
            'keluarga_periode_kode' => 'KLP-F',
            'keluarga_anggota_kode' => 'MEMBER-F',
        ]);
    }

    private function operator(): User
    {
        $user = new User([
            'name' => 'Admin',
            'role' => 'operator',
        ]);
        $user->setAttribute('id', 1);

        return $user;
    }
}
