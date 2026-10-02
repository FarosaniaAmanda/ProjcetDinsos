<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluargaPart3 extends Model
{
    protected $table = 'part3_keuangan_keluargas';

    public $timestamps = false;

    protected $fillable = [
        'keluarga_periode_kode',
        'pengeluaran_listrik_bulanan',
        'pengeluaran_pulsa_bulanan',
        'pengeluaran_internet_bulanan',
        'pengeluaran_makan_mingguan',
        'pengeluaran_nonmakan_bulanan',
        'pengeluaran_nonmakan_tahunan',
        'total_pendapatan_kerja',
        'total_pendapatan_usaha',
        'total_pendapatan_lainnya',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
}