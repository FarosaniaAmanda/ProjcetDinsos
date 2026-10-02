<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpsiJawaban extends Model
{
    protected $table = 'opsi_jawaban';

    protected $fillable = [
        'kuesioner_id',
        'kode_opsi',
        'opsi_jawaban',
        'urutan',
    ];
}