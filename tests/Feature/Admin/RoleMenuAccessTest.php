<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class RoleMenuAccessTest extends TestCase
{
    public static function deniedMenuAccess(): array
    {
        return [
            'petugas cannot open the dashboard' => [
                'petugas',
                '/dashboard',
                'periode.pilih',
                ['tujuan' => 'kuisioner.index'],
            ],
            'verifikator cannot open the dashboard' => [
                'verifikator',
                '/dashboard',
                'periode.pilih',
                ['tujuan' => 'verifikasi.index'],
            ],
            'operator cannot open questionnaires' => [
                'operator',
                '/kuisioner',
                'dashboard',
                [],
            ],
            'operator cannot open verifications' => [
                'operator',
                '/verifikasi',
                'dashboard',
                [],
            ],
            'petugas cannot open verifications' => [
                'petugas',
                '/verifikasi',
                'periode.pilih',
                ['tujuan' => 'kuisioner.index'],
            ],
            'verifikator cannot open questionnaires' => [
                'verifikator',
                '/kuisioner',
                'periode.pilih',
                ['tujuan' => 'verifikasi.index'],
            ],
        ];
    }

    #[DataProvider('deniedMenuAccess')]
    public function test_user_is_redirected_from_menus_their_role_cannot_access(
        string $role,
        string $path,
        string $expectedRoute,
        array $expectedParameters
    ): void {
        $user = new User(['role' => $role]);

        $response = $this->actingAs($user)->get($path);

        $response->assertRedirect(route($expectedRoute, $expectedParameters));
    }

    public static function invalidPeriodMenuTargets(): array
    {
        return [
            'petugas can only choose questionnaire periods' => [
                'petugas',
                'verifikasi.index',
                'kuisioner.index',
            ],
            'verifikator can only choose verification periods' => [
                'verifikator',
                'kuisioner.index',
                'verifikasi.index',
            ],
        ];
    }

    #[DataProvider('invalidPeriodMenuTargets')]
    public function test_period_picker_keeps_users_on_their_role_menu(
        string $role,
        string $requestedTarget,
        string $expectedTarget
    ): void {
        $user = new User(['role' => $role]);

        $this->actingAs($user)
            ->get(route('periode.pilih', ['tujuan' => $requestedTarget]))
            ->assertRedirect(route('periode.pilih', ['tujuan' => $expectedTarget]));

        $this->post(route('periode.set'), [
            'periode_id' => 1,
            'tujuan' => $requestedTarget,
        ])->assertRedirect(route('periode.pilih', ['tujuan' => $expectedTarget]));
    }

    public function test_operator_cannot_choose_questionnaire_or_verification_periods(): void
    {
        $user = new User(['role' => 'operator']);

        $this->actingAs($user)
            ->get(route('periode.pilih', ['tujuan' => 'kuisioner.index']))
            ->assertRedirect(route('dashboard'));

        $this->post(route('periode.set'), [
            'periode_id' => 1,
            'tujuan' => 'verifikasi.index',
        ])->assertRedirect(route('dashboard'));
    }
}
