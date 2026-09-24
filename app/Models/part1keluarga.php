<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part1Keluarga extends Model
{
    protected $table = 'part1_keluarga';

    protected $fillable = [
        'keluarga_periode_kode',
        'nik',
        'no_kk',
        'jml_keluarga',
        'provinsi',
        'daerah',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'rt_rw',
        'alamat_lengkap',
        'jalan_rumah',
        'is_alamat_sesuai',
        'geotangging',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_alamat_sesuai' => 'boolean',
    ];
}