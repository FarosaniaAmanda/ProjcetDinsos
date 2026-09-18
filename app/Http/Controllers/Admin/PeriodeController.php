<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class PeriodeController extends Controller
{

    public function index()
    {
        return view('admin.periode.index');
    }

    public function create()
    {
        return view('admin.periode.create');
    }

    public function edit()
    {
        return view('admin.periode.edit');
    }


}