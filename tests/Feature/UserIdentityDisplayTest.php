<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UserIdentityDisplayTest extends TestCase
{
    public static function authenticatedUsersByRole(): array
    {
        return [
            'operator account' => ['operator', 'Eka', 'Operator'],
            'verifikator account' => ['verifikator', 'Adibah', 'Verifikator'],
        ];
    }

    #[DataProvider('authenticatedUsersByRole')]
    public function test_profile_and_application_headers_show_the_authenticated_identity(
        string $role,
        string $name,
        string $roleLabel
    ): void {
        Auth::setUser(new User([
            'name' => $name,
            'role' => $role,
        ]));

        $this->get(route('profil.index'))
            ->assertSee('Profil '.$roleLabel)
            ->assertSee('Nama '.$roleLabel)
            ->assertSee($name)
            ->assertSee($roleLabel)
            ->assertDontSee('Administrator Sistem Pendataan Perlinsos');

        $layout = view('admin.layouts.app')->render();
        $dashboard = view('admin.dashboard', [
            'totalResponden' => 0,
            'periodeAktif' => 0,
            'jumlahMenungguVerifikasi' => 0,
            'jumlahDisetujui' => 0,
            'jumlahDitolak' => 0,
            'persebaranKecamatan' => collect(),
            'rekapPeriode' => collect(),
            'kuisionerTerakhir' => collect(),
        ])->render();

        $this->assertStringContainsString($name, $layout);
        $this->assertStringContainsString($roleLabel, $layout);
        $this->assertStringNotContainsString('Operator - Admin', $layout);
        $dashboardProfileMarker = strpos($dashboard, 'class="header-admin"');
        $dashboardProfileStart = strrpos(
            substr($dashboard, 0, (int) $dashboardProfileMarker),
            '<a'
        );
        $dashboardProfileEnd = strpos($dashboard, '</a>', (int) $dashboardProfileMarker);

        $this->assertNotFalse($dashboardProfileMarker);
        $this->assertNotFalse($dashboardProfileStart);
        $this->assertNotFalse($dashboardProfileEnd);

        $dashboardProfile = substr(
            $dashboard,
            (int) $dashboardProfileStart,
            (int) $dashboardProfileEnd - (int) $dashboardProfileStart
        );

        $this->assertStringContainsString($name, $dashboardProfile);
        $this->assertStringContainsString($roleLabel, $dashboardProfile);

        $profileMarker = strpos($layout, '<!-- ADMIN PROFILE -->');
        $profileLinkStart = strpos($layout, '<a', (int) $profileMarker);
        $profileLinkEnd = strpos($layout, '</a>', (int) $profileLinkStart);

        $this->assertNotFalse($profileMarker);
        $this->assertNotFalse($profileLinkStart);
        $this->assertNotFalse($profileLinkEnd);

        $profileLink = substr(
            $layout,
            (int) $profileLinkStart,
            (int) $profileLinkEnd - (int) $profileLinkStart
        );

        $this->assertStringContainsString('href="'.route('profil.index').'"', $profileLink);
        $this->assertStringContainsString('class="admin-avatar"', $profileLink);
        $this->assertStringContainsString($name, $profileLink);
        $this->assertStringContainsString($roleLabel, $profileLink);
        $this->assertStringContainsString($name, $dashboard);
        $this->assertStringContainsString('Anda masuk sebagai '.strtolower($roleLabel), $dashboard);
    }
}
