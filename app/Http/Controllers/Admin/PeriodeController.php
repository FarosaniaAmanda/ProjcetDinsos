<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    /**
     * Menampilkan data periode
     */
    public function index(Request $request)
    {
        $query = Periode::query();

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(
                'nama_periode',
                'like',
                '%' . $search . '%'
            );
        }

        $periodes = $query
            ->latest()
            ->get();

        return view(
            'admin.periode.index',
            compact('periodes')
        );
    }


    /**
     * Halaman tambah
     *
     * Tambah periode menggunakan modal
     * di halaman index.
     */
    public function create()
    {
        return redirect()->route('periode.index');
    }


    /**
     * Menyimpan periode baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:255',

            'tanggal_mulai' => 'required|date',

            'tanggal_selesai' =>
                'required|date|after_or_equal:tanggal_mulai',

            'status' =>
                'required|string|max:50',

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
        | Simpan ke database
        |--------------------------------------------------------------------------
        */

        Periode::create([
            'nama_periode' =>
                $request->nama_periode,

            'tanggal_mulai' =>
                $request->tanggal_mulai,

            'tanggal_selesai' =>
                $request->tanggal_selesai,

            'status' =>
                $request->status,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Kembali ke halaman Manajemen Periode
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('periode.index')
            ->with(
                'success',
                'Periode berhasil ditambahkan.'
            );
    }


    /**
     * Edit menggunakan modal di index.
     *
     * Tidak membutuhkan edit.blade.php.
     */
    public function edit($id)
    {
        return redirect()->route('periode.index');
    }


    /**
     * Memperbarui periode
     */
    public function update(
        Request $request,
        $id
    ) {
        $request->validate([

            'nama_periode' =>
                'required|string|max:255',

            'tanggal_mulai' =>
                'required|date',

            'tanggal_selesai' =>
                'required|date|after_or_equal:tanggal_mulai',

            'status' =>
                'required|string|max:50',

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


        $periode->update([

            'nama_periode' =>
                $request->nama_periode,

            'tanggal_mulai' =>
                $request->tanggal_mulai,

            'tanggal_selesai' =>
                $request->tanggal_selesai,

            'status' =>
                $request->status,
        ]);


        return redirect()
            ->route('periode.index')
            ->with(
                'success',
                'Periode berhasil diperbarui.'
            );
    }


    /**
     * Menghapus periode
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