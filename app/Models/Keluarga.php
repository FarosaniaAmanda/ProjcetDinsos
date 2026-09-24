<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keluarga extends Model
{
    protected $table = 'keluargas';

    protected $fillable = [
        'kode',
        'no_kk',
        'nik',
        'nama_lengkap',
        'status_keluarga',
        'created_by',
        'updated_by',
    ];

    /**
     * Relasi ke anggota keluarga
     *
     * keluarga_anggotas.keluarga_kode
     * berelasi dengan
     * keluargas.kode
     */
    public function anggota()
    {
        return $this->hasMany(
            KeluargaAnggota::class,
            'keluarga_kode',
            'kode'
        );
    }

    /**
     * Relasi ke periode keluarga
     */
    public function periode()
    {
        return $this->hasMany(
            KeluargaPeriode::class,
            'keluarga_kode',
            'kode'
        );
    }
}