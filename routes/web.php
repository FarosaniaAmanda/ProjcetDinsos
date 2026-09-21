<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PeriodeController;
use App\Http\Controllers\Admin\VerifikasiController;
use App\Http\Controllers\Admin\MonitoringController;


Route::get('/',[LoginController::class,'index']);

Route::get('/dashboard',[DashboardController::class,'index']);

Route::get('/periode',[PeriodeController::class,'index']);

Route::get('/periode/tambah',[PeriodeController::class,'create']);

Route::get('/periode/edit',[PeriodeController::class,'edit']);

Route::get('/verifikasi',[VerifikasiController::class,'index']);

Route::get('/monitoring',[MonitoringController::class,'index']);
