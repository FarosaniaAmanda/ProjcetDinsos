<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KeluargaPart1;
use Illuminate\Http\Request;

class KuisionerController extends Controller
{
    /**
     * HALAMAN UTAMA KUISIONER
     * Hanya menampilkan jumlah Draft dan Selesai.
     */
    public function index()
    {
        $user = auth()->user()->name ?? 'admin';

        $draftCount = KeluargaPart1::where('status', 'draft')
            ->where('created_by', $user)
            ->count();

        $selesaiCount = KeluargaPart1::where('status', 'selesai')
            ->where('created_by', $user)
            ->count();

        return view('admin.kuisioner.index', [
            'page' => 'home',
            'draftCount' => $draftCount,
            'selesaiCount' => $selesaiCount,
            'drafts' => collect(),
            'selesais' => collect(),
        ]);
    }


    /**
     * HALAMAN DRAFT
     */
    public function draft()
    {
        $user = auth()->user()->name ?? 'admin';

        $drafts = KeluargaPart1::where('status', 'draft')
            ->where('created_by', $user)
            ->latest('id')
            ->get();

        return view('admin.kuisioner.index', [
            'page' => 'draft',
            'draftCount' => $drafts->count(),
            'selesaiCount' => KeluargaPart1::where('status', 'selesai')
                ->where('created_by', $user)
                ->count(),
            'drafts' => $drafts,
            'selesais' => collect(),
        ]);
    }


    /**
     * HALAMAN SELESAI
     */
    public function selesai()
    {
        $user = auth()->user()->name ?? 'admin';

        $selesais = KeluargaPart1::where('status', 'selesai')
            ->where('created_by', $user)
            ->latest('id')
            ->get();

        return view('admin.kuisioner.index', [
            'page' => 'selesai',
            'draftCount' => KeluargaPart1::where('status', 'draft')
                ->where('created_by', $user)
                ->count(),
            'selesaiCount' => $selesais->count(),
            'drafts' => collect(),
            'selesais' => $selesais,
        ]);
    }


    /**
     * MULAI KUISIONER
     * Tombol "Mulai Kuisioner" masuk ke Part 1.
     */
    public function part1()
    {
        return view('admin.kuisioner.index');
    }


    /**
     * SIMPAN PART 1 SEBAGAI DRAFT
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
        | CARI DRAFT BERDASARKAN NIK
        |--------------------------------------------------------------------------
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
        | SIMPAN DRAFT AKTIF
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
     * LANJUTKAN DRAFT
     */
    public function resumeDraft($id)
    {
        $user = auth()->user()->name ?? 'admin';

        $draft = KeluargaPart1::where('id', $id)
            ->where('status', 'draft')
            ->where('created_by', $user)
            ->firstOrFail();

        session([
            'draft_keluarga_id' => $draft->id,
            'part1_selesai' => true,
        ]);

        switch ((int) $draft->current_part) {

            case 1:
                return redirect()->route('kuisioner.part1');

            case 2:
                return redirect()->route('kuisioner.part2');

            case 3:
                return redirect()->route('kuisioner.part3');

            case 4:
                return redirect()->route('kuisioner.part4');

            case 5:
                return redirect()->route('kuisioner.part5');

            default:
                return redirect()->route('kuisioner.part1');
        }
    }
}