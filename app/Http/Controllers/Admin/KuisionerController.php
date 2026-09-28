<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KeluargaPart1;
use Illuminate\Http\Request;

class KuisionerController extends Controller
{
    /**
     * ============================================================
     * HALAMAN UTAMA KUISIONER
     * ============================================================
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


    /**
     * ============================================================
     * HALAMAN DRAFT
     * ============================================================
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


    /**
     * ============================================================
     * HALAMAN SELESAI
     * ============================================================
     */
    public function selesai()
    {
        $selesais = KeluargaPart1::where('status', 'selesai')
            ->latest('id')
            ->get();

        $draftCount = KeluargaPart1::where('status', 'draft')
            ->count();

        return view('admin.kuisioner.index', [
            'page' => 'selesai',
            'draftCount' => $draftCount,
            'selesaiCount' => $selesais->count(),
            'drafts' => collect(),
            'selesais' => $selesais,
        ]);
    }


    /**
     * ============================================================
     * PART 1
     * ============================================================
     */
    public function part1()
    {
        return view('admin.kuisioner.part1');
    }


    /**
     * ============================================================
     * SIMPAN PART 1
     * ============================================================
     */
    public function storePart1(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'nullable|string|max:255',
            'no_kk' => 'nullable|string|max:255',
            'jml_keluarga' => 'nullable|string|max:255',

            'provinsi' => 'nullable|string|max:255',
            'daerah' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'kelurahan' => 'nullable|string|max:255',

            'kode_pos' => 'nullable|string|max:16',
            'rt_rw' => 'nullable|string|max:16',

            'alamat_lengkap' => 'nullable|string|max:255',
            'jalan_rumah' => 'nullable|string|max:255',

            'is_alamat_sesuai' => 'nullable|boolean',

            'geotangging' => 'nullable|string|max:255',
        ]);

        $user = auth()->user()->name ?? 'admin';

        /*
        |--------------------------------------------------------------------------
        | CARI DRAFT LAMA
        |--------------------------------------------------------------------------
        |
        | Jika NIK yang sama sudah memiliki draft milik user ini,
        | maka data akan diperbarui, bukan membuat draft baru.
        |
        */

        $draft = null;

        if (!empty($validated['nik'])) {
            $draft = KeluargaPart1::where('nik', $validated['nik'])
                ->where('status', 'draft')
                ->where('created_by', $user)
                ->first();
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE DRAFT LAMA
        |--------------------------------------------------------------------------
        */

        if ($draft) {

            $validated['updated_by'] = $user;
            $validated['status'] = 'draft';
            $validated['current_part'] = 2;

            $draft->update($validated);
        }


        /*
        |--------------------------------------------------------------------------
        | BUAT DRAFT BARU
        |--------------------------------------------------------------------------
        */

        else {

            $validated['created_by'] = $user;
            $validated['status'] = 'draft';
            $validated['current_part'] = 2;

            $draft = KeluargaPart1::create($validated);
        }


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DRAFT AKTIF KE SESSION
        |--------------------------------------------------------------------------
        */

        session([
            'draft_keluarga_id' => $draft->id,
            'part1_selesai' => true,
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


    /**
     * ============================================================
     * PART 2
     * ============================================================
     */
    public function part2()
    {
        if (!session('part1_selesai')) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 1 terlebih dahulu.'
                );
        }

        return view('admin.kuisioner.part2');
    }


    /**
     * ============================================================
     * PART 3
     * ============================================================
     */
    public function part3()
    {
        if (!session('part1_selesai')) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 1 terlebih dahulu.'
                );
        }

        if (!session('part2_selesai')) {

            return redirect()
                ->route('kuisioner.part2')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 2 terlebih dahulu.'
                );
        }

        return view('admin.kuisioner.part3');
    }


    /**
     * ============================================================
     * PART 4
     * ============================================================
     */
    public function part4()
    {
        if (!session('part1_selesai')) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 1 terlebih dahulu.'
                );
        }

        if (!session('part2_selesai')) {

            return redirect()
                ->route('kuisioner.part2')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 2 terlebih dahulu.'
                );
        }

        if (!session('part3_selesai')) {

            return redirect()
                ->route('kuisioner.part3')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 3 terlebih dahulu.'
                );
        }

        return view('admin.kuisioner.part4');
    }


    /**
     * ============================================================
     * PART 5
     * ============================================================
     */
    public function part5()
    {
        if (!session('part1_selesai')) {

            return redirect()
                ->route('kuisioner.part1')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 1 terlebih dahulu.'
                );
        }

        if (!session('part2_selesai')) {

            return redirect()
                ->route('kuisioner.part2')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 2 terlebih dahulu.'
                );
        }

        if (!session('part3_selesai')) {

            return redirect()
                ->route('kuisioner.part3')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 3 terlebih dahulu.'
                );
        }

        if (!session('part4_selesai')) {

            return redirect()
                ->route('kuisioner.part4')
                ->with(
                    'warning',
                    'Silakan lengkapi Part 4 terlebih dahulu.'
                );
        }

        return view('admin.kuisioner.part5');
    }


    /**
     * ============================================================
     * SELESAIKAN KUISIONER
     * ============================================================
     */
    public function selesaiKuisioner(Request $request)
    {
        if (!session('draft_keluarga_id')) {

            return redirect()
                ->route('kuisioner.index')
                ->with(
                    'warning',
                    'Tidak ada kuisioner aktif.'
                );
        }


        $draft = KeluargaPart1::findOrFail(
            session('draft_keluarga_id')
        );


        /*
        |--------------------------------------------------------------------------
        | UBAH STATUS MENJADI SELESAI
        |--------------------------------------------------------------------------
        */

        $draft->update([
            'status' => 'selesai',
            'current_part' => 5,
            'updated_by' => auth()->user()->name ?? 'admin',
        ]);


        /*
        |--------------------------------------------------------------------------
        | HAPUS SESSION KUISIONER AKTIF
        |--------------------------------------------------------------------------
        */

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


    /**
     * ============================================================
     * LANJUTKAN DRAFT
     * ============================================================
     */
    public function resumeDraft($id)
    {
        $draft = KeluargaPart1::where('id', $id)
            ->where('status', 'draft')
            ->firstOrFail();


        /*
        |--------------------------------------------------------------------------
        | SIMPAN DRAFT AKTIF
        |--------------------------------------------------------------------------
        */

        session([
            'draft_keluarga_id' => $draft->id,
            'part1_selesai' => true,
        ]);


        /*
        |--------------------------------------------------------------------------
        | TENTUKAN PART TERAKHIR
        |--------------------------------------------------------------------------
        */

        switch ((int) $draft->current_part) {

            case 1:

                return redirect()
                    ->route('kuisioner.part1');


            case 2:

                session([
                    'part2_selesai' => false,
                ]);

                return redirect()
                    ->route('kuisioner.part2');


            case 3:

                session([
                    'part2_selesai' => true,
                    'part3_selesai' => false,
                ]);

                return redirect()
                    ->route('kuisioner.part3');


            case 4:

                session([
                    'part2_selesai' => true,
                    'part3_selesai' => true,
                    'part4_selesai' => false,
                ]);

                return redirect()
                    ->route('kuisioner.part4');


            case 5:

                session([
                    'part2_selesai' => true,
                    'part3_selesai' => true,
                    'part4_selesai' => true,
                ]);

                return redirect()
                    ->route('kuisioner.part5');


            default:

                return redirect()
                    ->route('kuisioner.part1');
        }
    }
}