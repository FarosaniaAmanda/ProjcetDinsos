<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluargaPeriode extends Model
{
    protected $table = 'keluarga_periodes';

    protected $fillable = [
        'kode',
        'keluarga_kode',
        'periode_kode',
        'status_kuisoner',
        'created_by',
        'updated_by',
    ];

    /**
     * Relasi ke keluarga
     */
    public function keluarga()
    {
        return $this->belongsTo(
            Keluarga::class,
            'keluarga_kode',
            'kode'
        );
    }
}