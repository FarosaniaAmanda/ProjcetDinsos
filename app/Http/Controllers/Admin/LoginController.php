<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class LoginController extends Controller
{
    /**
     * Halaman login
     */
    public function index()
    {
        return view('admin.auth.login');
    }

    /**
     * Proses login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'nomor_identitas' => 'required',
            'password' => 'required',
        ], [
            'nomor_identitas.required' => 'Nomor Identitas wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $nomorIdentitas = trim((string) $credentials['nomor_identitas']);

        $user = User::query()
            ->where(function (Builder $query) use ($nomorIdentitas): void {
                $query
                    ->where('nomor_identitas', $nomorIdentitas)
                    ->orWhere('email', $nomorIdentitas);

                if (Schema::hasColumn('users', 'username')) {
                    $query->orWhere('username', $nomorIdentitas);
                }
            })
            ->first();

        if (! $user) {
            return back()
                ->withErrors([
                    'nomor_identitas' => 'Nomor Identitas atau password salah.',
                ])
                ->withInput(
                    $request->only('nomor_identitas')
                );
        }

        $passwordDatabase = $user->getRawOriginal('password');
        $passwordBenar = false;

        if (is_string($passwordDatabase) && $passwordDatabase !== '') {
            if (str_starts_with($passwordDatabase, '$2') || str_starts_with($passwordDatabase, '$argon') || str_starts_with($passwordDatabase, '$pbkdf2')) {
                $passwordBenar = Hash::check($credentials['password'], $passwordDatabase);
            } else {
                $passwordBenar = hash_equals((string) $passwordDatabase, (string) $credentials['password']);
            }
        }

        if ($passwordBenar) {
            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->route('dashboard');
        }

        return back()
            ->withErrors([
                'nomor_identitas' => 'Nomor Identitas atau password salah.',
            ])
            ->withInput(
                $request->only('nomor_identitas')
            );
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
