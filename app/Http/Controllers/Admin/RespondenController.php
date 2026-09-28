<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaAnggota;
use App\Models\RtRw;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RespondenController extends Controller
{
    /**
     * Menampilkan daftar responden.
     */
    public function index()
    {
        $keluargas = Keluarga::with([
            'anggota' => function ($query) {
                $query->orderBy('id');
            },
            'rtRw',
        ])
            ->latest()
            ->get();

        $kecamatans = DB::table('kecamatans')
            ->orderBy('deskripsi', 'asc')
            ->get();

        return view(
            'admin.responden.index',
            compact(
                'keluargas',
                'kecamatans'
            )
        );
    }

    /**
     * Menampilkan halaman tambah responden.
     */
    public function create()
    {
        $kecamatans = DB::table('kecamatans')
            ->orderBy('deskripsi', 'asc')
            ->get();

        return view(
            'admin.responden.create',
            compact('kecamatans')
        );
    }

    /**
     * Menyimpan data responden baru.
     */
    public function store(Request $request)
    {
        if (!$request->filled('nomor_kk') && $request->filled('no_kk')) {
            $request->merge([
                'nomor_kk' => $request->input('no_kk'),
            ]);
        }

        if (!$request->filled('no_kk') && $request->filled('nomor_kk')) {
            $request->merge([
                'no_kk' => $request->input('nomor_kk'),
            ]);
        }

        $request->validate([
            'nomor_kk' => [
                'required',
                'digits:16',
            ],

            'nama_kepala_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota' => [
                'required',
                'array',
                'min:1',
            ],

            'anggota.*.nik' => [
                'required',
                'digits:16',
            ],

            'anggota.*.nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota.*.status_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota.*.status_keluarga_lainnya' => [
                'nullable',
                'string',
                'max:255',
            ],

            'provinsi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'daerah' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kecamatan_id' => [
                'nullable',
                'string',
                'max:13',
            ],

            'kelurahan_id' => [
                'nullable',
                'string',
                'max:13',
            ],

            'kode_pos' => [
                'nullable',
                'string',
                'max:10',
            ],

            'rt_rw' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^\d{1,3}\s*\/\s*\d{1,3}$/',
            ],

            'alamat_lengkap' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use ($request) {

            $createdBy = auth()->user()?->name ?? 'admin';

            /*
            |--------------------------------------------------------------------------
            | Generate kode keluarga
            |--------------------------------------------------------------------------
            */
            $kodeKeluarga =
                'KLG-' . strtoupper(Str::random(10));

            /*
            |--------------------------------------------------------------------------
            | Cari NIK Kepala Keluarga
            |--------------------------------------------------------------------------
            */
            $nikKepalaKeluarga = null;

            foreach ($request->anggota as $anggota) {

                if (
                    isset($anggota['status_keluarga']) &&
                    $anggota['status_keluarga'] === 'Kepala Keluarga'
                ) {
                    $nikKepalaKeluarga =
                        $anggota['nik'] ?? null;

                    break;
                }
            }

            if (
                !$nikKepalaKeluarga &&
                !empty($request->anggota)
            ) {
                $nikKepalaKeluarga =
                    $request->anggota[0]['nik'] ?? null;
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan RT/RW
            |--------------------------------------------------------------------------
            */
            $rtRwId = null;

            if (
                $request->filled('rt_rw') &&
                $request->filled('kelurahan_id')
            ) {
                $rtRw = $this->simpanRtRw(
                    $request->kelurahan_id,
                    $request->rt_rw
                );

                $rtRwId = $rtRw?->id;
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan keluarga
            |--------------------------------------------------------------------------
            */
            $keluarga = Keluarga::create([
                'kode' => $kodeKeluarga,

                'no_kk' => $request->nomor_kk,

                'nik' => $nikKepalaKeluarga,

                'nama_lengkap' =>
                    $request->nama_kepala_keluarga,

                'status_keluarga' =>
                    'Kepala Keluarga',

                'kecamatan_id' =>
                    $request->kecamatan_id,

                'kelurahan_id' =>
                    $request->kelurahan_id,

                'rt_rw_id' =>
                    $rtRwId,

                'kode_pos' =>
                    $request->kode_pos,

                'alamat_lengkap' =>
                    $request->alamat_lengkap,

                'created_by' =>
                    $createdBy,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Simpan anggota keluarga
            |--------------------------------------------------------------------------
            */
            foreach ($request->anggota as $anggota) {

                $statusKeluarga =
                    $anggota['status_keluarga'] ?? '';

                if ($statusKeluarga === 'Lainnya') {

                    $customStatus =
                        trim(
                            (string) (
                                $anggota[
                                    'status_keluarga_lainnya'
                                ] ?? ''
                            )
                        );

                    $statusKeluarga =
                        $customStatus !== ''
                            ? $customStatus
                            : 'Lainnya';
                }

                KeluargaAnggota::create([
                    'kode' =>
                        'ANG-' .
                        strtoupper(
                            Str::random(10)
                        ),

                    'keluarga_kode' =>
                        $keluarga->kode,

                    'nik' =>
                        $anggota['nik'],

                    'nama_lengkap' =>
                        $anggota['nama_lengkap'],

                    'status_keluarga' =>
                        $statusKeluarga,

                    'created_by' =>
                        $createdBy,
                ]);
            }
        });

        return redirect()
            ->route('responden.index')
            ->with(
                'success',
                'Data responden berhasil ditambahkan.'
            );
    }

    /**
     * Menampilkan halaman edit responden.
     */
    public function edit($id)
    {
        $keluarga = Keluarga::with([
            'anggota',
            'rtRw',
        ])->findOrFail($id);

        $kecamatans = DB::table('kecamatans')
            ->orderBy('deskripsi', 'asc')
            ->get();

        return view(
            'admin.responden.edit',
            compact(
                'keluarga',
                'kecamatans'
            )
        );
    }

    /**
     * Mengambil data responden untuk modal edit.
     */
    public function editData($id)
    {
        $keluarga = Keluarga::with([
            'anggota',
            'rtRw',
        ])->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Ambil nama kecamatan
        |--------------------------------------------------------------------------
        */
        $kecamatan = null;

        if ($keluarga->kecamatan_id) {
            $kecamatan = DB::table('kecamatans')
                ->where(
                    'kecamatan_id',
                    $keluarga->kecamatan_id
                )
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil nama kelurahan
        |--------------------------------------------------------------------------
        */
        $kelurahan = null;

        if ($keluarga->kelurahan_id) {
            $kelurahan = DB::table('kelurahans')
                ->where(
                    'kelurahan_id',
                    $keluarga->kelurahan_id
                )
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Bentuk RT/RW untuk form
        |--------------------------------------------------------------------------
        */
        $rtRw = null;

        if ($keluarga->rtRw) {
            $rtRw =
                $keluarga->rtRw->rt .
                '/' .
                $keluarga->rtRw->rw;
        }

        return response()->json([
            'id' =>
                $keluarga->id,

            'kode' =>
                $keluarga->kode,

            'no_kk' =>
                $keluarga->no_kk,

            'nik' =>
                $keluarga->nik,

            'nama_lengkap' =>
                $keluarga->nama_lengkap,

            'status_keluarga' =>
                $keluarga->status_keluarga,

            'provinsi' =>
                'Jawa Timur',

            'daerah' =>
                'Kota Pasuruan',

            'kecamatan_id' =>
                $keluarga->kecamatan_id,

            'kecamatan' =>
                $kecamatan?->deskripsi,

            'kelurahan_id' =>
                $keluarga->kelurahan_id,

            'kelurahan' =>
                $kelurahan?->deskripsi,

            'kode_pos' =>
                $keluarga->kode_pos,

            'rt_rw' =>
                $rtRw,

            'alamat_lengkap' =>
                $keluarga->alamat_lengkap,

            'jml_keluarga' =>
                $keluarga->anggota->count(),

            'anggota' =>
                $keluarga->anggota
                    ->sortBy(function ($anggota) {
                        $statusPriority = [
                            'Kepala Keluarga' => 1,
                            'Istri' => 2,
                            'Suami' => 3,
                            'Anak' => 4,
                            'Orang Tua' => 5,
                            'Saudara' => 6,
                            'Famili' => 7,
                        ];

                        $status = (string) ($anggota->status_keluarga ?? '');

                        return ($statusPriority[$status] ?? 999) * 1000000 + ($anggota->id ?? 0);
                    })
                    ->map(function ($anggota) {
                        $statusNormal = [
                            'Kepala Keluarga',
                            'Istri',
                            'Suami',
                            'Anak',
                            'Orang Tua',
                            'Saudara',
                            'Famili',
                        ];

                        $status = (string) ($anggota->status_keluarga ?? '');

                        return [
                            'id' => $anggota->id,
                            'kode' => $anggota->kode,
                            'nik' => $anggota->nik,
                            'nama_lengkap' => $anggota->nama_lengkap,
                            'status_keluarga' => $status,
                            'status_keluarga_lainnya' =>
                                $status !== '' && !in_array($status, $statusNormal, true)
                                    ? $status
                                    : '',
                        ];
                    })
                    ->values(),
        ]);
    }

    /**
     * Mengambil Kelurahan berdasarkan Kecamatan.
     */
    public function getKelurahan($kecamatanId)
    {
        $kecamatan = DB::table('kecamatans')
            ->where(
                'kecamatan_id',
                $kecamatanId
            )
            ->first();

        if (!$kecamatan) {
            return response()->json([
                'success' => false,
                'message' =>
                    'Kecamatan tidak ditemukan.',
                'data' => [],
            ], 404);
        }

        $kelurahans = DB::table('kelurahans')
            ->where(
                'kecamatan_id',
                $kecamatanId
            )
            ->orderBy(
                'deskripsi',
                'asc'
            )
            ->get([
                'kelurahan_id',
                'kecamatan_id',
                'deskripsi',
            ]);

        return response()->json([
            'success' => true,
            'data' => $kelurahans,
        ]);
    }

    /**
     * Memperbarui data responden.
     */
    public function update(
        Request $request,
        $id
    ) {
        if (!$request->filled('nomor_kk') && $request->filled('no_kk')) {
            $request->merge([
                'nomor_kk' => $request->input('no_kk'),
            ]);
        }

        if (!$request->filled('no_kk') && $request->filled('nomor_kk')) {
            $request->merge([
                'no_kk' => $request->input('nomor_kk'),
            ]);
        }

        $request->validate([
            'nomor_kk' => [
                'required',
                'digits:16',
            ],

            'nama_kepala_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota' => [
                'required',
                'array',
                'min:1',
            ],

            'anggota.*.nik' => [
                'required',
                'digits:16',
            ],

            'anggota.*.nama_lengkap' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota.*.status_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota.*.status_keluarga_lainnya' => [
                'nullable',
                'string',
                'max:255',
            ],

            'provinsi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'daerah' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kecamatan_id' => [
                'nullable',
                'string',
                'max:13',
            ],

            'kelurahan_id' => [
                'nullable',
                'string',
                'max:13',
            ],

            'kode_pos' => [
                'nullable',
                'string',
                'max:10',
            ],

            'rt_rw' => [
                'nullable',
                'string',
                'max:20',
                'regex:/^\d{1,3}\s*\/\s*\d{1,3}$/',
            ],

            'alamat_lengkap' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $id
        ) {

            $updatedBy =
                auth()->user()?->name ?? 'admin';

            $keluarga =
                Keluarga::findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Cari NIK Kepala Keluarga
            |--------------------------------------------------------------------------
            */
            $nikKepalaKeluarga = null;

            foreach ($request->anggota as $anggota) {

                if (
                    isset($anggota['status_keluarga']) &&
                    $anggota['status_keluarga']
                    === 'Kepala Keluarga'
                ) {
                    $nikKepalaKeluarga =
                        $anggota['nik'] ?? null;

                    break;
                }
            }

            if (
                !$nikKepalaKeluarga &&
                !empty($request->anggota)
            ) {
                $nikKepalaKeluarga =
                    $request->anggota[0]['nik'] ?? null;
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan / ambil RT RW
            |--------------------------------------------------------------------------
            */
            $rtRwId = null;

            if (
                $request->filled('rt_rw') &&
                $request->filled('kelurahan_id')
            ) {
                $rtRw = $this->simpanRtRw(
                    $request->kelurahan_id,
                    $request->rt_rw
                );

                $rtRwId = $rtRw?->id;
            }

            /*
            |--------------------------------------------------------------------------
            | Update keluarga
            |--------------------------------------------------------------------------
            */
            $keluarga->update([
                'no_kk' =>
                    $request->nomor_kk,

                'nik' =>
                    $nikKepalaKeluarga,

                'nama_lengkap' =>
                    $request->nama_kepala_keluarga,

                'status_keluarga' =>
                    'Kepala Keluarga',

                'kecamatan_id' =>
                    $request->kecamatan_id,

                'kelurahan_id' =>
                    $request->kelurahan_id,

                'rt_rw_id' =>
                    $rtRwId,

                'kode_pos' =>
                    $request->kode_pos,

                'alamat_lengkap' =>
                    $request->alamat_lengkap,

                'updated_by' =>
                    $updatedBy,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Hapus anggota lama
            |--------------------------------------------------------------------------
            */
            $keluarga->anggota()->delete();

            /*
            |--------------------------------------------------------------------------
            | Simpan anggota terbaru
            |--------------------------------------------------------------------------
            */
            foreach ($request->anggota as $anggota) {

                $statusKeluarga =
                    $anggota['status_keluarga'] ?? '';

                if ($statusKeluarga === 'Lainnya') {

                    $customStatus =
                        trim(
                            (string) (
                                $anggota[
                                    'status_keluarga_lainnya'
                                ] ?? ''
                            )
                        );

                    $statusKeluarga =
                        $customStatus !== ''
                            ? $customStatus
                            : 'Lainnya';
                }

                KeluargaAnggota::create([
                    'kode' =>
                        'ANG-' .
                        strtoupper(
                            Str::random(10)
                        ),

                    'keluarga_kode' =>
                        $keluarga->kode,

                    'nik' =>
                        $anggota['nik'],

                    'nama_lengkap' =>
                        $anggota['nama_lengkap'],

                    'status_keluarga' =>
                        $statusKeluarga,

                    'created_by' =>
                        $updatedBy,
                ]);
            }
        });

        return redirect()
            ->route('responden.index')
            ->with(
                'success',
                'Data responden berhasil diperbarui.'
            );
    }

    /**
     * Menyimpan RT/RW ke tabel rt_rws.
     */
    private function simpanRtRw(
        $kelurahanId,
        $rtRwValue
    ) {
        $rtRwValue =
            trim((string) $rtRwValue);

        $parts =
            preg_split(
                '/\s*\/\s*/',
                $rtRwValue
            );

        if (
            count($parts) !== 2 ||
            $parts[0] === '' ||
            $parts[1] === ''
        ) {
            return null;
        }

        $rt =
            str_pad(
                trim($parts[0]),
                3,
                '0',
                STR_PAD_LEFT
            );

        $rw =
            str_pad(
                trim($parts[1]),
                3,
                '0',
                STR_PAD_LEFT
            );

        return RtRw::firstOrCreate([
            'kelurahan_id' =>
                $kelurahanId,

            'rt' =>
                $rt,

            'rw' =>
                $rw,
        ]);
    }

    /**
     * Menghapus data responden.
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $keluarga =
                Keluarga::findOrFail($id);

            $keluarga->anggota()->delete();

            $keluarga->delete();
        });

        return redirect()
            ->route('responden.index')
            ->with(
                'success',
                'Data responden berhasil dihapus.'
            );
    }

    /**
     * Detail data responden.
     */
    public function detail($id)
    {
        $keluarga = Keluarga::with([
            'anggota',
            'rtRw',
        ])->findOrFail($id);

        $kecamatan = null;

        if ($keluarga->kecamatan_id) {
            $kecamatan = DB::table('kecamatans')
                ->where(
                    'kecamatan_id',
                    $keluarga->kecamatan_id
                )
                ->first();
        }

        $kelurahan = null;

        if ($keluarga->kelurahan_id) {
            $kelurahan = DB::table('kelurahans')
                ->where(
                    'kelurahan_id',
                    $keluarga->kelurahan_id
                )
                ->first();
        }

        $rtRw = null;

        if ($keluarga->rtRw) {
            $rtRw =
                $keluarga->rtRw->rt .
                '/' .
                $keluarga->rtRw->rw;
        }

        return response()->json([
            'id' =>
                $keluarga->id,

            'kode' =>
                $keluarga->kode,

            'no_kk' =>
                $keluarga->no_kk,

            'nik' =>
                $keluarga->nik,

            'nama_lengkap' =>
                $keluarga->nama_lengkap,

            'status_keluarga' =>
                $keluarga->status_keluarga,

            'provinsi' =>
                'Jawa Timur',

            'daerah' =>
                'Kota Pasuruan',

            'kecamatan_id' =>
                $keluarga->kecamatan_id,

            'kecamatan' =>
                $kecamatan?->deskripsi,

            'kelurahan_id' =>
                $keluarga->kelurahan_id,

            'kelurahan' =>
                $kelurahan?->deskripsi,

            'kode_pos' =>
                $keluarga->kode_pos,

            'rt_rw' =>
                $rtRw,

            'alamat_lengkap' =>
                $keluarga->alamat_lengkap,

            'jml_keluarga' =>
                $keluarga->anggota->count(),

            'anggota' =>
                $keluarga->anggota,
        ]);
    }
}