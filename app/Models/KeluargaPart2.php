<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeluargaPart2 extends Model
{
    protected $table = 'part2_kondisi_rumahs';

    public $timestamps = false;

    protected $fillable = [
        'keluarga_periode_kode',
        'jenis_bangungan',
        'is_keluarga_lain',
        'jml_keluarga_lain',
        'total_penghuni',
        'kepemilikan_bangunan',
        'bukti_kepemilikan',
        'harga_sewa_kontrak',
        'luas_lantai',
        'jenis_lantai',
        'kondisi_lantai',
        'jenis_dinding',
        'kondisi_dinding',
        'jenis_atap',
        'kondisi_atap',
        'fasilitas_bab',
        'jenis_kloset',
        'sumber_minum',
        'sumber_penerangan',
        'daya_listrik',
        'idpel_pln',
        'jml_meteran',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];
}