<?php

namespace App\Models;

use App\Casts\EncryptedIdentifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'kode_pos',
        'alamat_lengkap',
        'tanggal_entri',
        'rt',
        'rw',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tanggal_entri' => 'date',
        'no_kk' => EncryptedIdentifier::class,
        'nik' => EncryptedIdentifier::class,
    ];

    public function anggota(): HasMany
    {
        return $this->hasMany(
            KeluargaAnggota::class,
            'keluarga_kode',
            'kode'
        );
    }
}
