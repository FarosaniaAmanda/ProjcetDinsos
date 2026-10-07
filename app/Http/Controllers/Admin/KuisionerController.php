<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\KeluargaPart1;
use App\Models\KeluargaPart2;
use App\Models\KeluargaPart3;
use App\Models\KeluargaPart4;
use App\Models\KeluargaPart5;
use App\Models\KeluargaAnggota;
use App\Models\KeluargaFotoRumah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KuisionerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HOME
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        session()->forget([
            'draft_keluarga_id',
            'selected_keluarga_kode',
            'part1_selesai',
            'part2_selesai',
            'part3_selesai',
            'part4_selesai',
            'part5_selesai',
        ]);
        /*
        |--------------------------------------------------------------------------
        | JUMLAH RESPONDEN
        |--------------------------------------------------------------------------
        */

        $respondenCount = Keluarga::count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH DRAFT
        |--------------------------------------------------------------------------
        */

        $draftCount = KeluargaPart1::where('status', 'draft')->count();

        /*
        |--------------------------------------------------------------------------
        | JUMLAH KUISIONER SELESAI
        |--------------------------------------------------------------------------
        */

        $selesaiCount = KeluargaPart1::where('status', 'selesai')->count();

        /*
        |--------------------------------------------------------------------------
        | DATA DRAFT
        |--------------------------------------------------------------------------
        */

        $drafts = KeluargaPart1::where('status', 'draft')
            ->orderByDesc('updated_at')
            ->paginate(5, ['*'], 'draft_page');

        /*
        |--------------------------------------------------------------------------
        | DATA SIAP DIAJUKAN
        |--------------------------------------------------------------------------
        */

        $submitCount = KeluargaPart1::where('status', 'selesai')->count();

        /*
        |--------------------------------------------------------------------------
        | KIRIM DATA KE VIEW
        |--------------------------------------------------------------------------
        */

        return view('admin.kuisioner.index', [
            'page' => 'home',

            'respondenCount' => $respondenCount,

            'draftCount' => $draftCount,
            'selesaiCount' => $selesaiCount,
            'submitCount' => $submitCount,

            'drafts' => $drafts,

            'selesais' => collect(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | DRAFT
    |--------------------------------------------------------------------------
    */

    public function draft()
    {
        $drafts = KeluargaPart1::where('status', 'draft')
            ->latest('id')
            ->get();

        return view('admin.kuisioner.draft', [
            'drafts' => $drafts,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SELESAI
    |--------------------------------------------------------------------------
    */

    public function selesai()
    {
        $selesai = KeluargaPart1::where('status', 'selesai')
            ->orderByDesc('updated_at')
            ->get();

        return view('admin.kuisioner.selesai', [
            'selesai' => $selesai,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PART 1
    |--------------------------------------------------------------------------
    */

    public function part1(Request $request)
    {
        $search = trim($request->get('search', ''));

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA KELUARGA
        |--------------------------------------------------------------------------
        */

        $query = Keluarga::with('anggota')
            ->orderBy('id');

        if ($search !== '') {
            $query->where(
                'nama_lengkap',
                'like',
                '%' . $search . '%'
            );
        }

        $keluargas = $query
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | AMBIL STATUS KUESIONER
        |--------------------------------------------------------------------------
        */

        $statusKeluarga = KeluargaPart1::whereIn(
            'keluarga_periode_kode',
            $keluargas->pluck('kode')
        )
            ->orderByDesc('id')
            ->get()
            ->keyBy('keluarga_periode_kode');

        /*
        |--------------------------------------------------------------------------
        | TEMPELKAN STATUS KE KELUARGA
        |--------------------------------------------------------------------------
        */

        foreach ($keluargas as $keluarga) {
            $part1 = $statusKeluarga->get($keluarga->kode);

            if (!$part1) {
                // BELUM PERNAH MULAI
                $keluarga->status_kuesioner = null;
                $keluarga->current_part_kuesioner = 0;
            } else {
                // SUDAH ADA DATA PART 1
                $keluarga->status_kuesioner = $part1->status ?? 'draft';
                $keluarga->current_part_kuesioner = $part1->current_part ?? 1;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | DATA KECAMATAN DAN KELURAHAN
        |--------------------------------------------------------------------------
        */

        $kecamatans = DB::table('kecamatans')
            ->orderBy('deskripsi')
            ->get();

        $kelurahans = DB::table('kelurahans')
            ->orderBy('deskripsi')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | DEFAULT
        |--------------------------------------------------------------------------
        */

        $selectedKeluarga = null;
        $data = null;

        $selectedKecamatan = null;
        $selectedKelurahan = null;
        $anggotaPertama = null;

        /*
        |--------------------------------------------------------------------------
        | JIKA MEMILIH KELUARGA
        |--------------------------------------------------------------------------
        */
        if (!$request->filled('keluarga')) {
            session()->forget([
                'selected_keluarga_kode',
                'draft_keluarga_id',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);
        }

        if ($request->filled('keluarga')) {

            $selectedKeluarga = Keluarga::with('anggota')
                ->where(
                    'kode',
                    $request->keluarga
                )
                ->first();

            if ($selectedKeluarga) {

                $anggotaPertama = $selectedKeluarga->anggota->first();
            
                $data = KeluargaPart1::where(
                    'keluarga_periode_kode',
                    $selectedKeluarga->kode
                )
                    ->where('status', 'draft')
                    ->latest('id')
                    ->first();

                if ($selectedKeluarga->kecamatan_id) {

                    $selectedKecamatan = DB::table('kecamatans')
                        ->where(
                            'kecamatan_id',
                            $selectedKeluarga->kecamatan_id
                        )
                        ->value('deskripsi');
                }

                if ($selectedKeluarga->kelurahan_id) {

                    $selectedKelurahan = DB::table('kelurahans')
                        ->where(
                            'kelurahan_id',
                            $selectedKeluarga->kelurahan_id
                        )
                        ->value('deskripsi');
                }

                session([
                    'selected_keluarga_kode' =>
                        $selectedKeluarga->kode,
                ]);
            }
        }

        return view('admin.kuisioner.part1', [

            'keluargas' => $keluargas,

            'selectedKeluarga' => $selectedKeluarga,

            'data' => $data,

            'kecamatans' => $kecamatans,

            'kelurahans' => $kelurahans,

            'selectedKecamatan' => $selectedKecamatan,

            'selectedKelurahan' => $selectedKelurahan,

            'anggotaPertama' => $anggotaPertama,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PART 1
    |--------------------------------------------------------------------------
    */

    public function storePart1(Request $request)
    {
        $validated = $request->validate([

            'nik' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nama_kepala_keluarga' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status_keluarga' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nomor_induk_keluarga' => [
                'nullable',
                'string',
                'max:255',
            ],

            'no_kk' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jml_keluarga' => [
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

            'kecamatan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kelurahan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kode_pos' => [
                'nullable',
                'string',
                'max:16',
            ],

            'rt_rw' => [
                'nullable',
                'string',
                'max:255',
            ],

            'alamat_lengkap' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jalan_rumah' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nomor_rumah' => [
                'nullable',
                'string',
                'max:255',
            ],

            'is_alamat_sesuai' => [
                'nullable',
                'boolean',
            ],

            'alasan_tidak_sesuai' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'geotangging' => [
                'nullable',
                'string',
                'max:255',
            ],

            'keluarga_kode' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $user = auth()->user()->name ?? 'admin';

        /*
        |--------------------------------------------------------------------------
        | TENTUKAN KODE KELUARGA
        |--------------------------------------------------------------------------
        */

        $keluargaKode =
            $validated['keluarga_kode']
            ?? session('selected_keluarga_kode');

        if (!$keluargaKode) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kode keluarga tidak ditemukan. Silakan pilih keluarga terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CARI DRAFT MILIK KELUARGA
        |--------------------------------------------------------------------------
        */

        $draft = KeluargaPart1::where(
            'keluarga_periode_kode',
            $keluargaKode
        )
            ->where('status', 'draft')
            ->latest('id')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | JIKA BELUM ADA DRAFT
        |--------------------------------------------------------------------------
        */

        if (!$draft) {

            $draft = new KeluargaPart1();

            $draft->status = 'draft';

            $draft->current_part = 2;

            $draft->created_by = $user;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA DASAR
        |--------------------------------------------------------------------------
        */

        $draft->nik =
            $validated['nik'] ?? null;

        $draft->nama_kepala_keluarga =
            $validated['nama_kepala_keluarga'] ?? null;

        $draft->no_kk =
            $validated['no_kk'] ?? null;

        $draft->jml_keluarga =
            $validated['jml_keluarga'] ?? null;

        $draft->provinsi =
            $validated['provinsi'] ?? null;

        $draft->daerah =
            $validated['daerah'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | KECAMATAN
        |--------------------------------------------------------------------------
        */

        $draft->kecamatan =
            $validated['kecamatan'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | KELURAHAN
        |--------------------------------------------------------------------------
        */

        $draft->kelurahan =
            $validated['kelurahan'] ?? null;

        $draft->kode_pos =
            $validated['kode_pos'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | ALAMAT
        |--------------------------------------------------------------------------
        */

        $rtRw = trim(
            $validated['rt_rw'] ?? ''
        );

        $alamat = trim(
            $validated['alamat_lengkap'] ?? ''
        );

        if ($rtRw && $alamat) {

            $draft->alamat_lengkap =
                $rtRw . ', ' . $alamat;

        } elseif ($rtRw) {

            $draft->alamat_lengkap =
                $rtRw;

        } else {

            $draft->alamat_lengkap =
                $alamat;
        }

        /*
        |--------------------------------------------------------------------------
        | JALAN DAN NOMOR RUMAH
        |--------------------------------------------------------------------------
        */

        $draft->jalan_rumah =
            $validated['jalan_rumah'] ?? null;

        $draft->nomor_rumah =
            $validated['nomor_rumah'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | ALAMAT SESUAI
        |--------------------------------------------------------------------------
        */

        $draft->is_alamat_sesuai =
            $validated['is_alamat_sesuai'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | GEOTAGGING
        |--------------------------------------------------------------------------
        */

        $draft->geotangging =
            $validated['geotangging'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | HUBUNGKAN DENGAN KELUARGA
        |--------------------------------------------------------------------------
        */

        $draft->keluarga_periode_kode =
            $keluargaKode;

        /*
        |--------------------------------------------------------------------------
        | DATA SYSTEM
        |--------------------------------------------------------------------------
        */

        $draft->status =
            'draft';

        $draft->current_part =
            2;

        $draft->updated_by =
            $user;

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        $draft->save();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DRAFT AKTIF KE SESSION
        |--------------------------------------------------------------------------
        */

        session([
            'draft_keluarga_id' =>
                $draft->id,

            'selected_keluarga_kode' =>
                $keluargaKode,

            'part1_selesai' =>
                true,

            'part2_selesai' =>
                false,

            'part3_selesai' =>
                false,

            'part4_selesai' =>
                false,

            'part5_selesai' =>
                false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | LANJUT PART 2
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('kuisioner.part2')
            ->with(
                'success',
                'Data Part 1 berhasil disimpan. Silakan lanjut ke Part 2.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PART 2
    |--------------------------------------------------------------------------
    */

    public function part2()
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (!$keluargaKode) {
            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan pilih keluarga dan selesaikan Part 1 terlebih dahulu.'
                );
        }

        $draftId = session('draft_keluarga_id');

        if (!$draftId) {
            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi dan simpan Part 1 terlebih dahulu.'
                );
        }

        $draft = KeluargaPart1::where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->where('status', 'draft')
            ->first();

        if (!$draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan pilih keluarga dan selesaikan Part 1 terlebih dahulu.'
                );
        }

        if ((int) $draft->current_part < 2) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi dan simpan Part 1 terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA PART 2
        |--------------------------------------------------------------------------
        */

        $dataPart2 = null;

        if ($draft->keluarga_periode_kode) {

            $dataPart2 = KeluargaPart2::where(
                'keluarga_periode_kode',
                $draft->keluarga_periode_kode
            )->first();
        }

        return view('admin.kuisioner.part2', [
            'dataPart2' => $dataPart2,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PART 2
    |--------------------------------------------------------------------------
    */

    public function storePart2(Request $request)
    {
        $draftId = session('draft_keluarga_id');

        if (!$draftId) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi dan simpan Part 1 terlebih dahulu.'
                );
        }

        $draft = KeluargaPart1::where('id', $draftId)
            ->where('status', 'draft')
            ->first();

        if (!$draft) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Draft kuisioner tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'jenis_bangunan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'keluarga_lain' => [
                'nullable',
                'in:Ya,Tidak',
            ],

            'jumlah_keluarga_lain' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            'jumlah_orang' => [
                'nullable',
                'numeric',
                'min:1',
            ],

            'status_kepemilikan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bukti_kepemilikan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sewa_bulanan' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'perkiraan_sewa' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'status_kepemilikan_lainnya' => [
                'nullable',
                'string',
                'max:255',
            ],

            'luas_lantai' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'jenis_lantai' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kondisi_lantai' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jenis_dinding' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kondisi_dinding' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jenis_atap' => [
                'nullable',
                'string',
                'max:255',
            ],

            'kondisi_atap' => [
                'nullable',
                'in:Baik,Rusak Ringan,Rusak Sedang,Rusak Berat',
            ],

            'fasilitas_bab' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jenis_toilet' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sumber_air_minum' => [
                'nullable',
                'string',
                'max:255',
            ],

            'sumber_penerangan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'daya_listrik' => [
                'nullable',
                'string',
                'max:255',
            ],

            'id_pelanggan_pln' => [
                'nullable',
                'string',
                'max:255',
            ],

            'jumlah_meteran' => [
                'nullable',
                'numeric',
                'min:1',
            ],
        ]);

        $user = auth()->user()->name ?? 'admin';

        /*
        |--------------------------------------------------------------------------
        | KODE KELUARGA
        |--------------------------------------------------------------------------
        */

        $keluargaKode = $draft->keluarga_periode_kode;

        if (!$keluargaKode) {
            $keluargaKode = session('selected_keluarga_kode');
        }

        if (!$keluargaKode) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Kode keluarga tidak ditemukan. Silakan pilih keluarga kembali.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | KONVERSI KONDISI ATAP
        |--------------------------------------------------------------------------
        */

        $kondisiAtap = null;

        if (!empty($validated['kondisi_atap'])) {

            $kondisiAtap = match ($validated['kondisi_atap']) {

                'Baik' => 1,

                'Rusak Ringan' => 2,

                'Rusak Sedang' => 3,

                'Rusak Berat' => 4,

                default => null,
            };
        }

        /*
        |--------------------------------------------------------------------------
        | STATUS KEPEMILIKAN
        |--------------------------------------------------------------------------
        */

        $kepemilikan =
            $validated['status_kepemilikan'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | HARGA SEWA
        |--------------------------------------------------------------------------
        */

        $hargaSewa = null;

        if ($kepemilikan === 'Kontrak/Sewa') {

            $hargaSewa =
                $validated['sewa_bulanan'] ?? null;

        } elseif ($kepemilikan === 'Bebas Sewa') {

            $hargaSewa =
                $validated['perkiraan_sewa'] ?? null;
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN / UPDATE
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $validated,
            $keluargaKode,
            $draft,
            $user,
            $kondisiAtap,
            $kepemilikan,
            $hargaSewa
        ) {

            $data = [

                'keluarga_periode_kode' =>
                    $keluargaKode,

                'jenis_bangungan' =>
                    $validated['jenis_bangunan'] ?? null,

                'is_keluarga_lain' =>
                    isset($validated['keluarga_lain'])
                        ? (
                            $validated['keluarga_lain'] === 'Ya'
                                ? 1
                                : 0
                        )
                        : null,

                'jml_keluarga_lain' =>
                    $validated['jumlah_keluarga_lain'] ?? null,

                'total_penghuni' =>
                    $validated['jumlah_orang'] ?? null,

                'kepemilikan_bangunan' =>
                    $kepemilikan,

                'bukti_kepemilikan' =>
                    $validated['bukti_kepemilikan'] ?? null,

                'harga_sewa_kontrak' =>
                    $hargaSewa,

                'luas_lantai' =>
                    $validated['luas_lantai'] ?? null,

                'jenis_lantai' =>
                    $validated['jenis_lantai'] ?? null,

                'kondisi_lantai' =>
                    $validated['kondisi_lantai'] ?? null,

                'jenis_dinding' =>
                    $validated['jenis_dinding'] ?? null,

                'kondisi_dinding' =>
                    $validated['kondisi_dinding'] ?? null,

                'jenis_atap' =>
                    $validated['jenis_atap'] ?? null,

                'kondisi_atap' =>
                    $kondisiAtap,

                'fasilitas_bab' =>
                    $validated['fasilitas_bab'] ?? null,

                'jenis_kloset' =>
                    $validated['jenis_toilet'] ?? null,

                'sumber_minum' =>
                    $validated['sumber_air_minum'] ?? null,

                'sumber_penerangan' =>
                    $validated['sumber_penerangan'] ?? null,

                'daya_listrik' =>
                    $validated['daya_listrik'] ?? null,

                'idpel_pln' =>
                    $validated['id_pelanggan_pln'] ?? null,

                'jml_meteran' =>
                    $validated['jumlah_meteran'] ?? null,

                'updated_by' =>
                    $user,

                'updated_at' =>
                    now(),
            ];

            $part2 = KeluargaPart2::where(
                'keluarga_periode_kode',
                $keluargaKode
            )->first();

            if (!$part2) {

                $data['created_by'] = $user;
                $data['created_at'] = now();

                KeluargaPart2::create($data);

            } else {

                $part2->update($data);
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE PROGRESS
            |--------------------------------------------------------------------------
            */

            $draft->update([

                'current_part' => 3,

                'updated_by' => $user,

            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | SESSION
        |--------------------------------------------------------------------------
        */

        session([
            'draft_keluarga_id' =>
                $draft->id,

            'part1_selesai' =>
                true,

            'part2_selesai' =>
                true,

            'part3_selesai' =>
                false,

            'part4_selesai' =>
                false,

            'part5_selesai' =>
                false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | LANJUT PART 3
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('kuisioner.part3')
            ->with(
                'success',
                'Data Part 2 berhasil disimpan. Silakan lanjut ke Part 3.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PART 3
    |--------------------------------------------------------------------------
    */

    public function part3()
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (!$keluargaKode) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draftId = session('draft_keluarga_id');

        if (!$draftId) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draft = KeluargaPart1::where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->where('status', 'draft')
            ->first();

        if (!$draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1');
        }

        if ((int) $draft->current_part < 3) {

            return redirect()
                ->route('kuisioner.part1');
        }

        $dataPart3 = KeluargaPart3::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )->first();

        return view(
            'admin.kuisioner.part3',
            compact(
                'draft',
                'dataPart3'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PART 3
    |--------------------------------------------------------------------------
    */

    public function storePart3(Request $request)
    {
        $draftId = session('draft_keluarga_id');

        if (!$draftId) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Silakan pilih keluarga terlebih dahulu.'
                );
        }

        $draft = KeluargaPart1::where('id', $draftId)
            ->where('status', 'draft')
            ->first();

        if (!$draft) {

            session()->forget([
                'draft_keluarga_id',
            ]);

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Data draft keluarga tidak ditemukan.'
                );
        }

        if ((int) $draft->current_part < 3) {

            return redirect()
                ->route('kuisioner.part2')
                ->with(
                    'error',
                    'Silakan selesaikan Part 2 terlebih dahulu.'
                );
        }

        $request->validate([

            'pengeluaran_listrik_bulanan' =>
                ['nullable', 'numeric', 'min:0'],

            'pengeluaran_pulsa_bulanan' =>
                ['nullable', 'numeric', 'min:0'],

            'pengeluaran_internet_bulanan' =>
                ['nullable', 'numeric', 'min:0'],

            'pengeluaran_makan_mingguan' =>
                ['nullable', 'numeric', 'min:0'],

            'pengeluaran_nonmakan_bulanan' =>
                ['nullable', 'numeric', 'min:0'],

            'pengeluaran_nonmakan_tahunan' =>
                ['nullable', 'numeric', 'min:0'],

            'total_pendapatan_kerja' =>
                ['nullable', 'numeric', 'min:0'],

            'total_pendapatan_usaha' =>
                ['nullable', 'numeric', 'min:0'],

            'total_pendapatan_lainnya' =>
                ['nullable', 'numeric', 'min:0'],
        ]);

        $keluargaKode =
            $draft->keluarga_periode_kode
            ?: session('selected_keluarga_kode');

        if (!$keluargaKode) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kode keluarga tidak ditemukan.'
                );
        }

        DB::beginTransaction();

        try {

            $data = [

                'keluarga_periode_kode' =>
                    $keluargaKode,

                'pengeluaran_listrik_bulanan' =>
                    $request->pengeluaran_listrik_bulanan,

                'pengeluaran_pulsa_bulanan' =>
                    $request->pengeluaran_pulsa_bulanan,

                'pengeluaran_internet_bulanan' =>
                    $request->pengeluaran_internet_bulanan,

                'pengeluaran_makan_mingguan' =>
                    $request->pengeluaran_makan_mingguan,

                'pengeluaran_nonmakan_bulanan' =>
                    $request->pengeluaran_nonmakan_bulanan,

                'pengeluaran_nonmakan_tahunan' =>
                    $request->pengeluaran_nonmakan_tahunan,

                'total_pendapatan_kerja' =>
                    $request->total_pendapatan_kerja,

                'total_pendapatan_usaha' =>
                    $request->total_pendapatan_usaha,

                'total_pendapatan_lainnya' =>
                    $request->total_pendapatan_lainnya,

                'updated_by' =>
                    auth()->user()->username ?? 'admin',

                'updated_at' =>
                    now(),
            ];

            $dataPart3 = KeluargaPart3::where(
                'keluarga_periode_kode',
                $keluargaKode
            )->first();

            if ($dataPart3) {

                $dataPart3->update($data);

            } else {

                $data['created_by'] =
                    auth()->user()->username ?? 'admin';

                $data['created_at'] =
                    now();

                KeluargaPart3::create($data);
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE PROGRESS
            |--------------------------------------------------------------------------
            */

            $draft->current_part = 4;

            $draft->updated_by =
                auth()->user()->username ?? 'admin';

            $draft->save();

            /*
            |--------------------------------------------------------------------------
            | UPDATE SESSION
            |--------------------------------------------------------------------------
            */

            session([
                'part3_selesai' => true,
                'part4_selesai' => false,
                'part5_selesai' => false,
            ]);

            DB::commit();

            return redirect()
                ->route('kuisioner.part4')
                ->with(
                    'success',
                    'Data Part 3 berhasil disimpan.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data Part 3 gagal disimpan: ' .
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PART 4
    |--------------------------------------------------------------------------
    */

    public function part4()
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (!$keluargaKode) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draftId = session('draft_keluarga_id');

        if (!$draftId) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draft = KeluargaPart1::where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->where('status', 'draft')
            ->first();

        if (!$draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1');
        }

        if ((int) $draft->current_part < 4) {

            return redirect()
                ->route('kuisioner.part1');
        }

        $dataPart4 = KeluargaPart4::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )->get();

        return view(
            'admin.kuisioner.part4',
            compact(
                'draft',
                'dataPart4'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PART 4
    |--------------------------------------------------------------------------
    */

    public function storePart4(Request $request)
    {
        $draftId = session('draft_keluarga_id');

        if (!$draftId) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Silakan pilih keluarga terlebih dahulu.'
                );
        }

        $draft = KeluargaPart1::where('id', $draftId)
            ->where('status', 'draft')
            ->first();

        if (!$draft) {

            session()->forget([
                'draft_keluarga_id',
            ]);

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Data draft keluarga tidak ditemukan.'
                );
        }

        if ((int) $draft->current_part < 4) {

            return redirect()
                ->route('kuisioner.part3')
                ->with(
                    'error',
                    'Silakan selesaikan Part 3 terlebih dahulu.'
                );
        }

        $request->validate([

            'aset' =>
                ['required', 'array'],

            'aset.*.punya' =>
                ['required', 'in:0,1'],

            'aset.*.jumlah' =>
                ['nullable', 'integer', 'min:0'],
        ]);

        $keluargaKode =
            $draft->keluarga_periode_kode
            ?: session('selected_keluarga_kode');

        if (!$keluargaKode) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kode keluarga tidak ditemukan.'
                );
        }

        DB::beginTransaction();

        try {

            $asetList = [

                'Tabung Gas 3 KG',

                'Tabung Gas 5,5 KG atau Lebih',

                'Lemari Es/Kulkas',

                'AC (Air Conditioner)',

                'Emas/Perhiasan',

                'Komputer/Laptop/Tablet',

                'Sepeda Motor',

                'Mobil',

                'Rumah/Bangunan (selain yang ditempati)',

                'Lahan Lainnya',
            ];

            foreach ($asetList as $aset) {

                $data = $request->input(
                    'aset.' . md5($aset),
                    []
                );

                $punya = isset($data['punya'])
                    ? (int) $data['punya']
                    : 0;

                $jumlah = $punya === 1
                    ? ($data['jumlah'] ?? 0)
                    : 0;

                $existing = KeluargaPart4::where(
                    'keluarga_periode_kode',
                    $keluargaKode
                )
                    ->where(
                        'aset_keluarga',
                        $aset
                    )
                    ->first();

                $saveData = [

                    'keluarga_periode_kode' =>
                        $keluargaKode,

                    'aset_keluarga' =>
                        $aset,

                    'is_punya_aset' =>
                        $punya,

                    'jml_aset' =>
                        $jumlah,

                    'updated_by' =>
                        auth()->user()->username ?? 'admin',

                    'updated_at' =>
                        now(),
                ];

                if ($existing) {

                    $existing->update($saveData);

                } else {

                    $saveData['created_by'] =
                        auth()->user()->username ?? 'admin';

                    $saveData['created_at'] =
                        now();

                    KeluargaPart4::create($saveData);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UPDATE PROGRESS
            |--------------------------------------------------------------------------
            */

            $draft->current_part = 5;

            $draft->updated_by =
                auth()->user()->username ?? 'admin';

            $draft->save();

            session([
                'part4_selesai' => true,
                'part5_selesai' => false,
            ]);

            DB::commit();

            return redirect()
                ->route('kuisioner.part5')
                ->with(
                    'success',
                    'Data Part 4 berhasil disimpan.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data Part 4 gagal disimpan: ' .
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PART 5
    |--------------------------------------------------------------------------
    */

    public function part5(Request $request)
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (!$keluargaKode) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draftId = session('draft_keluarga_id');

        if (!$draftId) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draft = KeluargaPart1::where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->where('status', 'draft')
            ->first();

        if (!$draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1');
        }

        if ((int) $draft->current_part < 5) {

            return redirect()
                ->route('kuisioner.part1');
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL SELURUH ANGGOTA KELUARGA
        |--------------------------------------------------------------------------
        */

        $anggota = KeluargaAnggota::where(
            'keluarga_kode',
            $draft->keluarga_periode_kode
        )
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | AMBIL JAWABAN PART 5
        |--------------------------------------------------------------------------
        */

        $dataPart5 = KeluargaPart5::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )
            ->get()
            ->keyBy('keluarga_anggota_kode');

        /*
        |--------------------------------------------------------------------------
        | CEK SELESAI
        |--------------------------------------------------------------------------
        */

        $jumlahAnggota =
            $anggota->count();

        $jumlahSelesai =
            $anggota->filter(
                function ($item) use ($dataPart5) {

                    return $dataPart5->has(
                        $item->kode
                    );
                }
            )->count();

        $semuaAnggotaSelesai =
            $jumlahAnggota === $jumlahSelesai;

        return view(
            'admin.kuisioner.part5',
            compact(
                'draft',
                'anggota',
                'dataPart5',
                'jumlahAnggota',
                'jumlahSelesai',
                'semuaAnggotaSelesai'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PART 5 ANGGOTA
    |--------------------------------------------------------------------------
    */

    public function part5Anggota($kode)
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (!$keluargaKode) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draftId = session('draft_keluarga_id');

        if (!$draftId) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draft = KeluargaPart1::where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->where('status', 'draft')
            ->first();

        if (!$draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1');
        }

        if ((int) $draft->current_part < 5) {

            return redirect()
                ->route('kuisioner.part1');
        }

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN ANGGOTA MILIK KELUARGA
        |--------------------------------------------------------------------------
        */

        $anggota = KeluargaAnggota::where(
            'keluarga_kode',
            $draft->keluarga_periode_kode
        )
            ->where(
                'kode',
                $kode
            )
            ->first();

        if (!$anggota) {

            return redirect()
                ->route('kuisioner.part5')
                ->with(
                    'error',
                    'Anggota keluarga tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA YANG SUDAH DISIMPAN
        |--------------------------------------------------------------------------
        */

        $dataPart5 = KeluargaPart5::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )
            ->where(
                'keluarga_anggota_kode',
                $anggota->kode
            )
            ->first();

        return view(
            'admin.kuisioner.part5-anggota',
            compact(
                'draft',
                'anggota',
                'dataPart5'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN PART 5 ANGGOTA
    |--------------------------------------------------------------------------
    */

    public function storePart5Anggota(
        Request $request,
        $kode
    ) {
        $draftId =
            session('draft_keluarga_id');

        if (!$draftId) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Silakan mulai pengisian kuisioner terlebih dahulu.'
                );
        }

        $draft =
            KeluargaPart1::find($draftId);

        if (!$draft) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Data keluarga tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CARI ANGGOTA
        |--------------------------------------------------------------------------
        */

        $anggota = KeluargaAnggota::where(
            'keluarga_kode',
            $draft->keluarga_periode_kode
        )
            ->where(
                'kode',
                $kode
            )
            ->first();

        if (!$anggota) {

            return redirect()
                ->route('kuisioner.part5')
                ->with(
                    'error',
                    'Anggota keluarga tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'keberadaan' =>
                'required|string|max:255',

            'no_hp' =>
                'nullable|string|max:16',

            'jenis_kelamin' =>
                'required|string|max:16',

            'tanggal_lahir' =>
                'nullable|date',

            'status_perkawinan' =>
                'required|string|max:255',

            'status_sekolah' =>
                'required|string|max:255',

            'ijazah_tertinggi' =>
                'required|string|max:255',

            'pekerjaan_utama' =>
                'required|string|max:255',

            'status_pekerjaan' =>
                'required_unless:pekerjaan_utama,Tidak Bekerja|string|max:255',

            'kepemilikan_rekening' =>
                'required|string|max:255',

            'is_disabilitas_fisik' =>
                'required|in:0,1',

            'is_disabilitas_mental' =>
                'required|in:0,1',

            'is_disabilitas_intelektual' =>
                'required|in:0,1',

            'is_disabilitas_netra' =>
                'required|in:0,1',

            'is_disabilitas_rungu' =>
                'required|in:0,1',

            'is_disabilitas_wicara' =>
                'required|in:0,1',

            'keluhan_kesehatan' =>
                'required|string|max:255',
        ]);

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $data = [

            'keluarga_periode_kode' =>
                $draft->keluarga_periode_kode,

            'keluarga_anggota_kode' =>
                $anggota->kode,

            'keberadaan' =>
                $validated['keberadaan'],

            'no_hp' =>
                $validated['no_hp'] ?? null,

            'jenis_kelamin' =>
                $validated['jenis_kelamin'],

            'tanggal_lahir' =>
                $validated['tanggal_lahir'] ?? null,

            'status_perkawinan' =>
                $validated['status_perkawinan'],

            'status_sekolah' =>
                $validated['status_sekolah'],

            'ijazah_tertinggi' =>
                $validated['ijazah_tertinggi'],

            'pekerjaan_utama' =>
                $validated['pekerjaan_utama'],

            'status_pekerjaan' =>
                $validated['status_pekerjaan'] ?? null,

            'kepemilikan_rekening' =>
                $validated['kepemilikan_rekening'],

            'is_disabilitas_fisik' =>
                $validated['is_disabilitas_fisik'],

            'is_disabilitas_mental' =>
                $validated['is_disabilitas_mental'],

            'is_disabilitas_intelektual' =>
                $validated['is_disabilitas_intelektual'],

            'is_disabilitas_netra' =>
                $validated['is_disabilitas_netra'],

            'is_disabilitas_rungu' =>
                $validated['is_disabilitas_rungu'],

            'is_disabilitas_wicara' =>
                $validated['is_disabilitas_wicara'],

            'keluhan_kesehatan' =>
                $validated['keluhan_kesehatan'],

            'created_by' =>
                auth()->id() ?? 'system',

            'updated_by' =>
                auth()->id() ?? 'system',

            'updated_at' =>
                now(),
        ];

        /*
        |--------------------------------------------------------------------------
        | UPDATE ATAU INSERT
        |--------------------------------------------------------------------------
        */

        $existing = KeluargaPart5::where(
            'keluarga_periode_kode',
            $draft->keluarga_periode_kode
        )
            ->where(
                'keluarga_anggota_kode',
                $anggota->kode
            )
            ->first();

        if ($existing) {

            $existing->update($data);

        } else {

            $data['created_at'] =
                now();

            KeluargaPart5::create($data);
        }

        return redirect()
            ->route('kuisioner.part5')
            ->with(
                'success',
                'Data anggota berhasil disimpan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | PART 5 FOTO
    |--------------------------------------------------------------------------
    */

    public function part5Foto()
    {
        $keluargaKode = session('selected_keluarga_kode');

        if (!$keluargaKode) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draftId = session('draft_keluarga_id');

        if (!$draftId) {
            return redirect()
                ->route('kuisioner.part1');
        }

        $draft = KeluargaPart1::where('id', $draftId)
            ->where(
                'keluarga_periode_kode',
                $keluargaKode
            )
            ->where('status', 'draft')
            ->first();

        if (!$draft) {

            session()->forget([
                'draft_keluarga_id',
                'selected_keluarga_kode',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
                'part5_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1');
        }

        if ((int) $draft->current_part < 5) {
            return redirect()
                ->route('kuisioner.part1');
        }

        /*
        |--------------------------------------------------------------------------
        | TAMPILKAN HALAMAN FOTO
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.kuisioner.part5-foto',
            compact('draft')
        );
    }
    /*--------------------------------------------------------------------------
    SIMPAN PART 5 FOTO
    --------------------------------------------------------------------------
    */

    public function storePart5Foto(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL DRAFT AKTIF
        |--------------------------------------------------------------------------
        */

        $draftId =
            session('draft_keluarga_id');

        if (!$draftId) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Silakan mulai pengisian kuisioner terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CARI DATA PART 1
        |--------------------------------------------------------------------------
        */

        $draft =
            KeluargaPart1::find($draftId);

        if (!$draft) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'error',
                    'Data keluarga tidak ditemukan.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN PART 5
        |--------------------------------------------------------------------------
        */

        if ((int) $draft->current_part < 5) {

            return redirect()
                ->route('kuisioner.part4')
                ->with(
                    'error',
                    'Selesaikan Part 4 terlebih dahulu.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDASI FOTO
        |--------------------------------------------------------------------------
        */

        $request->validate([

            'tampak_depan' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],

            'ruang_tamu' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],

            'kamar_mandi' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120',
            ],

        ], [

            'tampak_depan.required' =>
                'Foto tampak depan wajib diupload.',

            'ruang_tamu.required' =>
                'Foto ruang tamu wajib diupload.',

            'kamar_mandi.required' =>
                'Foto kamar mandi wajib diupload.',

            'tampak_depan.image' =>
                'File tampak depan harus berupa gambar.',

            'ruang_tamu.image' =>
                'File ruang tamu harus berupa gambar.',

            'kamar_mandi.image' =>
                'File kamar mandi harus berupa gambar.',

            '*.max' =>
                'Ukuran setiap foto maksimal 5 MB.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | JENIS FOTO
        |--------------------------------------------------------------------------
        */

        $jenisFoto = [

            'tampak_depan' =>
                'Foto Tampak Depan',

            'ruang_tamu' =>
                'Foto Ruang Tamu',

            'kamar_mandi' =>
                'Foto Kamar Mandi',
        ];

        /*
        |--------------------------------------------------------------------------
        | USER
        |--------------------------------------------------------------------------
        */

        $user = auth()->user();

        $userName = $user
            ? (
                $user->name
                ?? $user->username
                ?? 'admin'
            )
            : 'admin';

        $userId =
            auth()->id() ?? 'system';

        /*
        |--------------------------------------------------------------------------
        | SIMPAN FOTO + SELESAIKAN KUISIONER
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();

        try {

            foreach (
                $jenisFoto
                as $field => $namaJenis
            ) {

                $file =
                    $request->file($field);

                if (!$file) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | NAMA FILE
                |--------------------------------------------------------------------------
                */

                $namaFile =
                    time()
                    . '_'
                    . $field
                    . '_'
                    . uniqid()
                    . '.'
                    . $file->getClientOriginalExtension();

                /*
                |--------------------------------------------------------------------------
                | SIMPAN FILE
                |--------------------------------------------------------------------------
                */

                $path = $file->storeAs(
                    'kuisioner/rumah',
                    $namaFile,
                    'public'
                );

                /*
                |--------------------------------------------------------------------------
                | CARI FOTO LAMA
                |--------------------------------------------------------------------------
                */

                $existing = KeluargaFotoRumah::where(
                    'keluarga_periode_kode',
                    $draft->keluarga_periode_kode
                )
                    ->where(
                        'jenis_foto',
                        $field
                    )
                    ->first();

                /*
                |--------------------------------------------------------------------------
                | DATA FOTO
                |--------------------------------------------------------------------------
                */

                $data = [

                    'keluarga_periode_kode' =>
                        $draft->keluarga_periode_kode,

                    'jenis_foto' =>
                        $field,

                    'nama_file' =>
                        $namaFile,

                    'path_file' =>
                        $path,

                    'updated_by' =>
                        $userId,

                    'updated_at' =>
                        now(),
                ];

                /*
                |--------------------------------------------------------------------------
                | UPDATE / INSERT
                |--------------------------------------------------------------------------
                */

                if ($existing) {

                    $existing->update($data);

                } else {

                    $data['created_by'] =
                        $userId;

                    $data['created_at'] =
                        now();

                    KeluargaFotoRumah::create($data);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | UBAH STATUS DRAFT MENJADI SELESAI
            |--------------------------------------------------------------------------
            */

            $draft->update([

                'status' =>
                    'selesai',

                'current_part' =>
                    5,

                'updated_by' =>
                    $userName,

                'updated_at' =>
                    now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | BERSIHKAN SESSION
            |--------------------------------------------------------------------------
            */

            session()->forget([

                'draft_keluarga_id',

                'selected_keluarga_kode',

                'part1_selesai',

                'part2_selesai',

                'part3_selesai',

                'part4_selesai',

                'part5_selesai',
            ]);

            DB::commit();

            /*
            |--------------------------------------------------------------------------
            | REDIRECT
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('kuisioner.selesai')
                ->with(
                    'success',
                    'Foto rumah berhasil disimpan. Kuisioner selesai.'
                );

        } catch (\Throwable $e) {

            DB::rollBack();

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Kuisioner gagal diselesaikan: ' .
                    $e->getMessage()
                );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SELESAI KUISIONER
    |--------------------------------------------------------------------------
    */

    public function selesaiKuisioner(Request $request)
    {
        $draftId =
            session('draft_keluarga_id');

        if (!$draftId) {

            return redirect()
                ->route('kuisioner.index')
                ->with(
                    'warning',
                    'Tidak ada kuisioner aktif.'
                );
        }

        $draft =
            KeluargaPart1::findOrFail($draftId);

        $draft->update([

            'status' =>
                'selesai',

            'current_part' =>
                5,

            'updated_by' =>
                auth()->user()->name ?? 'admin',
        ]);

        session()->forget([

            'draft_keluarga_id',

            'selected_keluarga_kode',

            'part1_selesai',

            'part2_selesai',

            'part3_selesai',

            'part4_selesai',

            'part5_selesai',
        ]);

        return redirect()
            ->route('kuisioner.selesai')
            ->with(
                'success',
                'Kuisioner berhasil diselesaikan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL KUISIONER SELESAI
    |--------------------------------------------------------------------------
    */

    public function detailSelesai($id)
    {
        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA PART 1
        |--------------------------------------------------------------------------
        */

        $dataPart1 =
            KeluargaPart1::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | PASTIKAN SUDAH SELESAI
        |--------------------------------------------------------------------------
        */

        if ($dataPart1->status !== 'selesai') {

            return redirect()
                ->route('kuisioner.selesai')
                ->with(
                    'warning',
                    'Data kuisioner belum berstatus selesai.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | KODE KELUARGA
        |--------------------------------------------------------------------------
        */

        $keluargaKode =
            $dataPart1->keluarga_periode_kode;

        /*
        |--------------------------------------------------------------------------
        | PART 2
        |--------------------------------------------------------------------------
        */

        $dataPart2 =
            KeluargaPart2::where(
                'keluarga_periode_kode',
                $keluargaKode
            )->first();

        /*
        |--------------------------------------------------------------------------
        | PART 3
        |--------------------------------------------------------------------------
        */

        $dataPart3 =
            KeluargaPart3::where(
                'keluarga_periode_kode',
                $keluargaKode
            )->first();

        /*
        |--------------------------------------------------------------------------
        | PART 4
        |--------------------------------------------------------------------------
        */

        $dataPart4 =
            KeluargaPart4::where(
                'keluarga_periode_kode',
                $keluargaKode
            )->get();

        /*
        |--------------------------------------------------------------------------
        | ANGGOTA KELUARGA
        |--------------------------------------------------------------------------
        */

        $anggota =
            KeluargaAnggota::where(
                'keluarga_kode',
                $keluargaKode
            )
                ->orderBy('id')
                ->get();

        /*
        |--------------------------------------------------------------------------
        | PART 5
        |--------------------------------------------------------------------------
        */

        $dataPart5 =
            KeluargaPart5::where(
                'keluarga_periode_kode',
                $keluargaKode
            )
                ->get()
                ->keyBy(
                    'keluarga_anggota_kode'
                );

        /*
        |--------------------------------------------------------------------------
        | FOTO RUMAH
        |--------------------------------------------------------------------------
        */

        $fotoRumah =
            KeluargaFotoRumah::where(
                'keluarga_periode_kode',
                $keluargaKode
            )
                ->get()
                ->keyBy('jenis_foto');

        /*
        |--------------------------------------------------------------------------
        | KIRIM KE VIEW
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.kuisioner.detail-selesai',
            compact(
                'dataPart1',
                'dataPart2',
                'dataPart3',
                'dataPart4',
                'anggota',
                'dataPart5',
                'fotoRumah'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RESUME DRAFT
    |--------------------------------------------------------------------------
    */

    public function resumeDraft($id)
    {
        $draft = KeluargaPart1::where(
            'id',
            $id
        )
            ->where(
                'status',
                'draft'
            )
            ->firstOrFail();

        session([

            'draft_keluarga_id' =>
                $draft->id,

            'selected_keluarga_kode' =>
                $draft->keluarga_periode_kode,

            'part1_selesai' =>
                (int) $draft->current_part >= 2,

            'part2_selesai' =>
                (int) $draft->current_part >= 3,

            'part3_selesai' =>
                (int) $draft->current_part >= 4,

            'part4_selesai' =>
                (int) $draft->current_part >= 5,

            'part5_selesai' =>
                false,
        ]);

        switch (
            (int) $draft->current_part
        ) {

            case 1:

                return redirect()
                    ->route(
                        'kuisioner.part1'
                    );

            case 2:

                return redirect()
                    ->route(
                        'kuisioner.part2'
                    );

            case 3:

                return redirect()
                    ->route(
                        'kuisioner.part3'
                    );

            case 4:

                return redirect()
                    ->route(
                        'kuisioner.part4'
                    );

            case 5:

                return redirect()
                    ->route(
                        'kuisioner.part5'
                    );

            default:

                return redirect()
                    ->route(
                        'kuisioner.part1'
                    );
        }
    }
}