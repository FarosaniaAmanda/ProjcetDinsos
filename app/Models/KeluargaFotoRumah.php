<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluargaFotoRumah extends Model
{
    protected $table = 'part5_foto_rumahs';

    public $timestamps = false;

    protected $fillable = [
        'keluarga_periode_kode',
        'jenis_foto',
        'nama_file',
        'path_file',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];
}