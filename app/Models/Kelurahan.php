<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelurahan extends Model
{
    protected $table = 'kelurahans';

    protected $primaryKey = 'kelurahan_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'kelurahan_id',
        'kecamatan_id',
        'deskripsi',
    ];
}