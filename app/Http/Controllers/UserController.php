<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Kelurahan;
use App\Models\RtRw;
use App\Models\PetugasWilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            ->with([
                'petugasWilayah.rtRw'
            ])
            ->orderBy('name')
            ->get();

        $kelurahans = Kelurahan::orderBy('deskripsi')->get();

        return view('admin.master.pengguna.index', compact(
            'operators',
            'verifikators',
            'petugas',
            'kelurahans'
        ));
    }


    /**
     * Simpan Operator / Verifikator / Petugas.
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

            'password' => [
                'required',
                'string',
                'min:6',
            ],

            'kelurahan_id' => [
                'required_if:role,petugas',
                'nullable',
                'string',
                'exists:kelurahans,kelurahan_id',
            ],

            'rt_rw_ids' => [
                'required_if:role,petugas',
                'nullable',
                'array',
                'min:1',
            ],

            'rt_rw_ids.*' => [
                'integer',
                'distinct',
                'exists:rt_rws,id',
            ],
        ]);


        DB::transaction(function () use ($validated) {

            $kelurahan = null;

            if ($validated['role'] === 'petugas') {

                $kelurahan = Kelurahan::where(
                    'kelurahan_id',
                    $validated['kelurahan_id']
                )->firstOrFail();


                /*
                |--------------------------------------------------------------------------
                | Pastikan RT/RW memang milik Kelurahan yang dipilih
                |--------------------------------------------------------------------------
                */

                $validRtRwIds = RtRw::where(
                    'kelurahan_id',
                    $validated['kelurahan_id']
                )
                    ->whereIn(
                        'id',
                        $validated['rt_rw_ids']
                    )
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->sort()
                    ->values()
                    ->all();


                $requestedRtRwIds = collect(
                    $validated['rt_rw_ids']
                )
                    ->map(fn ($id) => (int) $id)
                    ->sort()
                    ->values()
                    ->all();


                if ($validRtRwIds !== $requestedRtRwIds) {

                    abort(
                        422,
                        'RT/RW yang dipilih tidak sesuai dengan kelurahan.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Simpan User
            |--------------------------------------------------------------------------
            */

            $user = User::create([
                'nomor_identitas' => $validated['nomor_identitas'],
                'name' => $validated['name'],
                'email' => $validated['email'],

                /*
                 * User.php sudah menggunakan cast "hashed".
                 * Jadi password tidak perlu Hash::make().
                 */
                'password' => $validated['password'],

                'role' => $validated['role'],

                'kelurahan' => $kelurahan
                    ? $kelurahan->deskripsi
                    : null,
            ]);


            /*
            |--------------------------------------------------------------------------
            | Simpan wilayah Petugas
            |--------------------------------------------------------------------------
            */

            if ($validated['role'] === 'petugas') {

                foreach ($validated['rt_rw_ids'] as $rtRwId) {

                    PetugasWilayah::create([
                        'user_id' => $user->id,
                        'rt_rw_id' => $rtRwId,
                    ]);
                }
            }
        });


        return response()->json([
            'success' => true,
            'message' => ucfirst($validated['role'])
                . ' berhasil ditambahkan.',
        ]);
    }


    /**
     * Update Operator / Verifikator / Petugas.
     */
    public function update(Request $request, User $user)
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
                Rule::unique('users', 'nomor_identitas')
                    ->ignore($user->id),
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
                Rule::unique('users', 'email')
                    ->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:6',
            ],

            'kelurahan_id' => [
                'required_if:role,petugas',
                'nullable',
                'string',
                'exists:kelurahans,kelurahan_id',
            ],

            'rt_rw_ids' => [
                'required_if:role,petugas',
                'nullable',
                'array',
                'min:1',
            ],

            'rt_rw_ids.*' => [
                'integer',
                'distinct',
                'exists:rt_rws,id',
            ],
        ]);


        DB::transaction(function () use (
            $validated,
            $user
        ) {

            $kelurahan = null;


            /*
            |--------------------------------------------------------------------------
            | Validasi wilayah jika Petugas
            |--------------------------------------------------------------------------
            */

            if ($validated['role'] === 'petugas') {

                $kelurahan = Kelurahan::where(
                    'kelurahan_id',
                    $validated['kelurahan_id']
                )->firstOrFail();


                $validRtRwIds = RtRw::where(
                    'kelurahan_id',
                    $validated['kelurahan_id']
                )
                    ->whereIn(
                        'id',
                        $validated['rt_rw_ids']
                    )
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->sort()
                    ->values()
                    ->all();


                $requestedRtRwIds = collect(
                    $validated['rt_rw_ids']
                )
                    ->map(fn ($id) => (int) $id)
                    ->sort()
                    ->values()
                    ->all();


                if ($validRtRwIds !== $requestedRtRwIds) {

                    abort(
                        422,
                        'RT/RW yang dipilih tidak sesuai dengan kelurahan.'
                    );
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Data utama User
            |--------------------------------------------------------------------------
            */

            $data = [
                'nomor_identitas' => $validated['nomor_identitas'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'kelurahan' => $kelurahan
                    ? $kelurahan->deskripsi
                    : null,
            ];


            /*
            |--------------------------------------------------------------------------
            | Password
            |--------------------------------------------------------------------------
            */

            if (!empty($validated['password'])) {

                $data['password'] =
                    $validated['password'];
            }


            $user->update($data);


            /*
            |--------------------------------------------------------------------------
            | Hapus wilayah lama
            |--------------------------------------------------------------------------
            */

            PetugasWilayah::where(
                'user_id',
                $user->id
            )->delete();


            /*
            |--------------------------------------------------------------------------
            | Simpan wilayah baru jika Petugas
            |--------------------------------------------------------------------------
            */

            if ($validated['role'] === 'petugas') {

                foreach ($validated['rt_rw_ids'] as $rtRwId) {

                    PetugasWilayah::create([
                        'user_id' => $user->id,
                        'rt_rw_id' => $rtRwId,
                    ]);
                }
            }
        });


        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diperbarui.',
        ]);
    }


    /**
     * Hapus User.
     */
    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {

            PetugasWilayah::where(
                'user_id',
                $user->id
            )->delete();

            $user->delete();
        });


        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil dihapus.',
        ]);
    }


    /**
     * Mengambil RT/RW berdasarkan Kelurahan.
     */
    public function getRtRw($kelurahanId)
    {
        $rtRw = RtRw::where(
            'kelurahan_id',
            $kelurahanId
        )
            ->orderBy('rw')
            ->orderBy('rt')
            ->get([
                'id',
                'kelurahan_id',
                'rt',
                'rw',
            ]);


        return response()->json($rtRw);
    }
}