<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluargaPart4 extends Model
{
    protected $table = 'part4_aset_keluargas';

    public $timestamps = false;

    protected $fillable = [
        'keluarga_periode_kode',
        'aset_keluarga',
        'is_punya_aset',
        'jml_aset',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];
}