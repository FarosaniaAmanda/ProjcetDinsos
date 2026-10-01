<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;

class DashboardController extends Controller
{
    public function index()
    {
        $periodeAktif = Periode::where('status_periode', 'Aktif')->count();

        return view('admin.dashboard', compact('periodeAktif'));
    }
}