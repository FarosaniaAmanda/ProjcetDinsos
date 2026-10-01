<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluargaPart1 extends Model
{
    protected $table = 'part1_keluarga';

    protected $fillable = [
        'keluarga_periode_kode',

        'nik',
        'nama_kepala_keluarga',
        'no_kk',
        'jml_keluarga',

        'provinsi',
        'daerah',
        'kecamatan',
        'kelurahan',
        'kode_pos',

        'alamat_lengkap',

        'jalan_rumah',
        'nomor_rumah',

        'is_alamat_sesuai',
        'geotangging',

        'status',
        'current_part',

        'created_by',
        'updated_by',
    ];
}
