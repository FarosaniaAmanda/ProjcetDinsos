<?php

namespace Tests\Feature\Admin;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class KuisionerControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('keluarga_periodes');
        Schema::dropIfExists('periodes');
        Schema::dropIfExists('part1_keluarga');
        Schema::dropIfExists('keluargas');

        Schema::create('periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama')->nullable();
            $table->string('status_periode');
            $table->timestamps();
        });

        Schema::create('keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode')->unique();
            $table->string('no_kk')->nullable();
            $table->string('nik')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('alamat_lengkap')->nullable();
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
            $table->string('keluarga_periode_kode');
            $table->string('no_kk')->nullable();
            $table->string('nik')->nullable();
            $table->string('nama_kepala_keluarga')->nullable();
            $table->string('jml_keluarga')->nullable();
            $table->string('alamat_lengkap')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kelurahan')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedTinyInteger('current_part')->default(1);
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        $periodeId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-TEST',
            'nama' => 'Periode Tes',
            'status_periode' => 'Aktif',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withSession(['periode_id' => $periodeId]);
    }

    public function test_rejected_questionnaire_opens_for_editing_without_losing_saved_data(): void
    {
        $familyCode = 'KLG-EDIT-001';

        DB::table('keluargas')->insert([
            'kode' => $familyCode,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->linkFamily($familyCode, 'KLP-EDIT-001');

        $questionnaireId = DB::table('part1_keluarga')->insertGetId([
            'keluarga_periode_kode' => 'KLP-EDIT-001',
            'no_kk' => '3575020101010001',
            'nama_kepala_keluarga' => 'Responden Ditolak',
            'status' => 'ditolak',
            'current_part' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->post(route('kuisioner.rejected.edit', ['id' => $questionnaireId]))
            ->assertRedirect(route('kuisioner.part1'))
            ->assertSessionHas('draft_keluarga_id', $questionnaireId)
            ->assertSessionHas('selected_keluarga_kode', 'KLP-EDIT-001')
            ->assertSessionHas('editing_rejected_questionnaire', true);

        $this->assertDatabaseHas('part1_keluarga', [
            'id' => $questionnaireId,
            'keluarga_periode_kode' => 'KLP-EDIT-001',
            'no_kk' => '3575020101010001',
            'nama_kepala_keluarga' => 'Responden Ditolak',
            'status' => 'ditolak',
            'current_part' => 5,
        ]);
    }

    public function test_non_rejected_questionnaire_cannot_be_opened_for_editing(): void
    {
        $familyCode = 'KLG-EDIT-002';

        DB::table('keluargas')->insert([
            'kode' => $familyCode,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->linkFamily($familyCode, 'KLP-EDIT-002');

        $questionnaireId = DB::table('part1_keluarga')->insertGetId([
            'keluarga_periode_kode' => 'KLP-EDIT-002',
            'status' => 'approved',
            'current_part' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->post(route('kuisioner.rejected.edit', ['id' => $questionnaireId]))
            ->assertRedirect(route('kuisioner.ditolak'));

        $this->assertDatabaseHas('part1_keluarga', [
            'id' => $questionnaireId,
            'status' => 'approved',
            'current_part' => 5,
        ]);
    }

    public function test_rejected_page_shows_only_rejected_questionnaires_and_saved_initial_data(): void
    {
        DB::table('keluargas')->insert([
            ['kode' => 'KLG-REJECTED', 'no_kk' => '111', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'KLG-APPROVED', 'no_kk' => '222', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->linkFamily('KLG-REJECTED', 'KLP-REJECTED');
        $this->linkFamily('KLG-APPROVED', 'KLP-APPROVED');

        DB::table('part1_keluarga')->insert([
            [
                'keluarga_periode_kode' => 'KLP-REJECTED',
                'no_kk' => '111',
                'nik' => '333',
                'nama_kepala_keluarga' => 'Responden Ditolak',
                'jml_keluarga' => '4',
                'alamat_lengkap' => 'Jl. Mawar',
                'kecamatan' => 'Gadingrejo',
                'kelurahan' => 'Bukir',
                'status' => 'rejected',
                'current_part' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keluarga_periode_kode' => 'KLP-APPROVED',
                'no_kk' => '222',
                'nik' => null,
                'nama_kepala_keluarga' => 'Responden Disetujui',
                'jml_keluarga' => null,
                'status' => 'approved',
                'current_part' => 5,
                'alamat_lengkap' => null,
                'kecamatan' => null,
                'kelurahan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->withoutVite()
            ->get(route('kuisioner.ditolak'))
            ->assertSee('Kuisioner Ditolak')
            ->assertSee('Responden Ditolak')
            ->assertSee('Jl. Mawar')
            ->assertDontSee('Responden Disetujui');
    }

    public function test_approved_page_shows_only_approved_questionnaires_and_saved_initial_data(): void
    {
        DB::table('keluargas')->insert([
            ['kode' => 'KLG-REJECTED', 'no_kk' => '111', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'KLG-APPROVED', 'no_kk' => '222', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->linkFamily('KLG-REJECTED', 'KLP-REJECTED');
        $this->linkFamily('KLG-APPROVED', 'KLP-APPROVED');

        DB::table('part1_keluarga')->insert([
            [
                'keluarga_periode_kode' => 'KLP-REJECTED',
                'no_kk' => '111',
                'nik' => null,
                'nama_kepala_keluarga' => 'Responden Ditolak',
                'jml_keluarga' => null,
                'alamat_lengkap' => null,
                'kecamatan' => null,
                'kelurahan' => null,
                'status' => 'ditolak',
                'current_part' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keluarga_periode_kode' => 'KLP-APPROVED',
                'no_kk' => '222',
                'nik' => '444',
                'nama_kepala_keluarga' => 'Responden Disetujui',
                'jml_keluarga' => null,
                'alamat_lengkap' => 'Jl. Melati',
                'kecamatan' => null,
                'kelurahan' => null,
                'status' => 'disetujui',
                'current_part' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        $this->withoutVite()
            ->get(route('kuisioner.disetujui'))
            ->assertSee('Kuisioner Disetujui')
            ->assertSee('Responden Disetujui')
            ->assertSee('Jl. Melati')
            ->assertDontSee('Responden Ditolak');
    }

    public function test_part5_photo_upload_saves_without_draft_and_reuses_the_existing_record(): void
    {
        Schema::dropIfExists('keluarga_anggotas');
        Schema::dropIfExists('part5_anggota_keluargas');
        Schema::dropIfExists('part5_foto_rumahs');

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

        $migration = require database_path(
            'migrations/2026_10_09_021412_create_part5_foto_rumahs_table.php'
        );
        $migration->up();

        $this->linkFamily('KLG-FOTO-001', 'KLP-FOTO-001');

        $questionnaireId = DB::table('part1_keluarga')->insertGetId([
            'keluarga_periode_kode' => 'KLP-FOTO-001',
            'status' => 'draft',
            'current_part' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withSession([
            'draft_keluarga_id' => $questionnaireId,
            'selected_keluarga_kode' => 'KLP-FOTO-001',
        ]);
        Storage::fake('public');

        $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('kuisioner.part5.foto.store'), [
            'action' => 'upload',
        ])->assertUnprocessable()->assertJson([
            'success' => false,
            'message' => 'Pilih foto yang ingin disimpan.',
        ]);
        $this->assertDatabaseCount('part5_foto_rumahs', 0);

        $upload = fn (string $field) => $this->withHeaders([
            'Accept' => 'application/json',
        ])->post(route('kuisioner.part5.foto.store'), [
            'action' => 'upload',
            $field => UploadedFile::fake()->image($field.'.png'),
        ]);

        $upload('tampak_depan')->assertOk()->assertJson([
            'success' => true,
        ]);
        $firstPath = DB::table('part5_foto_rumahs')->value('path_file');

        $upload('tampak_depan')->assertOk()->assertJson([
            'success' => true,
        ]);
        $secondPath = DB::table('part5_foto_rumahs')->value('path_file');

        foreach (['ruang_tamu', 'kamar_mandi'] as $field) {
            $upload($field)->assertOk()->assertJson([
                'success' => true,
            ]);
        }

        $this->assertDatabaseCount('part5_foto_rumahs', 3);
        $this->assertNotSame($firstPath, $secondPath);
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($secondPath);
        foreach (['ruang_tamu', 'kamar_mandi'] as $field) {
            $path = DB::table('part5_foto_rumahs')
                ->where('jenis_foto', $field)
                ->value('path_file');

            $this->assertDatabaseHas('part5_foto_rumahs', [
                'keluarga_periode_kode' => 'KLP-FOTO-001',
                'jenis_foto' => $field,
                'path_file' => $path,
            ]);
            Storage::disk('public')->assertExists($path);
        }
        $this->assertDatabaseHas('part1_keluarga', [
            'id' => $questionnaireId,
            'status' => 'draft',
            'current_part' => 5,
        ]);

        $this->withoutVite()
            ->get(route('kuisioner.part5.foto'))
            ->assertOk()
            ->assertSee(
                'data-upload-url="'.route('kuisioner.part5.foto.store').'"',
                false
            )
            ->assertSee(asset('storage/'.$secondPath), false)
            ->assertDontSee('Foto berhasil disimpan.')
            ->assertDontSee('Foto sudah tersimpan di server.');

        $this->post(route('kuisioner.part5.foto.store'), [
            'action' => 'draft',
        ])->assertRedirect(route('kuisioner.index'))
            ->assertSessionHas('success', 'Foto rumah berhasil disimpan sebagai draft.');
        $this->assertDatabaseCount('part5_foto_rumahs', 3);
    }

    public function test_completed_questionnaire_displays_legacy_encrypted_identifiers_as_plain_text(): void
    {
        Schema::create('keluarga_anggotas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('keluarga_kode');
            $table->string('nik')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('status_keluarga')->nullable();
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
                $table->string('keluarga_periode_kode');
            });
        }

        DB::table('keluargas')->insert([
            'kode' => 'KLG-DETAIL-ENCRYPTED',
            'no_kk' => '3575020101010001',
            'nik' => '3575020101010002',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $this->linkFamily('KLG-DETAIL-ENCRYPTED', 'KLP-DETAIL-ENCRYPTED');

        $nik = '3575020101010002';
        $noKk = '3575020101010001';
        $memberNik = '3575020101010003';
        $nikCiphertext = Crypt::encryptString($nik);
        $noKkCiphertext = Crypt::encryptString($noKk);
        $memberNikCiphertext = Crypt::encryptString($memberNik);

        DB::table('keluarga_anggotas')->insert([
            'kode' => 'ANG-DETAIL-ENCRYPTED',
            'keluarga_kode' => 'KLG-DETAIL-ENCRYPTED',
            'nik' => $memberNikCiphertext,
            'nama_lengkap' => 'Anggota Terenkripsi',
            'status_keluarga' => 'Anak',
        ]);

        $questionnaireId = DB::table('part1_keluarga')->insertGetId([
            'keluarga_periode_kode' => 'KLP-DETAIL-ENCRYPTED',
            'no_kk' => $noKkCiphertext,
            'nik' => $nikCiphertext,
            'nama_kepala_keluarga' => 'Responden Selesai',
            'status' => 'selesai',
            'current_part' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->withoutVite()
            ->get(route('kuisioner.selesai.detail', $questionnaireId))
            ->assertOk()
            ->assertSee($noKk)
            ->assertSee($nik)
            ->assertSee($memberNik)
            ->assertDontSee($noKkCiphertext)
            ->assertDontSee($nikCiphertext)
            ->assertDontSee($memberNikCiphertext);
    }

    private function linkFamily(string $familyCode, string $relationCode): void
    {
        DB::table('keluarga_periodes')->insert([
            'kode' => $relationCode,
            'keluarga_kode' => $familyCode,
            'periode_kode' => 'PERIODE-TEST',
            'status_kuisoner' => 'belum',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
