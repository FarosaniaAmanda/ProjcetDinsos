<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban extends Model
{
    protected $table = 'jawaban';

    protected $fillable = [
        'pendataan_id',
        'kuesioner_id',
        'anggota_keluarga_id',
        'opsi_jawaban_id',
        'jawaban_text',
    ];
}