<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periodes';

    protected $fillable = [
        'kode',
        'nama',
        'tgl_awal',
        'tgl_akhir',
        'status_periode',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_awal' => 'date',
        'tgl_akhir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}