<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PeriodeReportAuthenticationTest extends TestCase
{
    public function test_guest_is_redirected_to_login_before_choosing_a_report_period(): void
    {
        $this->get(route('periode.pilih', ['tujuan' => 'laporan.index']))
            ->assertRedirect(route('login'));
    }

    public function test_guest_cannot_submit_a_report_period_selection(): void
    {
        $this->post(route('periode.set'), [
            'periode_id' => 1,
            'tujuan' => 'laporan.index',
        ])->assertRedirect(route('login'));
    }

    public function test_authenticated_user_keeps_login_and_selected_period_after_report_period_selection(): void
    {
        Schema::create('periodes', function (Blueprint $table): void {
            $table->id();
            $table->string('kode');
            $table->string('nama');
            $table->dateTime('tgl_awal')->nullable();
            $table->dateTime('tgl_akhir')->nullable();
            $table->string('status_periode');
            $table->timestamps();
        });

        $periodeId = DB::table('periodes')->insertGetId([
            'kode' => 'PERIODE-LAPORAN',
            'nama' => 'Periode Laporan',
            'status_periode' => 'Aktif',
        ]);

        $user = new User;
        $user->setAttribute('id', 1);
        $this->actingAs($user);

        $this->post(route('periode.set'), [
            'periode_id' => $periodeId,
            'tujuan' => 'laporan.index',
        ])
            ->assertRedirect(route('laporan.index'))
            ->assertSessionHas('periode_id', $periodeId);

        $this->get(route('periode.pilih', ['tujuan' => 'laporan.index']))
            ->assertOk();

        $this->assertAuthenticatedAs($user);
    }
}
