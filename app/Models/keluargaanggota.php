<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluargaAnggota extends Model
{
    protected $table = 'keluarga_anggotas';

    protected $fillable = [
        'kode',
        'keluarga_kode',
        'nik',
        'nama_lengkap',
        'status_keluarga',
        'created_by',
        'updated_by',
    ];
}
