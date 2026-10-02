<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluargaPart5 extends Model
{
    protected $table = 'part5_anggota_keluargas';

    public $timestamps = false;

    protected $fillable = [
        'keluarga_periode_kode',
        'keluarga_anggota_kode',
        'keberadaan',
        'no_hp',
        'jenis_kelamin',
        'tanggal_lahir',
        'status_perkawinan',
        'status_sekolah',
        'ijazah_tertinggi',
        'pekerjaan_utama',
        'status_pekerjaan',
        'kepemilikan_rekening',
        'is_disabilitas_fisik',
        'is_disabilitas_mental',
        'is_disabilitas_intelektual',
        'is_disabilitas_netra',
        'is_disabilitas_rungu',
        'is_disabilitas_wicara',
        'keluhan_kesehatan',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];
}