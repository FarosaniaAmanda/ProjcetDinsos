<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pendataan extends Model
{
    protected $table = 'pendataan';

    protected $fillable = [
        'keluarga_id',
        'periode_id',
        'petugas_id',
        'status',
        'catatan',
        'mulai_diisi',
        'disubmit_at',
        'reviewed_at',
        'reviewed_by',
    ];

    protected $casts = [
        'mulai_diisi' => 'datetime',
        'disubmit_at' => 'datetime',
        'reviewed_at' => 'datetime',
    ];
}