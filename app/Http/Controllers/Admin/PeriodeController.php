<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
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
            ->latest('created_at')
            ->get();

        return view(
            'admin.periode.index',
            compact('periodes')
        );
    }

    public function create()
    {
        return redirect()->route('periode.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'status_periode' => 'required|string|max:255',
        ], [
            'nama.required' =>
                'Nama kegiatan wajib diisi.',

            'tgl_awal.required' =>
                'Tanggal mulai wajib diisi.',

            'tgl_akhir.required' =>
                'Tanggal selesai wajib diisi.',

            'tgl_akhir.after_or_equal' =>
                'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'status_periode.required' =>
                'Status wajib dipilih.',
        ]);

        Periode::create([
            'kode' => 'PER-' . now()->format('YmdHis'),
            'nama' => $request->nama,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'status_periode' => $request->status_periode,
            'created_by' => auth()->user()->name ?? 'Admin',
        ]);

        return redirect()
            ->route('periode.index')
            ->with(
                'success',
                'Periode berhasil ditambahkan.'
            );
    }

    public function edit($id)
    {
        return redirect()->route('periode.index');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tgl_awal' => 'required|date',
            'tgl_akhir' => 'required|date|after_or_equal:tgl_awal',
            'status_periode' => 'required|string|max:255',
        ], [
            'nama.required' =>
                'Nama kegiatan wajib diisi.',

            'tgl_awal.required' =>
                'Tanggal mulai wajib diisi.',

            'tgl_akhir.required' =>
                'Tanggal selesai wajib diisi.',

            'tgl_akhir.after_or_equal' =>
                'Tanggal selesai harus setelah atau sama dengan tanggal mulai.',

            'status_periode.required' =>
                'Status wajib dipilih.',
        ]);

        $periode = Periode::findOrFail($id);

        $periode->update([
            'nama' => $request->nama,
            'tgl_awal' => $request->tgl_awal,
            'tgl_akhir' => $request->tgl_akhir,
            'status_periode' => $request->status_periode,
            'updated_by' => auth()->user()->name ?? 'Admin',
        ]);

        return redirect()
            ->route('periode.index')
            ->with(
                'success',
                'Periode berhasil diperbarui.'
            );
    }

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