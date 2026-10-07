<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaAnggota;
use App\Models\KeluargaPart1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RespondenController extends Controller
{
    /**
     * Menampilkan daftar responden.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $search = is_string($search) ? trim($search) : '';

        $query = Keluarga::with([
            'anggota' => function ($query) {
                $query
                    ->where('status_keluarga', '!=', 'Kepala Keluarga')
                    ->orderBy('id');
            },
        ]);

        if ($search !== '') {
            $query->where(function ($query) use ($search) {
                $keyword = "%{$search}%";

                $query
                    ->where('no_kk', 'like', $keyword)
                    ->orWhere('nik', 'like', $keyword)
                    ->orWhere('nama_lengkap', 'like', $keyword)
                    ->orWhere('alamat_lengkap', 'like', $keyword)
                    ->orWhereHas('anggota', function ($query) use ($keyword) {
                        $query
                            ->where('nik', 'like', $keyword)
                            ->orWhere('nama_lengkap', 'like', $keyword);
                    });
            });
        }

        $keluargas = $query
            ->latest()
            ->paginate(5)
            ->withQueryString();

        $kecamatans = DB::table('kecamatans')
            ->orderBy('deskripsi', 'asc')
            ->get();

        $kelurahans = DB::table('kelurahans')
            ->get()
            ->keyBy('kelurahan_id');

        $kecamatansById = $kecamatans->keyBy('kecamatan_id');

        foreach ($keluargas as $keluarga) {
            $keluarga->nama_kepala_keluarga = $keluarga->nama_lengkap;

            $keluarga->kecamatan =
                $kecamatansById->get($keluarga->kecamatan_id)?->deskripsi;

            $keluarga->kelurahan =
                $kelurahans->get($keluarga->kelurahan_id)?->deskripsi;

            $keluarga->jml_keluarga =
                $keluarga->anggota->count();
        }

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
        /*
        |--------------------------------------------------------------------------
        | Samakan nomor KK
        |--------------------------------------------------------------------------
        */

        if (
            ! $request->filled('nomor_kk')
            && $request->filled('no_kk')
        ) {
            $request->merge([
                'nomor_kk' => $request->input('no_kk'),
            ]);
        }

        if (
            ! $request->filled('no_kk')
            && $request->filled('nomor_kk')
        ) {
            $request->merge([
                'no_kk' => $request->input('nomor_kk'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'nomor_kk' => [
                'required',
                'digits:16',
            ],

            'nik_kepala_keluarga' => [
                'required',
                'digits:16',
            ],

            'nama_kepala_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota' => [
                'nullable',
                'array',
                'max:19',
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

            'rt' => [
                'nullable',
                'string',
                'max:5',
            ],

            'rw' => [
                'nullable',
                'string',
                'max:5',
            ],

            'alamat_lengkap' => [
                'nullable',
                'string',
                'max:255',
            ],

            'geotangging' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        DB::transaction(function () use ($request) {

            $createdBy =
                auth()->user()?->name ?? 'admin';

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

            $nikKepalaKeluarga =
                $request->nik_kepala_keluarga;

            /*
            |--------------------------------------------------------------------------
            | Siapkan alamat lengkap + geotagging
            |--------------------------------------------------------------------------
            */

            $alamatLengkap =
                $this->gabungkanAlamatDenganGeotag(
                    $request->alamat_lengkap,
                    $request->geotangging
                );

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

                'kode_pos' =>
                    $request->kode_pos,

                'alamat_lengkap' =>
                    $alamatLengkap,

                'created_by' =>
                    $createdBy,
            ]);

            // RT/RW berada langsung di tabel keluargas.
            DB::table('keluargas')
                ->where('id', $keluarga->id)
                ->update([
                    'rt' => $request->input('rt'),
                    'rw' => $request->input('rw'),
                    'updated_at' => now(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | Simpan anggota keluarga
            |--------------------------------------------------------------------------
            */

            foreach (
                $request->input('anggota', [])
                as $anggota
            ) {

                $statusKeluarga =
                    $anggota['status_keluarga'] ?? '';

                /*
                |--------------------------------------------------------------------------
                | Jika status = Lainnya
                |--------------------------------------------------------------------------
                */

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
        $keluarga =
            Keluarga::with([
                'anggota',
                            ])
                ->findOrFail($id);

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
        $keluarga =
            Keluarga::with('anggota')
                ->findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Ambil nama kecamatan
        |--------------------------------------------------------------------------
        */

        $kecamatan = null;

        if ($keluarga->kecamatan_id) {

            $kecamatan =
                DB::table('kecamatans')
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

            $kelurahan =
                DB::table('kelurahans')
                    ->where(
                        'kelurahan_id',
                        $keluarga->kelurahan_id
                    )
                    ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil koordinat dari alamat_lengkap
        |--------------------------------------------------------------------------
        */

        $geotangging =
            $this->ambilGeotagDariAlamat(
                $keluarga->alamat_lengkap
            );

        /*
        |--------------------------------------------------------------------------
        | Ambil alamat tanpa koordinat
        |--------------------------------------------------------------------------
        */

        $alamatBersih =
            $this->hapusGeotagDariAlamat(
                $keluarga->alamat_lengkap
            );

        /*
        |--------------------------------------------------------------------------
        | Urutkan anggota
        |--------------------------------------------------------------------------
        */

        $anggota =
            $keluarga->anggota
                ->where(
                    'status_keluarga',
                    '!=',
                    'Kepala Keluarga'
                )
                ->sortBy(
                    function ($anggota) {

                        $statusPriority = [
                            'Kepala Keluarga' => 1,
                            'Istri' => 2,
                            'Suami' => 3,
                            'Anak' => 4,
                            'Orang Tua' => 5,
                            'Saudara' => 6,
                            'Famili' => 7,
                        ];

                        $status =
                            (string) (
                                $anggota->status_keluarga
                                ?? ''
                            );

                        return (
                            $statusPriority[$status]
                            ?? 999
                        ) * 1000000
                        + (
                            $anggota->id
                            ?? 0
                        );
                    }
                )
                ->map(
                    function ($anggota) {

                        $statusNormal = [
                            'Kepala Keluarga',
                            'Istri',
                            'Suami',
                            'Anak',
                            'Orang Tua',
                            'Saudara',
                            'Famili',
                        ];

                        $status =
                            (string) (
                                $anggota->status_keluarga
                                ?? ''
                            );

                        return [
                            'id' =>
                                $anggota->id,

                            'kode' =>
                                $anggota->kode,

                            'nik' =>
                                $anggota->nik,

                            'nama_lengkap' =>
                                $anggota->nama_lengkap,

                            'status_keluarga' =>
                                $status,

                            'status_keluarga_lainnya' =>
                                $status !== ''
                                && ! in_array(
                                    $status,
                                    $statusNormal,
                                    true
                                )
                                    ? $status
                                    : '',
                        ];
                    }
                )
                ->values();

        return response()->json([
            'id' =>
                $keluarga->id,

            'kode' =>
                $keluarga->kode,

            'no_kk' =>
                $keluarga->no_kk,

            'nik' =>
                $keluarga->nik,

            'nik_kepala_keluarga' =>
                $keluarga->nik,

            'nama_lengkap' =>
                $keluarga->nama_lengkap,

            'nama_kepala_keluarga' =>
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

            'rt' =>
                $keluarga->rt,

            'rw' =>
                $keluarga->rw,

            'rt_rw' =>
                trim((string) ($keluarga->rt ?? '') . '/' . (string) ($keluarga->rw ?? ''), '/'),

            'alamat_lengkap' =>
                $alamatBersih,

            /*
            |--------------------------------------------------------------------------
            | Kirim geotagging ke JavaScript
            |--------------------------------------------------------------------------
            */

            'geotangging' =>
                $geotangging,

            'jml_keluarga' =>
                $keluarga->anggota
                    ->where(
                        'status_keluarga',
                        '!=',
                        'Kepala Keluarga'
                    )
                    ->count(),

            'anggota' =>
                $anggota,
        ]);
    }

    /**
     * Mengambil Kelurahan berdasarkan Kecamatan.
     */
    public function getKelurahan($kecamatanId)
    {
        $kecamatan =
            DB::table('kecamatans')
                ->where(
                    'kecamatan_id',
                    $kecamatanId
                )
                ->first();

        if (! $kecamatan) {

            return response()->json([
                'success' => false,

                'message' =>
                    'Kecamatan tidak ditemukan.',

                'data' => [],
            ], 404);
        }

        $kelurahans =
            DB::table('kelurahans')
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

            'data' =>
                $kelurahans,
        ]);
    }

    /**
     * Memperbarui data responden.
     */
    public function update(
        Request $request,
        $id
    ) {

        /*
        |--------------------------------------------------------------------------
        | Samakan nomor KK
        |--------------------------------------------------------------------------
        */

        if (
            ! $request->filled('nomor_kk')
            && $request->filled('no_kk')
        ) {
            $request->merge([
                'nomor_kk' =>
                    $request->input('no_kk'),
            ]);
        }

        if (
            ! $request->filled('no_kk')
            && $request->filled('nomor_kk')
        ) {
            $request->merge([
                'no_kk' =>
                    $request->input('nomor_kk'),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'nomor_kk' => [
                'required',
                'digits:16',
            ],

            'nik_kepala_keluarga' => [
                'required',
                'digits:16',
            ],

            'nama_kepala_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            'anggota' => [
                'nullable',
                'array',
                'max:19',
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

            'rt' => [
                'nullable',
                'string',
                'max:5',
            ],

            'rw' => [
                'nullable',
                'string',
                'max:5',
            ],

            'alamat_lengkap' => [
                'nullable',
                'string',
                'max:255',
            ],

            'geotangging' => [
                'nullable',
                'string',
                'max:100',
            ],
        ]);

        DB::transaction(function () use (
            $request,
            $id
        ) {

            $updatedBy =
                auth()->user()?->name ?? 'admin';

            /*
            |--------------------------------------------------------------------------
            | Cari keluarga
            |--------------------------------------------------------------------------
            */

            $keluarga =
                Keluarga::findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Cari NIK Kepala Keluarga
            |--------------------------------------------------------------------------
            */

            $nikKepalaKeluarga =
                $request->nik_kepala_keluarga;

            /*
            |--------------------------------------------------------------------------
            | Gabungkan alamat + geotagging
            |--------------------------------------------------------------------------
            */

            $alamatLengkap =
                $this->gabungkanAlamatDenganGeotag(
                    $request->alamat_lengkap,
                    $request->geotangging
                );

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

                'kode_pos' =>
                    $request->kode_pos,

                'alamat_lengkap' =>
                    $alamatLengkap,

                'updated_by' =>
                    $updatedBy,
            ]);

            // RT/RW berada langsung di tabel keluargas.
            DB::table('keluargas')
                ->where('id', $keluarga->id)
                ->update([
                    'rt' => $request->input('rt'),
                    'rw' => $request->input('rw'),
                    'updated_at' => now(),
                ]);

            /*
            |--------------------------------------------------------------------------
            | Hapus anggota lama
            |--------------------------------------------------------------------------
            */

            $keluarga
                ->anggota()
                ->delete();

            /*
            |--------------------------------------------------------------------------
            | Simpan anggota terbaru
            |--------------------------------------------------------------------------
            */

            foreach (
                $request->input('anggota', [])
                as $anggota
            ) {

                $statusKeluarga =
                    $anggota['status_keluarga']
                    ?? '';

                /*
                |--------------------------------------------------------------------------
                | Jika status = Lainnya
                |--------------------------------------------------------------------------
                */

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
     * Menghapus data responden.
     */
    public function destroy(Request $request, $id)
    {
        $keluarga =
            Keluarga::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | LINDUNGI RESPONDEN YANG SUDAH PERNAH DIBUKA DI KUISIONER
        |--------------------------------------------------------------------------
        | Jika responden sudah pernah membuka/memulai kuisioner, maka
        | KeluargaPart1 sudah memiliki data dengan status draft, selesai,
        | atau status lainnya. Semua kondisi tersebut tidak boleh dihapus.
        |--------------------------------------------------------------------------
        */

        $dataKuisioner =
            KeluargaPart1::where(
                'keluarga_periode_kode',
                $keluarga->kode
            )
                ->orderByDesc('id')
                ->first();

        if ($dataKuisioner) {
            $status = strtolower(trim((string) ($dataKuisioner->status ?? '')));

            if ($status === 'draft') {
                $pesan =
                    'Data responden tidak dapat dihapus karena sudah masuk draft kuisioner.';
            } elseif ($status === 'selesai') {
                $pesan =
                    'Data responden tidak dapat dihapus karena kuisioner sudah disubmit.';
            } else {
                $pesan =
                    'Data responden tidak dapat dihapus karena sudah masuk proses kuisioner.';
            }

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'blocked' => true,
                    'message' => $pesan,
                ], 422);
            }

            return redirect()
                ->route('responden.index')
                ->with('error', $pesan);
        }

        DB::transaction(function () use ($keluarga) {

            $keluarga
                ->anggota()
                ->delete();

            $keluarga->delete();
        });

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'blocked' => false,
                'message' => 'Data responden berhasil dihapus.',
                'id' => $keluarga->id,
            ]);
        }

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
        $keluarga =
            Keluarga::with('anggota')
                ->findOrFail($id);

        $kecamatan = null;

        if ($keluarga->kecamatan_id) {

            $kecamatan =
                DB::table('kecamatans')
                    ->where(
                        'kecamatan_id',
                        $keluarga->kecamatan_id
                    )
                    ->first();
        }

        $kelurahan = null;

        if ($keluarga->kelurahan_id) {

            $kelurahan =
                DB::table('kelurahans')
                    ->where(
                        'kelurahan_id',
                        $keluarga->kelurahan_id
                    )
                    ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Pisahkan alamat dari koordinat
        |--------------------------------------------------------------------------
        */

        $alamatBersih =
            $this->hapusGeotagDariAlamat(
                $keluarga->alamat_lengkap
            );

        $geotangging =
            $this->ambilGeotagDariAlamat(
                $keluarga->alamat_lengkap
            );

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

            'rt' =>
                $keluarga->rt,

            'rw' =>
                $keluarga->rw,

            'rt_rw' =>
                trim((string) ($keluarga->rt ?? '') . '/' . (string) ($keluarga->rw ?? ''), '/'),

            'alamat_lengkap' =>
                $alamatBersih,

            'geotangging' =>
                $geotangging,

            'jml_keluarga' =>
                $keluarga->anggota->count(),

            'anggota' =>
                $keluarga->anggota,
        ]);
    }

    /**
     * Menggabungkan alamat lengkap dengan koordinat.
     *
     * Contoh hasil:
     *
     * Jl. Panglima Sudirman No. 10
     * Koordinat: -7.645321, 112.906543
     */
    private function gabungkanAlamatDenganGeotag(
        ?string $alamat,
        ?string $geotangging
    ): string {

        /*
        |--------------------------------------------------------------------------
        | Bersihkan alamat dari koordinat lama
        |--------------------------------------------------------------------------
        */

        $alamat =
            $this->hapusGeotagDariAlamat(
                $alamat
            );

        $alamat =
            trim($alamat);

        $geotangging =
            trim((string) $geotangging);

        /*
        |--------------------------------------------------------------------------
        | Jika tidak ada geotagging
        |--------------------------------------------------------------------------
        */

        if ($geotangging === '') {
            return mb_substr(
                $alamat,
                0,
                255
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Tambahkan koordinat
        |--------------------------------------------------------------------------
        */

        $tambahan =
            'Koordinat: ' .
            $geotangging;

        if ($alamat !== '') {

            $hasil =
                $alamat .
                "\n" .
                $tambahan;

        } else {

            $hasil =
                $tambahan;
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan tidak melebihi 255 karakter
        |--------------------------------------------------------------------------
        */

        return mb_substr(
            $hasil,
            0,
            255
        );
    }

    /**
     * Menghapus bagian "Koordinat: ..." dari alamat.
     */
    private function hapusGeotagDariAlamat(
        ?string $alamat
    ): string {

        if (
            $alamat === null
            || trim($alamat) === ''
        ) {
            return '';
        }

        /*
        |--------------------------------------------------------------------------
        | Hapus:
        |
        | Koordinat: -7.123456, 112.123456
        |--------------------------------------------------------------------------
        */

        $alamat =
            preg_replace(
                '/\s*Koordinat:\s*-?\d+(?:\.\d+)?\s*,\s*-?\d+(?:\.\d+)?/i',
                '',
                $alamat
            );

        return trim(
            (string) $alamat
        );
    }

    /**
     * Mengambil koordinat dari alamat_lengkap.
     *
     * Hasil:
     *
     * -7.645321, 112.906543
     */
    private function ambilGeotagDariAlamat(
        ?string $alamat
    ): ?string {

        if (
            $alamat === null
            || trim($alamat) === ''
        ) {
            return null;
        }

        if (
            preg_match(
                '/Koordinat:\s*(-?\d+(?:\.\d+)?)\s*,\s*(-?\d+(?:\.\d+)?)/i',
                $alamat,
                $matches
            )
        ) {

            return
                $matches[1] .
                ', ' .
                $matches[2];
        }

        return null;
    }
}