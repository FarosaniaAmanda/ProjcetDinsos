<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KeluargaAnggota extends Model
{
    protected $table = 'keluarga_anggotas';

    protected $fillable = [
        'kode',
        'keluarga_kode',
        'nik',
        'nama_lengkap',
        'status_keluarga',
        'created_by',
        'updated_by',
    ];

    /**
     * Relasi ke keluarga
     */
    public function keluarga(): BelongsTo
    {
        return $this->belongsTo(
            Keluarga::class,
            'keluarga_kode',
            'kode'
        );
    }
}