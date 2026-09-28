<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keluarga extends Model
{
    protected $table = 'keluargas';

    protected $fillable = [
        'kode',
        'no_kk',
        'nik',
        'nama_lengkap',
        'status_keluarga',
        'kecamatan_id',
        'kelurahan_id',
        'rt_rw_id',
        'kode_pos',
        'alamat_lengkap',
        'created_by',
        'updated_by',
    ];

    /**
     * Anggota keluarga
     */
    public function anggota(): HasMany
    {
        return $this->hasMany(
            KeluargaAnggota::class,
            'keluarga_kode',
            'kode'
        );
    }

    /**
     * Relasi RT/RW
     */
    public function rtRw(): BelongsTo
    {
        return $this->belongsTo(
            RtRw::class,
            'rt_rw_id',
            'id'
        );
    }
}