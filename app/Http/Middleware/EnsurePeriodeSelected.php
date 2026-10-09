<?php

namespace App\Http\Middleware;

use App\Models\Periode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePeriodeSelected
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (! session()->has('periode_id')) {
            $routeName = $request->route()?->getName();

            return redirect()->route('periode.pilih', [
                'tujuan' => $routeName,
            ]);
        }

        $periode = Periode::find(session('periode_id'));

        if (! $periode) {
            session()->forget([
                'periode_id',
                'periode_kode',
                'periode_nama',
            ]);

            $routeName = $request->route()?->getName();

            return redirect()->route('periode.pilih', [
                'tujuan' => $routeName,
            ])->with(
                'error',
                'Periode yang dipilih tidak ditemukan.'
            );
        }

        $status = strtolower(trim(
            $periode->status_periode ?? $periode->status ?? ''
        ));

        if ($status !== 'aktif') {
            session()->forget([
                'periode_id',
                'periode_kode',
                'periode_nama',
            ]);

            $routeName = $request->route()?->getName();

            if ($status === 'akan datang') {
                $pesan = 'Periode tersebut belum aktif. Silakan pilih periode yang aktif.';
            } elseif ($status === 'selesai') {
                $pesan = 'Periode tersebut sudah selesai. Silakan pilih periode yang aktif.';
            } else {
                $pesan = 'Periode tersebut tidak dapat digunakan. Silakan pilih periode yang aktif.';
            }

            return redirect()->route('periode.pilih', [
                'tujuan' => $routeName,
            ])->with('error', $pesan);
        }

        // Sinkronkan informasi periode dengan pilihan terbaru.
        session([
            'periode_id' => $periode->id,
            'periode_kode' => $periode->kode,
            'periode_nama' => $periode->nama,
        ]);

        // Sediakan data periode terpilih untuk view.
        view()->share('periodeTerpilih', $periode);

        return $next($request);
    }
}
