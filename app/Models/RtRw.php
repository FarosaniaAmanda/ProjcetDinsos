<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RtRw extends Model
{
    protected $table = 'rt_rws';

    protected $fillable = [
        'kelurahan_id',
        'rt',
        'rw',
    ];

    public function keluarga()
    {
        return $this->hasMany(
            Keluarga::class,
            'rt_rw_id',
            'id'
        );
    }
}