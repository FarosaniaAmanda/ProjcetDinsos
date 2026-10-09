<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Periode;

class EnsurePeriodeSelected
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH PERIODE SUDAH DIPILIH
        |--------------------------------------------------------------------------
        */

        if (!session()->has('periode_id')) {

            $routeName = $request->route()?->getName();

            return redirect()->route('periode.pilih', [
                'tujuan' => $routeName,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL PERIODE DARI SESSION
        |--------------------------------------------------------------------------
        */

        $periode = Periode::find(session('periode_id'));

        /*
        |--------------------------------------------------------------------------
        | JIKA PERIODE SUDAH TIDAK ADA DI DATABASE
        |--------------------------------------------------------------------------
        */

        if (!$periode) {

            session()->forget('periode_id');

            $routeName = $request->route()?->getName();

            return redirect()->route('periode.pilih', [
                'tujuan' => $routeName,
            ])->with(
                'error',
                'Periode yang dipilih tidak ditemukan.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK STATUS PERIODE
        |--------------------------------------------------------------------------
        |
        | Hanya periode dengan status "aktif" yang boleh digunakan.
        |
        */

        $status = strtolower(trim($periode->status ?? ''));

        if ($status !== 'aktif') {

            session()->forget('periode_id');

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
            ])->with(
                'error',
                $pesan
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PERIODE AKTIF
        |--------------------------------------------------------------------------
        |
        | Jika status periode adalah aktif, request dilanjutkan.
        |
        */

        return $next($request);
    }
}