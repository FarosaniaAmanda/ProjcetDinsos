<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaKeluarga extends Model
{
    protected $table = 'keluarga_anggotas';

    protected $fillable = [
        'keluarga_id',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tanggal_lahir',
        'umur',
        'status_keluarga',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];
}