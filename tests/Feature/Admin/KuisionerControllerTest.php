git stash pop<?php

namespace Tests\Feature\Admin;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class KuisionerControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('part1_keluarga');
        Schema::dropIfExists('keluargas');

        Schema::create('keluargas', function (Blueprint $table): void {
            $table->id();
            $table->string('kode')->unique();
            $table->string('no_kk')->nullable();
            $table->string('nik')->nullable();
            $table->string('nama_lengkap')->nullable();
            $table->string('alamat_lengkap')->nullable();
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
    }

    public function test_rejected_questionnaire_opens_for_editing_without_losing_saved_data(): void
    {
        $familyCode = 'KLG-EDIT-001';

        DB::table('keluargas')->insert([
            'kode' => $familyCode,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $questionnaireId = DB::table('part1_keluarga')->insertGetId([
            'keluarga_periode_kode' => $familyCode,
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
            ->assertSessionHas('selected_keluarga_kode', $familyCode)
            ->assertSessionHas('editing_rejected_questionnaire', true);

        $this->assertDatabaseHas('part1_keluarga', [
            'id' => $questionnaireId,
            'keluarga_periode_kode' => $familyCode,
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

        $questionnaireId = DB::table('part1_keluarga')->insertGetId([
            'keluarga_periode_kode' => $familyCode,
            'status' => 'approved',
            'current_part' => 5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->post(route('kuisioner.rejected.edit', ['id' => $questionnaireId]))
            ->assertRedirect(route('verifikasi.index', ['status' => 'rejected']));

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

        DB::table('part1_keluarga')->insert([
            [
                'keluarga_periode_kode' => 'KLG-REJECTED',
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
                'keluarga_periode_kode' => 'KLG-APPROVED',
                'no_kk' => '222',
                'nama_kepala_keluarga' => 'Responden Disetujui',
                'status' => 'approved',
                'current_part' => 5,
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

        DB::table('part1_keluarga')->insert([
            [
                'keluarga_periode_kode' => 'KLG-REJECTED',
                'no_kk' => '111',
                'nama_kepala_keluarga' => 'Responden Ditolak',
                'status' => 'ditolak',
                'current_part' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'keluarga_periode_kode' => 'KLG-APPROVED',
                'no_kk' => '222',
                'nik' => '444',
                'nama_kepala_keluarga' => 'Responden Disetujui',
                'alamat_lengkap' => 'Jl. Melati',
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
}
