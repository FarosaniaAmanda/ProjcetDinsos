<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaAnggota;
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
        $keluargas = Keluarga::with('anggota')
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
     *
     * DATA YANG DISIMPAN:
     * - keluargas
     * - keluarga_anggotas
     *
     * part1_keluarga TIDAK digunakan di sini.
     */
    public function store(Request $request)
    {
        $request->validate([
            /*
            |--------------------------------------------------------------------------
            | Data keluarga
            |--------------------------------------------------------------------------
            */
            'nomor_kk' => [
                'required',
                'digits:16',
            ],

            'nama_kepala_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Anggota keluarga
            |--------------------------------------------------------------------------
            */
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

            /*
            |--------------------------------------------------------------------------
            | Alamat
            |--------------------------------------------------------------------------
            |
            | Validasi tetap dipertahankan karena form responden
            | masih mengirimkan data alamat.
            |
            */
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
                'max:8',
            ],

            'kelurahan_id' => [
                'nullable',
                'string',
                'max:13',
            ],

            'kode_pos' => [
                'nullable',
                'string',
                'max:16',
            ],

            'rt_rw' => [
                'nullable',
                'string',
                'max:16',
            ],

            'alamat_lengkap' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        DB::transaction(function () use ($request) {

            /*
            |--------------------------------------------------------------------------
            | User yang membuat data
            |--------------------------------------------------------------------------
            */
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

            /*
            |--------------------------------------------------------------------------
            | Jika tidak ada Kepala Keluarga,
            | gunakan anggota pertama
            |--------------------------------------------------------------------------
            */
            if (
                !$nikKepalaKeluarga &&
                !empty($request->anggota)
            ) {
                $nikKepalaKeluarga =
                    $request->anggota[0]['nik'] ?? null;
            }

            /*
            |--------------------------------------------------------------------------
            | Simpan data keluarga
            |--------------------------------------------------------------------------
            */
            $keluarga = Keluarga::create([
                'kode' =>
                    $kodeKeluarga,

                'no_kk' =>
                    $request->nomor_kk,

                'nik' =>
                    $nikKepalaKeluarga,

                'nama_lengkap' =>
                    $request->nama_kepala_keluarga,

                'status_keluarga' =>
                    'Kepala Keluarga',

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

                /*
                |--------------------------------------------------------------------------
                | Jika status = Lainnya
                |--------------------------------------------------------------------------
                */
                if ($statusKeluarga === 'Lainnya') {

                    $customStatus = trim(
                        (string) (
                            $anggota['status_keluarga_lainnya']
                            ?? ''
                        )
                    );

                    $statusKeluarga =
                        $customStatus !== ''
                            ? $customStatus
                            : 'Lainnya';
                }

                KeluargaAnggota::create([
                    'kode' =>
                        'ANG-' . strtoupper(
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

            /*
            |--------------------------------------------------------------------------
            | PENTING
            |--------------------------------------------------------------------------
            |
            | Tidak ada penyimpanan ke part1_keluarga.
            |
            | part1_keluarga akan digunakan khusus untuk
            | menyimpan jawaban Kuisioner Part 1.
            |
            */
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
        $keluarga = Keluarga::with('anggota')
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
        $keluarga = Keluarga::with('anggota')
            ->findOrFail($id);

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

            /*
            |--------------------------------------------------------------------------
            | Alamat
            |--------------------------------------------------------------------------
            |
            | Untuk sementara dikosongkan karena data alamat sebelumnya
            | disimpan di part1_keluarga.
            |
            | Nanti alamat bisa kita tambahkan ke tabel keluarga
            | melalui migration khusus.
            |
            */
            'provinsi' => null,

            'daerah' => null,

            'kecamatan_id' => null,

            'kecamatan' => null,

            'kelurahan_id' => null,

            'kelurahan' => null,

            'kode_pos' => null,

            'rt_rw' => null,

            'alamat_lengkap' => null,

            'jml_keluarga' =>
                $keluarga->anggota->count(),

            'anggota' =>
                $keluarga->anggota,
        ]);
    }

    /**
     * Mengambil Kelurahan berdasarkan Kecamatan.
     *
     * Struktur tabel kelurahans:
     * - kelurahan_id
     * - kecamatan_id
     * - deskripsi
     */
    public function getKelurahan($kecamatanId)
    {
        /*
        |--------------------------------------------------------------------------
        | Pastikan Kecamatan memang ada
        |--------------------------------------------------------------------------
        */
        $kecamatan = DB::table('kecamatans')
            ->where(
                'kecamatan_id',
                $kecamatanId
            )
            ->first();

        if (!$kecamatan) {

            return response()->json([
                'success' => false,
                'message' => 'Kecamatan tidak ditemukan.',
                'data' => [],
            ], 404);
        }

        /*
        |--------------------------------------------------------------------------
        | Ambil Kelurahan berdasarkan kecamatan_id
        |--------------------------------------------------------------------------
        */
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
     *
     * Hanya:
     * - keluargas
     * - keluarga_anggotas
     *
     * Tidak menyentuh part1_keluarga.
     */
    public function update(
        Request $request,
        $id
    ) {
        $request->validate([
            /*
            |--------------------------------------------------------------------------
            | Data keluarga
            |--------------------------------------------------------------------------
            */
            'nomor_kk' => [
                'required',
                'digits:16',
            ],

            'nama_kepala_keluarga' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | Anggota keluarga
            |--------------------------------------------------------------------------
            */
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

            /*
            |--------------------------------------------------------------------------
            | Alamat
            |--------------------------------------------------------------------------
            */
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
                'max:8',
            ],

            'kelurahan_id' => [
                'nullable',
                'string',
                'max:13',
            ],

            'kode_pos' => [
                'nullable',
                'string',
                'max:16',
            ],

            'rt_rw' => [
                'nullable',
                'string',
                'max:16',
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

            /*
            |--------------------------------------------------------------------------
            | Ambil keluarga
            |--------------------------------------------------------------------------
            */
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

            /*
            |--------------------------------------------------------------------------
            | Jika tidak ditemukan,
            | gunakan anggota pertama
            |--------------------------------------------------------------------------
            */
            if (
                !$nikKepalaKeluarga &&
                !empty($request->anggota)
            ) {

                $nikKepalaKeluarga =
                    $request->anggota[0]['nik'] ?? null;
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
                        'ANG-' . strtoupper(
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

            /*
            |--------------------------------------------------------------------------
            | TIDAK ADA UPDATE part1_keluarga
            |--------------------------------------------------------------------------
            |
            | part1_keluarga hanya untuk jawaban Kuisioner Part 1.
            |
            */
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
     *
     * Hanya menghapus:
     * - keluarga_anggotas
     * - keluargas
     *
     * part1_keluarga TIDAK dihapus.
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {

            $keluarga =
                Keluarga::findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Hapus anggota keluarga
            |--------------------------------------------------------------------------
            */
            $keluarga->anggota()->delete();

            /*
            |--------------------------------------------------------------------------
            | Hapus keluarga
            |--------------------------------------------------------------------------
            */
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
        $keluarga =
            Keluarga::with('anggota')
                ->findOrFail($id);

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

            /*
            |--------------------------------------------------------------------------
            | Alamat
            |--------------------------------------------------------------------------
            */
            'provinsi' => null,

            'daerah' => null,

            'kecamatan' => null,

            'kelurahan' => null,

            'kode_pos' => null,

            'rt_rw' => null,

            'alamat_lengkap' => null,

            'jml_keluarga' =>
                $keluarga->anggota->count(),

            'anggota' =>
                $keluarga->anggota,
        ]);
    }
}