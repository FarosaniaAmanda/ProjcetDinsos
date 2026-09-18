<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Petugas extends Model
{
    protected $table = 'petugas';

    protected $fillable = [
        'user_id',
        'nik',
        'nomor_hp',
        'email',
        'alamat',
        'wilayah_id',
        'status',
        'last_login_at',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];
}