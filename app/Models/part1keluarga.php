<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part1Keluarga extends Model
{
    protected $table = 'part1_keluarga';

    public $timestamps = false;

    protected $fillable = [
        'no_kk',
        'provinsi',
        'daerah',
        'kecamatan',
        'kelurahan',
        'kode_pos',
        'rt_rw',
        'alamat_lengkap',
        'jlan_rumah',
        'is_alamat_sesuai',
    ];

    protected $casts = [
        'is_alamat_sesuai' => 'boolean',
    ];
}