<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('nomor_identitas')->nullable()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('kelurahan')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function test_user_can_login_using_nomor_identitas(): void
    {
        $user = User::factory()->create([
            'nomor_identitas' => 'ADM-001',
            'password' => Hash::make('password'),
        ]);

        $response = $this->from('/')->post('/login', [
            'nomor_identitas' => 'ADM-001',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public static function usersByRoleAndLandingPage(): array
    {
        return [
            'petugas' => ['petugas', 'periode.pilih', ['tujuan' => 'kuisioner.index']],
            'verifikator' => ['verifikator', 'periode.pilih', ['tujuan' => 'verifikasi.index']],
            'operator' => ['operator', 'dashboard', []],
        ];
    }

    #[DataProvider('usersByRoleAndLandingPage')]
    public function test_login_redirects_user_to_the_landing_page_for_their_role(
        string $role,
        string $routeName,
        array $routeParameters
    ): void {
        $user = User::factory()->create([
            'nomor_identitas' => 'ROLE-'.strtoupper($role),
            'role' => $role,
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('login.process'), [
            'nomor_identitas' => $user->nomor_identitas,
            'password' => 'password',
        ]);

        $response->assertRedirect(route($routeName, $routeParameters));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_using_email_when_users_table_has_no_username_column(): void
    {
        $user = User::factory()->create([
            'nomor_identitas' => 'ADM-002',
            'email' => 'admin@example.test',
            'password' => Hash::make('password'),
        ]);

        $response = $this->from('/')->post('/login', [
            'nomor_identitas' => 'admin@example.test',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_login_using_username_when_users_table_has_that_column(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('username')->nullable()->unique();
        });

        $user = User::factory()->create([
            'nomor_identitas' => 'ADM-003',
            'username' => 'admin-user',
            'password' => Hash::make('password'),
        ]);

        $response = $this->from('/')->post('/login', [
            'nomor_identitas' => 'admin-user',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }
}
