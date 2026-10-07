<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | PERIODE AKTIF
        |--------------------------------------------------------------------------
        */

        $periodeAktif = Periode::where(
            'status_periode',
            'Aktif'
        )->count();


        /*
        |--------------------------------------------------------------------------
        | REKAP DATA TIAP PERIODE
        |--------------------------------------------------------------------------
        |
        | Mengambil maksimal 10 periode terbaru.
        |
        */

        $rekapPeriode = Periode::orderByDesc('tgl_awal')
            ->limit(10)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | DASHBOARD
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.dashboard',
            compact(
                'periodeAktif',
                'rekapPeriode'
            )
        );
    }
}