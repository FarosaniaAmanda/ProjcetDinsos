<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    protected $table = 'keluarga';

    protected $fillable = [
        'periode_id',
        'petugas_id',
        'wilayah_id',
        'nomor_kk',
        'nama_kepala_keluarga',
        'alamat',
        'status_pendataan',
        'tanggal_pendaftaran',
    ];

    protected $casts = [
        'tanggal_pendaftaran' => 'datetime',
    ];
}