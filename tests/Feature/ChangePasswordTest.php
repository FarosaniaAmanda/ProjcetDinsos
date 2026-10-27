<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ChangePasswordTest extends TestCase
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

    public function test_authenticated_user_saves_a_new_password_as_a_hash(): void
    {
        $user = User::factory()->create([
            'password' => 'current-password',
        ]);

        $response = $this->actingAs($user)
            ->from(route('profil.password'))
            ->post(route('profil.password.update'), [
                'password_lama' => 'current-password',
                'password_baru' => 'new-password',
                'password_baru_confirmation' => 'new-password',
            ]);

        $response->assertRedirect(route('profil.index'));
        $response->assertSessionHas('success', 'Password berhasil diubah.');

        $updatedPassword = $user->fresh()->password;

        $this->assertNotSame('new-password', $updatedPassword);
        $this->assertTrue(Hash::check('new-password', $updatedPassword));
    }

    public function test_user_cannot_change_password_with_an_incorrect_current_password(): void
    {
        $user = User::factory()->create([
            'password' => 'current-password',
        ]);

        $response = $this->actingAs($user)
            ->from(route('profil.password'))
            ->post(route('profil.password.update'), [
                'password_lama' => 'incorrect-password',
                'password_baru' => 'new-password',
                'password_baru_confirmation' => 'new-password',
            ]);

        $response->assertRedirect(route('profil.password'));
        $response->assertSessionHasErrors([
            'password_lama' => 'Password lama salah.',
        ]);

        $this->assertTrue(Hash::check('current-password', $user->fresh()->password));
    }
}
