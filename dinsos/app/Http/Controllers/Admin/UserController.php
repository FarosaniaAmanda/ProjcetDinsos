<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan halaman Pengguna.
     */
    public function index()
    {
        $operators = User::where('role', 'operator')
            ->orderBy('name')
            ->get();

        $verifikators = User::where('role', 'verifikator')
            ->orderBy('name')
            ->get();

        $petugas = User::where('role', 'petugas')
            ->orderBy('name')
            ->get();

        $kelurahans = Kelurahan::orderBy('deskripsi')
            ->get();

        return view(
            'admin.master.pengguna.index',
            compact(
                'operators',
                'verifikators',
                'petugas',
                'kelurahans'
            )
        );
    }

    /**
     * Simpan Operator / Verifikator / Petugas.
     *
     * Password otomatis:
     * perlinsos123
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            'role' => [
                'required',
                Rule::in([
                    'operator',
                    'verifikator',
                    'petugas',
                ]),
            ],

            'nomor_identitas' => [
                'required',
                'string',
                'max:50',
                'unique:users,nomor_identitas',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'kelurahan_id' => [
                'required_if:role,petugas',
                'nullable',
                'string',
                'exists:kelurahans,kelurahan_id',
            ],
        ]);

        DB::transaction(function () use ($validated) {

            $kelurahan = null;

            /*
             * Ambil Kelurahan jika role Petugas.
             */
            if ($validated['role'] === 'petugas') {

                $kelurahan = Kelurahan::where(
                    'kelurahan_id',
                    $validated['kelurahan_id']
                )->firstOrFail();
            }

            /*
             * Simpan User.
             *
             * Password awal:
             * perlinsos123
             */
            User::create([

                'nomor_identitas' =>
                    $validated['nomor_identitas'],

                'name' =>
                    $validated['name'],

                'email' =>
                    $validated['email'],

                'password' =>
                    Hash::make('perlinsos123'),

                'role' =>
                    $validated['role'],

                'kelurahan' =>
                    $kelurahan
                        ? $kelurahan->deskripsi
                        : null,

                'is_active' => 1,
            ]);
        });

        return response()->json([

            'success' => true,

            'message' =>
                ucfirst($validated['role'])
                . ' berhasil ditambahkan.',

        ]);
    }

    /**
     * Update Operator / Verifikator / Petugas.
     *
     * Password tidak diubah dari form Edit.
     * Untuk mengembalikan password ke default,
     * gunakan tombol Reset Password.
     */
    public function update(
        Request $request,
        User $user
    ) {

        $validated = $request->validate([

            'role' => [
                'required',
                Rule::in([
                    'operator',
                    'verifikator',
                    'petugas',
                ]),
            ],

            'nomor_identitas' => [
                'required',
                'string',
                'max:50',

                Rule::unique(
                    'users',
                    'nomor_identitas'
                )->ignore($user->id),
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',

                Rule::unique(
                    'users',
                    'email'
                )->ignore($user->id),
            ],

            'kelurahan_id' => [
                'required_if:role,petugas',
                'nullable',
                'string',
                'exists:kelurahans,kelurahan_id',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $user
        ) {

            $kelurahan = null;

            /*
             * Ambil Kelurahan jika Petugas.
             */
            if ($validated['role'] === 'petugas') {

                $kelurahan = Kelurahan::where(
                    'kelurahan_id',
                    $validated['kelurahan_id']
                )->firstOrFail();
            }

            /*
             * Data yang diperbarui.
             *
             * Password sengaja tidak dimasukkan.
             */
            $data = [

                'nomor_identitas' =>
                    $validated['nomor_identitas'],

                'name' =>
                    $validated['name'],

                'email' =>
                    $validated['email'],

                'role' =>
                    $validated['role'],

                'kelurahan' =>
                    $kelurahan
                        ? $kelurahan->deskripsi
                        : null,
            ];

            $user->update($data);
        });

        return response()->json([

            'success' => true,

            'message' =>
                'Data pengguna berhasil diperbarui.',

        ]);
    }

    /**
     * Reset Password User.
     *
     * Password dikembalikan menjadi:
     * perlinsos123
     */
    public function resetPassword(User $user)
    {
        $user->update([

            'password' =>
                Hash::make('perlinsos123'),

        ]);

        return response()->json([

            'success' => true,

            'message' =>
                'Password berhasil direset ke perlinsos123.',

        ]);
    }

    /**
     * Hapus User.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([

            'success' => true,

            'message' =>
                'Data pengguna berhasil dihapus.',

        ]);
    }
}