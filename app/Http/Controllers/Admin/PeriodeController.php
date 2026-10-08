<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class PeriodeController extends Controller
{
    /**
     * Menampilkan halaman manajemen periode.
     */
    public function index(Request $request)
    {
        $query = Periode::query();

        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(
                'nama',
                'like',
                '%' . $search . '%'
            );
        }

        $periodes = $query
            ->latest('id')
            ->get();

        return view(
            'admin.periode.index',
            compact('periodes')
        );
    }


    /**
     * Menyimpan periode baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|string|max:255',
        ], [
            'nama_periode.required' =>
                'Nama kegiatan wajib diisi.',

            'tanggal_mulai.required' =>
                'Tanggal mulai wajib diisi.',

            'tanggal_selesai.required' =>
                'Tanggal selesai wajib diisi.',

            'tanggal_selesai.after_or_equal' =>
                'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'status.required' =>
                'Status wajib dipilih.',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Generate kode periode
        |--------------------------------------------------------------------------
        */

        $kode = 'PER-' . str_pad(
            (Periode::max('id') ?? 0) + 1,
            3,
            '0',
            STR_PAD_LEFT
        );


        /*
        |--------------------------------------------------------------------------
        | Simpan periode
        |--------------------------------------------------------------------------
        */

        $periode = new Periode();

        $periode->kode =
            $kode;

        $periode->nama =
            $request->nama_periode;

        $periode->tgl_awal =
            $request->tanggal_mulai;

        $periode->tgl_akhir =
            $request->tanggal_selesai;

        $periode->status_periode =
            $request->status;

        $periode->created_by =
            auth()->user()?->name ?? 'admin';

        $periode->updated_by =
            null;

        $periode->save();


        return redirect()
            ->route('periode.index')
            ->with(
                'success',
                'Periode berhasil ditambahkan.'
            );
    }


    /**
     * Memperbarui periode.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'status' => 'required|string|max:255',
        ], [
            'nama_periode.required' =>
                'Nama kegiatan wajib diisi.',

            'tanggal_mulai.required' =>
                'Tanggal mulai wajib diisi.',

            'tanggal_selesai.required' =>
                'Tanggal selesai wajib diisi.',

            'tanggal_selesai.after_or_equal' =>
                'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'status.required' =>
                'Status wajib dipilih.',
        ]);


        $periode = Periode::findOrFail($id);


        $periode->nama =
            $request->nama_periode;

        $periode->tgl_awal =
            $request->tanggal_mulai;

        $periode->tgl_akhir =
            $request->tanggal_selesai;

        $periode->status_periode =
            $request->status;

        $periode->updated_by =
            auth()->user()?->name ?? 'admin';

        $periode->save();


        return redirect()
            ->route('periode.index')
            ->with(
                'success',
                'Periode berhasil diperbarui.'
            );
    }


    /**
     * Menampilkan halaman pemilihan periode.
     */
    public function pilih(Request $request)
    {
        $periodes = Periode::query()
            ->orderByDesc('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Tujuan menu yang diperbolehkan
        |--------------------------------------------------------------------------
        */

        $tujuanYangDiizinkan = [
            'kuisioner.index',
            'verifikasi.index',
            'monitoring.index',
            'laporan.index',
        ];

        /*
        |--------------------------------------------------------------------------
        | Ambil tujuan dari menu yang dipilih
        |--------------------------------------------------------------------------
        */

        $tujuan = $request->get(
            'tujuan',
            'kuisioner.index'
        );

        /*
        |--------------------------------------------------------------------------
        | Pastikan tujuan valid
        |--------------------------------------------------------------------------
        */

        if (!in_array($tujuan, $tujuanYangDiizinkan, true)) {
            $tujuan = 'kuisioner.index';
        }

        return view(
            'admin.periode.pilih',
            compact(
                'periodes',
                'tujuan'
            )
        );
    }


    /**
     * Menyimpan periode yang dipilih
     * ke dalam session.
     */
    public function setPeriode(Request $request)
    {
        $request->validate([
            'periode_id' => [
                'required',
                'integer',
                'exists:periodes,id',
            ],

            'tujuan' => [
                'required',
                'string',
                'in:kuisioner.index,verifikasi.index,monitoring.index,laporan.index',
            ],
        ]);


        $periode = Periode::findOrFail(
            $request->periode_id
        );


        /*
        |--------------------------------------------------------------------------
        | Simpan periode aktif
        |--------------------------------------------------------------------------
        */

        session([
            'periode_id'   => $periode->id,
            'periode_kode' => $periode->kode,
            'periode_nama' => $periode->nama,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Redirect sesuai menu yang dipilih
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            $request->tujuan
        );
    }


    /**
     * Menghapus periode.
     */
    public function destroy($id)
    {
        $periode = Periode::findOrFail($id);

        $periode->delete();

        return redirect()
            ->route('periode.index')
            ->with(
                'success',
                'Periode berhasil dihapus.'
            );
    }
}
