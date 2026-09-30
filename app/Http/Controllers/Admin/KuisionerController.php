<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KeluargaPart1;
use Illuminate\Http\Request;

class KuisionerController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HOME
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $draftCount = KeluargaPart1::where('status', 'draft')->count();

        $selesaiCount = KeluargaPart1::where('status', 'selesai')->count();

        return view('admin.kuisioner.index', [
            'page' => 'home',
            'draftCount' => $draftCount,
            'selesaiCount' => $selesaiCount,
            'drafts' => collect(),
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
        $selesais = KeluargaPart1::where('status', 'selesai')
            ->latest('id')
            ->get();

        $draftCount = KeluargaPart1::where('status', 'draft')->count();

        return view('admin.kuisioner.index', [
            'page' => 'selesai',
            'draftCount' => $draftCount,
            'selesaiCount' => $selesais->count(),
            'drafts' => collect(),
            'selesais' => $selesais,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | PART 1
    |--------------------------------------------------------------------------
    */

    public function part1(Request $request)
    {
        /*
        | Jika klik "Mulai Kuisioner Baru"
        */
        if ($request->has('new')) {

            session()->forget([
                'draft_keluarga_id',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
            ]);

            return view('admin.kuisioner.part1', [
                'data' => null,
            ]);
        }

        /*
        | Ambil ID Part 1 dari session
        */
        $draftId = session('draft_keluarga_id');

        /*
        | Kalau tidak ada session
        */
        if (!$draftId) {
            return view('admin.kuisioner.part1', [
                'data' => null,
            ]);
        }

        /*
        | Ambil data Part 1
        */
        $data = KeluargaPart1::where('id', $draftId)
            ->where('status', 'draft')
            ->first();

        /*
        | Kalau data tidak ditemukan
        */
        if (!$data) {

            session()->forget([
                'draft_keluarga_id',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
            ]);

            return view('admin.kuisioner.part1', [
                'data' => null,
            ]);
        }

        /*
        | Tampilkan data draft
        */
        return view('admin.kuisioner.part1', [
            'data' => $data,
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
        ]);

        $user = auth()->user()->name ?? 'admin';

        /*
        |--------------------------------------------------------------------------
        | CARI DRAFT AKTIF
        |--------------------------------------------------------------------------
        */

        $draftId = session('draft_keluarga_id');

        $draft = null;

        if ($draftId) {
            $draft = KeluargaPart1::where('id', $draftId)
                ->where('status', 'draft')
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | KALAU BELUM ADA DRAFT → BUAT BARU
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
        | MASUKKAN SEMUA DATA
        |--------------------------------------------------------------------------
        */

        foreach ($validated as $field => $value) {
            $draft->{$field} = $value;
        }

        /*
        |--------------------------------------------------------------------------
        | DATA SYSTEM
        |--------------------------------------------------------------------------
        */

        $draft->status = 'draft';

        $draft->current_part = 2;

        $draft->updated_by = $user;

        /*
        |--------------------------------------------------------------------------
        | SIMPAN
        |--------------------------------------------------------------------------
        */

        $draft->save();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN ID PART 1 KE SESSION
        |--------------------------------------------------------------------------
        */

        session([
            'draft_keluarga_id' => $draft->id,

            'part1_selesai' => true,

            'part2_selesai' => false,

            'part3_selesai' => false,

            'part4_selesai' => false,
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

            session()->forget([
                'draft_keluarga_id',
                'part1_selesai',
                'part2_selesai',
                'part3_selesai',
                'part4_selesai',
            ]);

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Draft kuisioner tidak ditemukan.'
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

        return view('admin.kuisioner.part2');
    }

    /*
    |--------------------------------------------------------------------------
    | PART 3
    |--------------------------------------------------------------------------
    */

    public function part3()
    {
        $draftId = session('draft_keluarga_id');

        if (!$draftId) {
            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 1 terlebih dahulu.'
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

        if ((int) $draft->current_part < 3) {
            return redirect()
                ->route('kuisioner.part2')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 2 terlebih dahulu.'
                );
        }

        return view('admin.kuisioner.part3');
    }

    /*
    |--------------------------------------------------------------------------
    | PART 4
    |--------------------------------------------------------------------------
    */

    public function part4()
    {
        $draftId = session('draft_keluarga_id');

        if (!$draftId) {
            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 1 terlebih dahulu.'
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

        if ((int) $draft->current_part < 4) {
            return redirect()
                ->route('kuisioner.part3')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 3 terlebih dahulu.'
                );
        }

        return view('admin.kuisioner.part4');
    }

    /*
    |--------------------------------------------------------------------------
    | PART 5
    |--------------------------------------------------------------------------
    */

    public function part5()
    {
        $draftId = session('draft_keluarga_id');

        if (!$draftId) {
            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 1 terlebih dahulu.'
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

        if ((int) $draft->current_part < 5) {
            return redirect()
                ->route('kuisioner.part4')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 4 terlebih dahulu.'
                );
        }

        return view('admin.kuisioner.part5');
    }

    /*
    |--------------------------------------------------------------------------
    | SELESAI KUISIONER
    |--------------------------------------------------------------------------
    */

    public function selesaiKuisioner(Request $request)
    {
        $draftId = session('draft_keluarga_id');

        if (!$draftId) {
            return redirect()
                ->route('kuisioner.index')
                ->with(
                    'warning',
                    'Tidak ada kuisioner aktif.'
                );
        }

        $draft = KeluargaPart1::findOrFail($draftId);

        $draft->update([
            'status' => 'selesai',

            'current_part' => 5,

            'updated_by' => auth()->user()->name ?? 'admin',
        ]);

        session()->forget([
            'draft_keluarga_id',

            'part1_selesai',

            'part2_selesai',

            'part3_selesai',

            'part4_selesai',
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
    | RESUME DRAFT
    |--------------------------------------------------------------------------
    */

    public function resumeDraft($id)
    {
        $draft = KeluargaPart1::where('id', $id)
            ->where('status', 'draft')
            ->firstOrFail();

        session([
            'draft_keluarga_id' => $draft->id,

            'part1_selesai' =>
                (int) $draft->current_part >= 2,

            'part2_selesai' =>
                (int) $draft->current_part >= 3,

            'part3_selesai' =>
                (int) $draft->current_part >= 4,

            'part4_selesai' =>
                (int) $draft->current_part >= 5,
        ]);

        switch ((int) $draft->current_part) {

            case 1:
                return redirect()
                    ->route('kuisioner.part1');

            case 2:
                return redirect()
                    ->route('kuisioner.part2');

            case 3:
                return redirect()
                    ->route('kuisioner.part3');

            case 4:
                return redirect()
                    ->route('kuisioner.part4');

            case 5:
                return redirect()
                    ->route('kuisioner.part5');

            default:
                return redirect()
                    ->route('kuisioner.part1');
        }
    }
}