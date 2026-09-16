<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () {
    return view('welcome');
});

// Monitoring
Route::get('/admin/monitoring', [MonitoringController::class, 'index'])
    ->name('admin.monitoring');

Route::get('/admin/monitoring/{id}', [MonitoringController::class, 'detail'])
    ->name('admin.monitoring.detail');

// Laporan
Route::get('/admin/laporan', [LaporanController::class, 'index'])
    ->name('admin.laporan');