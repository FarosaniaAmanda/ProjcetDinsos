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
            'operator account' => ['operator', 'Operator Aktif', 'Operator'],
            'petugas account' => ['petugas', 'Petugas Aktif', 'Petugas'],
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
            'periodeAktif' => 0,
            'rekapPeriode' => collect(),
            'kuisionerTerakhir' => collect(),
        ])->render();

        $this->assertStringContainsString($name, $layout);
        $this->assertStringContainsString($roleLabel, $layout);
        $this->assertStringNotContainsString('Operator - Admin', $layout);
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
