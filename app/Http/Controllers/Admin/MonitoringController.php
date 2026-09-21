<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class MonitoringController extends Controller
{
    public function index()
    {
        return view('admin.monitoring.index');
    }

    public function detail($id)
    {
        return view('admin.monitoring.detail', compact('id'));
    }
}