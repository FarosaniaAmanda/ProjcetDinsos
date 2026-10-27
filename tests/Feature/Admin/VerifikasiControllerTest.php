<?php

namespace Tests\Feature\Admin;

use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Models\Keluarga;
use App\Models\KeluargaPart1;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class VerifikasiControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('keluarga_anggotas');
        Schema::dropIfExists('part1_keluarga');
        Schema::dropIfExists('keluarga_periodes');
        Schema::dropIfExists('keluargas');
        Schema::dropIfExists('periodes');

        Schema::create('periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->dateTime('tgl_awal');
            $table->dateTime('tgl_akhir');
            $table->string('status_periode');
            $table->timestamps();
        });

        Schema::create('keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode')->unique();
            $table->string('no_kk')->nullable();
            $table->string('nik')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('status_keluarga')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->string('geotagging')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->timestamps();
        });

        Schema::create('keluarga_periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode')->unique();
            $table->string('keluarga_kode');
            $table->string('periode_kode');
            $table->string('status_kuisoner')->nullable();
            $table->timestamps();
        });

        Schema::create('part1_keluarga', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode')->nullable();
            $table->string('nik')->nullable();
            $table->string('nama_kepala_keluarga')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('jml_keluarga')->nullable();
            $table->text('alamat_lengkap')->nullable();
            $table->boolean('is_alamat_sesuai')->nullable();
            $table->text('alasan_tidak_sesuai')->nullable();
            $table->string('geotangging')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedTinyInteger('current_part')->default(1);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('keluarga_anggotas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode')->nullable();
            $table->string('keluarga_kode');
            $table->string('nik')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('status_keluarga')->nullable();
            $table->timestamps();
        });

        foreach ([
            'part2_kondisi_rumahs',
            'part3_keuangan_keluargas',
            'part4_aset_keluargas',
            'part5_anggota_keluargas',
            'part5_foto_rumahs',
        ] as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->string('keluarga_periode_kode')->nullable();
                $table->string('keluarga_anggota_kode')->nullable();
            });
        }
    }

    public function test_detail_uses_the_family_and_questionnaire_from_the_selected_period(): void
    {
        DB::table('periodes')->insert([
            [
                'kode' => 'PERIODE-LAMA',
                'nama' => 'Periode Lama',
                'tgl_awal' => '2025-01-01 00:00:00',
                'tgl_akhir' => '2025-12-31 23:59:59',
                'status_periode' => 'nonaktif',
            ],
            [
                'kode' => 'PERIODE-AKTIF',
                'nama' => 'Periode Aktif',
                'tgl_awal' => '2026-01-01 00:00:00',
                'tgl_akhir' => '2026-12-31 23:59:59',
                'status_periode' => 'aktif',
            ],
        ]);

        DB::table('keluargas')->insert([
            [
                'kode' => 'KELUARGA-LAMA',
                'no_kk' => 'KK-SAMA',
                'nama_lengkap' => 'Keluarga Periode Lama',
                'rt' => '001',
                'rw' => '002',
                'geotagging' => '-7.100, 112.100',
            ],
            [
                'kode' => 'KELUARGA-AKTIF',
                'no_kk' => 'KK-SAMA',
                'nama_lengkap' => 'Keluarga Periode Aktif',
                'rt' => '007',
                'rw' => '012',
                'geotagging' => '-7.657, 112.899',
            ],
        ]);

        DB::table('keluarga_periodes')->insert([
            [
                'kode' => 'KODE-LAMA',
                'keluarga_kode' => 'KELUARGA-LAMA',
                'periode_kode' => 'PERIODE-LAMA',
            ],
            [
                'kode' => 'KODE-AKTIF',
                'keluarga_kode' => 'KELUARGA-AKTIF',
                'periode_kode' => 'PERIODE-AKTIF',
            ],
        ]);

        DB::table('part1_keluarga')->insert([
            [
                'keluarga_periode_kode' => 'KODE-LAMA',
                'no_kk' => 'KK-SAMA',
                'nama_kepala_keluarga' => 'Responden Lama',
                'jml_keluarga' => '2',
                'status' => 'pending',
            ],
            [
                'keluarga_periode_kode' => 'KODE-AKTIF',
                'no_kk' => 'KK-SAMA',
                'nama_kepala_keluarga' => 'Responden Aktif',
                'jml_keluarga' => '2',
                'status' => 'pending',
            ],
        ]);

        $data = (new TestableVerifikasiController)->verificationData();

        $this->assertCount(1, $data);
        $this->assertSame('Keluarga Periode Aktif', $data[0]['nama']);
        $this->assertSame('Periode Aktif', $data[0]['periode']);
        $this->assertSame('RT 007 / RW 012', $data[0]['rt_rw']);
        $this->assertSame('-7.657,112.899', $data[0]['geotangging']);
    }

    public function test_selected_non_active_period_is_used_by_verification_monitoring_and_reports(): void
    {
        DB::table('periodes')->insert([
            'kode' => 'PERIODE-AKTIF',
            'nama' => 'Periode Aktif',
            'tgl_awal' => '2026-01-01 00:00:00',
            'tgl_akhir' => '2026-12-31 23:59:59',
            'status_periode' => 'Aktif',
        ]);
        $periodeAkanDatangId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-AKAN-DATANG',
            'nama' => 'Periode Akan Datang',
            'tgl_awal' => '2027-01-01 00:00:00',
            'tgl_akhir' => '2027-12-31 23:59:59',
            'status_periode' => 'Akan Datang',
        ]);
        $periodeSelesaiId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-SELESAI',
            'nama' => 'Periode Selesai',
            'tgl_awal' => '2025-01-01 00:00:00',
            'tgl_akhir' => '2025-12-31 23:59:59',
            'status_periode' => 'Selesai',
        ]);

        DB::table('keluargas')->insert([
            [
                'kode' => 'KELUARGA-AKTIF',
                'no_kk' => 'KK-AKTIF',
                'nama_lengkap' => 'Responden Periode Aktif',
            ],
            [
                'kode' => 'KELUARGA-AKAN-DATANG',
                'no_kk' => 'KK-AKAN-DATANG',
                'nama_lengkap' => 'Responden Periode Akan Datang',
            ],
            [
                'kode' => 'KELUARGA-SELESAI',
                'no_kk' => 'KK-SELESAI',
                'nama_lengkap' => 'Responden Periode Selesai',
            ],
        ]);

        DB::table('keluarga_periodes')->insert([
            [
                'kode' => 'KP-AKTIF',
                'keluarga_kode' => 'KELUARGA-AKTIF',
                'periode_kode' => 'PERIODE-AKTIF',
            ],
            [
                'kode' => 'KP-AKAN-DATANG',
                'keluarga_kode' => 'KELUARGA-AKAN-DATANG',
                'periode_kode' => 'PERIODE-AKAN-DATANG',
            ],
            [
                'kode' => 'KP-SELESAI',
                'keluarga_kode' => 'KELUARGA-SELESAI',
                'periode_kode' => 'PERIODE-SELESAI',
            ],
        ]);

        DB::table('part1_keluarga')->insert([
            [
                'keluarga_periode_kode' => 'KP-AKTIF',
                'no_kk' => 'KK-AKTIF',
                'nama_kepala_keluarga' => 'Responden Periode Aktif',
                'jml_keluarga' => '1',
                'status' => 'approved',
            ],
            [
                'keluarga_periode_kode' => 'KP-AKAN-DATANG',
                'no_kk' => 'KK-AKAN-DATANG',
                'nama_kepala_keluarga' => 'Responden Periode Akan Datang',
                'jml_keluarga' => '1',
                'status' => 'approved',
            ],
            [
                'keluarga_periode_kode' => 'KP-SELESAI',
                'no_kk' => 'KK-SELESAI',
                'nama_kepala_keluarga' => 'Responden Periode Selesai',
                'jml_keluarga' => '1',
                'status' => 'approved',
            ],
        ]);

        foreach ([
            $periodeAkanDatangId => 'Responden Periode Akan Datang',
            $periodeSelesaiId => 'Responden Periode Selesai',
        ] as $periodeId => $expectedRespondent) {
            session(['periode_id' => $periodeId]);

            $verificationRows = (new TestableVerifikasiController)->verificationData();
            $monitoringRows = (new TestableMonitoringController)->monitoringData();
            $reportRows = (new TestableLaporanController)->reportRows();

            $this->assertSame(
                [$expectedRespondent],
                collect($verificationRows)->pluck('nama')->all()
            );
            $this->assertSame(
                [$expectedRespondent],
                collect($monitoringRows)->pluck('nama')->all()
            );
            $this->assertSame(
                [$expectedRespondent],
                $reportRows->pluck('nama')->all()
            );
        }
    }

    public function test_legacy_questionnaire_remains_visible_when_family_period_links_are_empty(): void
    {
        DB::table('periodes')->insert([
            'kode' => 'PERIODE-AKTIF',
            'nama' => 'Periode Aktif',
            'tgl_awal' => '2026-01-01 00:00:00',
            'tgl_akhir' => '2026-12-31 23:59:59',
            'status_periode' => 'aktif',
        ]);

        DB::table('keluargas')->insert([
            'kode' => 'KELUARGA-LEGACY',
            'no_kk' => 'KK-LEGACY',
            'nama_lengkap' => 'Keluarga Lama',
            'rt' => '003',
            'rw' => '004',
            'geotagging' => '-7.657, 112.899',
        ]);

        DB::table('part1_keluarga')->insert([
            'keluarga_periode_kode' => 'KELUARGA-LEGACY',
            'no_kk' => 'KK-LEGACY',
            'nama_kepala_keluarga' => 'Responden Lama',
            'jml_keluarga' => '1',
            'status' => 'pending',
        ]);

        $data = (new TestableVerifikasiController)->verificationData();

        $this->assertCount(1, $data);
        $this->assertSame('Keluarga Lama', $data[0]['nama']);
        $this->assertSame('RT 003 / RW 004', $data[0]['rt_rw']);
        $this->assertSame('-7.657,112.899', $data[0]['geotangging']);
    }

    public function test_submitted_and_reviewed_questionnaires_remain_in_the_verification_list(): void
    {
        DB::table('keluargas')->insert([
            [
                'kode' => 'KELUARGA-SELESAI',
                'no_kk' => 'KK-SELESAI',
                'nama_lengkap' => 'Keluarga Selesai',
            ],
            [
                'kode' => 'KELUARGA-APPROVED',
                'no_kk' => 'KK-APPROVED',
                'nama_lengkap' => 'Keluarga Approved',
            ],
            [
                'kode' => 'KELUARGA-REJECTED',
                'no_kk' => 'KK-REJECTED',
                'nama_lengkap' => 'Keluarga Rejected',
            ],
        ]);

        DB::table('part1_keluarga')->insert([
            [
                'keluarga_periode_kode' => 'KELUARGA-SELESAI',
                'no_kk' => 'KK-SELESAI',
                'nama_kepala_keluarga' => 'Responden Selesai',
                'jml_keluarga' => '1',
                'status' => 'selesai',
                'current_part' => 5,
            ],
            [
                'keluarga_periode_kode' => 'KELUARGA-APPROVED',
                'no_kk' => 'KK-APPROVED',
                'nama_kepala_keluarga' => 'Responden Approved',
                'jml_keluarga' => '1',
                'status' => 'approved',
                'current_part' => 5,
            ],
            [
                'keluarga_periode_kode' => 'KELUARGA-REJECTED',
                'no_kk' => 'KK-REJECTED',
                'nama_kepala_keluarga' => 'Responden Rejected',
                'jml_keluarga' => '1',
                'status' => 'rejected',
                'current_part' => 5,
            ],
        ]);

        $controller = new TestableVerifikasiController;
        $data = $controller->completedVerificationData();
        $pendingData = $controller->completedVerificationData('pending');

        $this->assertCount(3, $data);
        $this->assertSame(
            [
                'approved',
                'pending',
                'rejected',
            ],
            collect($data)->pluck('status')->sort()->values()->all()
        );
        $this->assertCount(1, $pendingData);
        $this->assertSame('Keluarga Selesai', $pendingData[0]['nama']);
    }

    public function test_address_mismatch_displays_its_reason_and_saved_location(): void
    {
        $item = new KeluargaPart1;
        $item->forceFill([
            'is_alamat_sesuai' => false,
            'alasan_tidak_sesuai' => 'Alamat domisili berbeda dari KTP.',
            'jml_keluarga' => 1,
            'current_part' => 1,
        ]);

        $keluarga = new Keluarga;
        $keluarga->forceFill([
            'rt' => '7',
            'rw' => '12',
            'geotagging' => '-7.657, 112.899',
        ]);

        $questions = collect(
            (new TestableVerifikasiController)->part1Questions($item, $keluarga)
        )->keyBy('question');

        $this->assertSame(
            'Tidak',
            $questions['Apakah alamat tempat tinggal saat ini sesuai dengan KK? *']['answer']
        );
        $this->assertSame(
            'Alamat domisili berbeda dari KTP.',
            $questions['Apakah alamat tempat tinggal saat ini sesuai dengan KK? *']['reason']
        );
        $this->assertSame('RT 007 / RW 012', $questions['RT / RW *']['answer']);
        $this->assertSame('-7.657,112.899', $questions['Lokasi Rumah / Geotagging']['answer']);
        $this->assertSame('map', $questions['Lokasi Rumah / Geotagging']['type']);
    }

    public function test_address_match_does_not_display_an_inapplicable_mismatch_reason(): void
    {
        $item = new KeluargaPart1;
        $item->forceFill([
            'is_alamat_sesuai' => true,
            'alasan_tidak_sesuai' => 'Alasan tidak semestinya tampil.',
            'jml_keluarga' => 1,
            'current_part' => 1,
        ]);

        $questions = collect(
            (new TestableVerifikasiController)->part1Questions($item, null)
        );

        $this->assertSame(
            false,
            $questions->contains(
                fn (array $question): bool => isset($question['reason'])
            )
        );
        $this->assertSame(
            'Kota Pasuruan',
            $questions->firstWhere('question', 'Kabupaten / Kota *')['answer']
        );
    }

    public function test_part1_questions_use_the_saved_values_in_form_order(): void
    {
        $item = new KeluargaPart1;
        $item->forceFill([
            'nik' => '3575010101010001',
            'nama_kepala_keluarga' => 'Nama Tersimpan',
            'no_kk' => '3575010101010002',
            'jml_keluarga' => '4',
            'provinsi' => 'Jawa Timur',
            'daerah' => 'Kota Pasuruan',
            'kecamatan' => 'Gadingrejo',
            'kelurahan' => 'Krapyakrejo',
            'kode_pos' => '67115',
            'alamat_lengkap' => 'Jl. Contoh No. 1',
            'jalan_rumah' => 'Jl. Contoh',
            'nomor_rumah' => '1',
            'is_alamat_sesuai' => true,
            'current_part' => 1,
        ]);

        $keluarga = new Keluarga;
        $keluarga->forceFill([
            'status_keluarga' => 'Kepala Keluarga',
            'rt' => '007',
            'rw' => '012',
        ]);

        $questions = (new TestableVerifikasiController)->part1Questions($item, $keluarga);

        $this->assertSame(
            [
                'NIK *',
                'Nama Kepala Keluarga *',
                'Status dalam Keluarga *',
                'Nomor KK *',
                'Jumlah Anggota Keluarga *',
                'Provinsi *',
                'Kabupaten / Kota *',
                'Kecamatan *',
                'Kelurahan *',
                'Kode Pos',
                'RT / RW *',
                'Alamat Lengkap *',
                'Jalan',
                'Nomor Rumah',
                'Apakah alamat tempat tinggal saat ini sesuai dengan KK? *',
                'Lokasi Rumah / Geotagging',
            ],
            collect($questions)->pluck('question')->all()
        );

        $this->assertSame(
            [
                '3575010101010001',
                'Nama Tersimpan',
                'Kepala Keluarga',
                '3575010101010002',
                '4',
                'Jawa Timur',
                'Kota Pasuruan',
                'Gadingrejo',
                'Krapyakrejo',
                '67115',
                'RT 007 / RW 012',
                'Jl. Contoh No. 1',
                'Jl. Contoh',
                '1',
                'Ya',
            ],
            collect($questions)
                ->take(15)
                ->pluck('answer')
                ->all()
        );
    }

    public function test_monitoring_detail_enrichment_uses_the_selected_part1_record(): void
    {
        DB::table('keluargas')->insert([
            [
                'kode' => 'KELUARGA-DIPILIH',
                'no_kk' => 'KK-SAMA',
                'rt' => '007',
                'rw' => '012',
            ],
            [
                'kode' => 'KELUARGA-LAIN',
                'no_kk' => 'KK-SAMA',
                'rt' => '099',
                'rw' => '098',
            ],
        ]);

        $selectedId = DB::table('part1_keluarga')->insertGetId([
            'keluarga_periode_kode' => 'KELUARGA-DIPILIH',
            'no_kk' => 'KK-SAMA',
            'jml_keluarga' => '1',
            'is_alamat_sesuai' => false,
            'alasan_tidak_sesuai' => 'Alamat berbeda dengan kartu keluarga.',
            'current_part' => 1,
        ]);

        DB::table('part1_keluarga')->insert([
            'keluarga_periode_kode' => 'KELUARGA-LAIN',
            'no_kk' => 'KK-SAMA',
            'jml_keluarga' => '1',
            'current_part' => 1,
        ]);

        $item = (new TestableMonitoringController)->monitoringDetailData([
            'id' => $selectedId,
            'no_kk' => 'KK-SAMA',
            'kuisioner' => [
                [
                    'part' => 1,
                    'title' => 'Data Keluarga',
                    'questions' => [
                        [
                            'question' => 'RT / RW *',
                            'answer' => '-',
                        ],
                        [
                            'question' => 'Apakah alamat tempat tinggal saat ini sesuai dengan KK? *',
                            'answer' => '-',
                        ],
                    ],
                ],
            ],
        ]);

        $part1Questions = collect($item['kuisioner'])
            ->firstWhere('part', 1)['questions'];
        $questionsByText = collect($part1Questions)->keyBy('question');

        $this->assertCount(2, $part1Questions);
        $this->assertSame('RT 007 / RW 012', $questionsByText['RT / RW *']['answer']);
        $this->assertSame(
            'Tidak',
            $questionsByText['Apakah alamat tempat tinggal saat ini sesuai dengan KK? *']['answer']
        );
        $this->assertSame(
            'Alamat berbeda dengan kartu keluarga.',
            $questionsByText['Apakah alamat tempat tinggal saat ini sesuai dengan KK? *']['reason']
        );
    }

    public function test_laporan_includes_only_approved_and_rejected_records(): void
    {
        $statuses = [
            'approved' => 'approved',
            'disetujui' => 'disetujui',
            'rejected' => 'rejected',
            'ditolak' => 'ditolak',
            'rejected_alias' => 'reject',
            'draft' => 'draft',
            'pending' => 'pending',
            'not_processed' => 'not_processed',
            'unfinished' => 'selesai',
        ];

        foreach ($statuses as $code => $status) {
            DB::table('keluargas')->insert([
                'kode' => 'KELUARGA-LAPORAN-'.$code,
                'no_kk' => 'KK-LAPORAN-'.$code,
                'nama_lengkap' => 'Keluarga '.$code,
            ]);

            DB::table('part1_keluarga')->insert([
                'keluarga_periode_kode' => 'KELUARGA-LAPORAN-'.$code,
                'no_kk' => 'KK-LAPORAN-'.$code,
                'nama_kepala_keluarga' => 'Responden '.$code,
                'jml_keluarga' => '1',
                'status' => $status,
            ]);
        }

        $rows = (new TestableLaporanController)->reportRows();

        $this->assertSame(
            [
                'approved',
                'approved',
                'rejected',
                'rejected',
                'rejected',
            ],
            collect($rows)
                ->pluck('status')
                ->sort()
                ->values()
                ->all()
        );
    }

    public function test_laporan_pdf_renders_saved_answers_for_each_part5_member(): void
    {
        Schema::table('part5_anggota_keluargas', function (Blueprint $table): void {
            foreach ([
                'keberadaan',
                'no_hp',
                'jenis_kelamin',
                'pekerjaan_utama',
                'status_pekerjaan',
            ] as $column) {
                $table->string($column)->nullable();
            }

            $table->text('keluhan_kesehatan')->nullable();
        });

        $periodeId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-PDF',
            'nama' => 'Periode PDF',
            'tgl_awal' => '2026-01-01 00:00:00',
            'tgl_akhir' => '2026-12-31 23:59:59',
            'status_periode' => 'Aktif',
        ]);
        session(['periode_id' => $periodeId]);

        DB::table('keluargas')->insert([
            'kode' => 'KELUARGA-PDF',
            'no_kk' => 'KK-PDF',
            'nik' => 'NIK-KEPALA-PDF',
            'nama_lengkap' => 'Kepala Keluarga PDF',
            'status_keluarga' => 'Kepala Keluarga',
        ]);
        DB::table('keluarga_periodes')->insert([
            'kode' => 'KP-PDF',
            'keluarga_kode' => 'KELUARGA-PDF',
            'periode_kode' => 'PERIODE-PDF',
        ]);
        $questionnaireId = DB::table('part1_keluarga')->insertGetId([
            'keluarga_periode_kode' => 'KP-PDF',
            'no_kk' => 'KK-PDF',
            'nama_kepala_keluarga' => 'Kepala Keluarga PDF',
            'jml_keluarga' => '3',
            'status' => 'approved',
            'current_part' => 5,
        ]);

        DB::table('keluarga_anggotas')->insert([
            [
                'kode' => 'ANGGOTA-PDF-1',
                'keluarga_kode' => 'KELUARGA-PDF',
                'nik' => 'NIK-ANGGOTA-PDF-1',
                'nama_lengkap' => 'Anggota PDF Satu',
                'status_keluarga' => 'Anak',
            ],
            [
                'kode' => 'ANGGOTA-PDF-2',
                'keluarga_kode' => 'KELUARGA-PDF',
                'nik' => 'NIK-ANGGOTA-PDF-2',
                'nama_lengkap' => 'Anggota PDF Dua',
                'status_keluarga' => 'Istri',
            ],
        ]);
        DB::table('part5_anggota_keluargas')->insert([
            [
                'keluarga_periode_kode' => 'KP-PDF',
                'keluarga_anggota_kode' => 'ANGGOTA-PDF-1',
                'keberadaan' => 'Ada - Rumah',
                'no_hp' => '0811111111',
                'jenis_kelamin' => 'Laki-laki',
                'pekerjaan_utama' => 'Pelajar',
                'status_pekerjaan' => 'Tidak Bekerja',
                'keluhan_kesehatan' => 'Asma',
            ],
            [
                'keluarga_periode_kode' => 'KP-PDF',
                'keluarga_anggota_kode' => 'ANGGOTA-PDF-2',
                'keberadaan' => 'Ada - Rumah',
                'no_hp' => '0822222222',
                'jenis_kelamin' => 'Perempuan',
                'pekerjaan_utama' => 'Guru',
                'status_pekerjaan' => 'Pegawai',
                'keluhan_kesehatan' => 'Migrain',
            ],
        ]);

        $item = (new TestableLaporanController)
            ->reportRows()
            ->firstWhere('id', $questionnaireId);
        $html = view('admin.laporan.pdf', ['item' => $item])->render();
        $pdfResponse = (new TestableLaporanController)->pdf($questionnaireId);

        $this->assertStringContainsString('Anggota PDF Satu', $html);
        $this->assertStringContainsString('NIK-ANGGOTA-PDF-1', $html);
        $this->assertStringContainsString('0811111111', $html);
        $this->assertStringContainsString('Laki-laki', $html);
        $this->assertStringContainsString('Pelajar', $html);
        $this->assertStringContainsString('Asma', $html);
        $this->assertStringContainsString('Anggota PDF Dua', $html);
        $this->assertStringContainsString('NIK-ANGGOTA-PDF-2', $html);
        $this->assertStringContainsString('0822222222', $html);
        $this->assertStringContainsString('Perempuan', $html);
        $this->assertStringContainsString('Guru', $html);
        $this->assertStringContainsString('Migrain', $html);
        $this->assertSame('application/pdf', $pdfResponse->headers->get('Content-Type'));
        $this->assertStringStartsWith('%PDF-', $pdfResponse->getContent());
    }
}

class TestableVerifikasiController extends VerifikasiController
{
    public function verificationData(): array
    {
        return $this->getData();
    }

    public function part1Questions(KeluargaPart1 $item, ?Keluarga $keluarga): array
    {
        return parent::buildQuestionnaire($item, $keluarga)[0]['questions'];
    }

    public function completedVerificationData(string $status = 'all'): array
    {
        $query = $this->buildFilteredPart1Query([
            'search' => '',
            'kecamatan' => '',
            'kelurahan' => '',
            'status' => $status,
        ], ['pending', 'approved', 'rejected']);

        $this->whereQuestionnaireComplete($query);

        return $this->getData($query);
    }

    protected function getProgress(KeluargaPart1 $item, ?Keluarga $keluarga): array
    {
        return [
            'completed' => 0,
            'total' => 6,
            'percent' => 0,
            'parts' => [],
        ];
    }

    protected function buildWilayah(KeluargaPart1 $item, ?Keluarga $keluarga): string
    {
        return '-';
    }

    protected function buildQuestionnaire(KeluargaPart1 $item, ?Keluarga $keluarga): array
    {
        return [];
    }
}

class TestableMonitoringController extends MonitoringController
{
    public function monitoringData(): array
    {
        return $this->getData()->all();
    }

    public function monitoringDetailData(array $item): array
    {
        return $this->completeMonitoringData($item);
    }
}

class TestableLaporanController extends LaporanController
{
    public function reportRows(?array $filters = null): Collection
    {
        return $this->eligibleRows($filters);
    }
}
