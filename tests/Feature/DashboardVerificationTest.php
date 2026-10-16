<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DashboardVerificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('part5_foto_rumahs');
        Schema::dropIfExists('part5_anggota_keluargas');
        Schema::dropIfExists('keluarga_anggotas');
        Schema::dropIfExists('part4_aset_keluargas');
        Schema::dropIfExists('part3_keuangan_keluargas');
        Schema::dropIfExists('part2_kondisi_rumahs');
        Schema::dropIfExists('part1_keluarga');
        Schema::dropIfExists('keluargas');
        Schema::dropIfExists('kecamatans');
        Schema::dropIfExists('periodes');

        Schema::create('periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('status_periode');
            $table->timestamps();
        });

        Schema::create('kecamatans', function (Blueprint $table): void {
            $table->string('kecamatan_id')->primary();
            $table->string('deskripsi');
        });

        Schema::create('keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('no_kk');
            $table->string('nama_lengkap');
            $table->string('kecamatan')->nullable();
            $table->string('kecamatan_id')->nullable();
            $table->timestamps();
        });

        Schema::create('part1_keluarga', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode')->nullable();
            $table->string('no_kk')->nullable();
            $table->string('nama_kepala_keluarga')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('status')->nullable();
            $table->unsignedTinyInteger('current_part')->default(1);
            $table->timestamps();
        });

        foreach ([
            'part2_kondisi_rumahs',
            'part3_keuangan_keluargas',
            'part4_aset_keluargas',
        ] as $tableName) {
            Schema::create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->string('keluarga_periode_kode');
            });
        }

        Schema::create('keluarga_anggotas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('keluarga_kode');
        });

        Schema::create('part5_anggota_keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode');
            $table->string('keluarga_anggota_kode');
        });

        Schema::create('part5_foto_rumahs', function (Blueprint $table): void {
            $table->id();
            $table->string('keluarga_periode_kode');
        });
    }

    public function test_dashboard_counts_verification_statuses_and_uses_the_verification_data(): void
    {
        $approvedCode = $this->createVerificationRecord(1, ' APPROVED ');
        $rejectedCode = $this->createVerificationRecord(2, 'DITOLAK');
        $pendingCode = $this->createVerificationRecord(3, 'selesai', 5);
        $this->completeQuestionnaire($pendingCode);

        $response = $this->get(route('dashboard'));

        $response
            ->assertSee('Status Verifikasi')
            ->assertDontSee('Status Verifikasi Alamat')
            ->assertSee('Menunggu Verifikasi')
            ->assertSee('Keluarga 1')
            ->assertSee('Keluarga 2')
            ->assertSee('Keluarga 3')
            ->assertViewHas('totalResponden', 3)
            ->assertViewHas('jumlahDisetujui', 1)
            ->assertViewHas('jumlahMenungguVerifikasi', 1)
            ->assertViewHas('jumlahDitolak', 1)
            ->assertViewHas('kuisionerTerakhir', function (LengthAwarePaginator $items) use ($approvedCode, $pendingCode, $rejectedCode): bool {
                $statuses = $items->getCollection()
                    ->pluck('status_verifikasi', 'keluarga_periode_kode');

                $this->assertSame('approved', $statuses->get($approvedCode));
                $this->assertSame('pending', $statuses->get($pendingCode));
                $this->assertSame('rejected', $statuses->get($rejectedCode));

                return true;
            });
    }

    public function test_dashboard_questionnaire_table_paginates_five_verification_records_per_page(): void
    {
        foreach (range(1, 6) as $number) {
            $this->createVerificationRecord($number, 'approved');
        }

        $firstPage = $this->get(route('dashboard'));

        $firstPage
            ->assertSee('Keluarga 6')
            ->assertDontSee('Keluarga 1')
            ->assertSee('page=2', false)
            ->assertViewHas('kuisionerTerakhir', function (LengthAwarePaginator $items): bool {
                $this->assertSame(6, $items->total());
                $this->assertSame(5, $items->perPage());
                $this->assertSame(1, $items->currentPage());

                return true;
            });

        $secondPage = $this->get(route('dashboard', ['page' => 2]));

        $secondPage
            ->assertSee('Keluarga 1')
            ->assertDontSee('Keluarga 6')
            ->assertSee('Menampilkan 6 sampai 6 dari 6 data')
            ->assertViewHas('kuisionerTerakhir', function (LengthAwarePaginator $items): bool {
                $this->assertSame(6, $items->total());
                $this->assertSame(2, $items->currentPage());

                return true;
            });
    }

    public function test_dashboard_shows_disabled_pagination_controls_when_questionnaires_fit_on_one_page(): void
    {
        $this->createVerificationRecord(1, 'approved');

        $response = $this->get(route('dashboard'));

        $response
            ->assertSee('aria-label="Navigasi halaman kuisioner"', false)
            ->assertSee('pagination-button active', false)
            ->assertSee('aria-current="page"', false);

        $this->assertSame(
            2,
            substr_count($response->getContent(), 'aria-disabled="true"')
        );
    }

    public function test_dashboard_paginates_all_two_thousand_questionnaires(): void
    {
        $this->createVerificationRecords(2000);

        $response = $this->get(route('dashboard', ['page' => 400]));

        $response
            ->assertSee('Keluarga 1')
            ->assertDontSee('Keluarga 6')
            ->assertSee('Menampilkan 1996 sampai 2000 dari 2000 data')
            ->assertViewHas('kuisionerTerakhir', function (LengthAwarePaginator $items): bool {
                $this->assertSame(2000, $items->total());
                $this->assertSame(400, $items->currentPage());

                return true;
            });
    }

    private function createVerificationRecord(
        int $number,
        string $status,
        int $currentPart = 1
    ): string {
        $code = 'KLG-'.str_pad((string) $number, 4, '0', STR_PAD_LEFT);
        $noKk = 'KK-'.str_pad((string) $number, 4, '0', STR_PAD_LEFT);
        $name = 'Keluarga '.$number;
        $timestamp = now()->addSeconds($number);

        DB::table('keluargas')->insert([
            'kode' => $code,
            'no_kk' => $noKk,
            'nama_lengkap' => $name,
            'kecamatan' => 'Kecamatan Tes',
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        DB::table('part1_keluarga')->insert([
            'keluarga_periode_kode' => $code,
            'no_kk' => $noKk,
            'nama_kepala_keluarga' => $name,
            'kecamatan' => 'Kecamatan Tes',
            'status' => $status,
            'current_part' => $currentPart,
            'created_at' => $timestamp,
            'updated_at' => $timestamp,
        ]);

        return $code;
    }

    private function createVerificationRecords(int $count): void
    {
        $families = [];
        $questionnaires = [];
        $timestamp = now();

        foreach (range(1, $count) as $number) {
            $code = 'KLG-'.str_pad((string) $number, 4, '0', STR_PAD_LEFT);
            $noKk = 'KK-'.str_pad((string) $number, 4, '0', STR_PAD_LEFT);
            $createdAt = $timestamp->copy()->addSeconds($number);

            $families[] = [
                'kode' => $code,
                'no_kk' => $noKk,
                'nama_lengkap' => 'Keluarga '.$number,
                'kecamatan' => 'Kecamatan Tes',
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];

            $questionnaires[] = [
                'keluarga_periode_kode' => $code,
                'no_kk' => $noKk,
                'nama_kepala_keluarga' => 'Keluarga '.$number,
                'kecamatan' => 'Kecamatan Tes',
                'status' => 'approved',
                'current_part' => 1,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        foreach (array_chunk($families, 250) as $familyBatch) {
            DB::table('keluargas')->insert($familyBatch);
        }

        foreach (array_chunk($questionnaires, 250) as $questionnaireBatch) {
            DB::table('part1_keluarga')->insert($questionnaireBatch);
        }
    }

    private function completeQuestionnaire(string $code): void
    {
        foreach ([
            'part2_kondisi_rumahs',
            'part3_keuangan_keluargas',
            'part4_aset_keluargas',
        ] as $tableName) {
            DB::table($tableName)->insert([
                'keluarga_periode_kode' => $code,
            ]);
        }

        $memberCode = $code.'-ANGGOTA-1';

        DB::table('keluarga_anggotas')->insert([
            'kode' => $memberCode,
            'keluarga_kode' => $code,
        ]);

        DB::table('part5_anggota_keluargas')->insert([
            'keluarga_periode_kode' => $code,
            'keluarga_anggota_kode' => $memberCode,
        ]);

        DB::table('part5_foto_rumahs')->insert([
            'keluarga_periode_kode' => $code,
        ]);
    }
}
