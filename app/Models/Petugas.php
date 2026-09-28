<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    use HasFactory;

    protected $table = 'petugas';

    protected $fillable = [
        'nama_lengkap',
        'nik',
        'jenis_kelamin',
        'tgl_lahir',
        'no_hp',
        'email',
        'alamat_rumah',
        'wilayah_tugas',
        'username',
        'terakhir_login',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'terakhir_login' => 'datetime',
    ];
}