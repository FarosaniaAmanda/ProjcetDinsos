<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Periode extends Model
{
    protected $table = 'periodes';

    protected $fillable = [
        'kode',
        'nama',
        'tgl_awal',
        'tgl_akhir',
        'status_periode',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'tgl_awal' => 'date',
        'tgl_akhir' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Alias agar kode project lama tetap bisa memakai:
    | nama_periode
    | tanggal_mulai
    | tanggal_selesai
    | status
    |--------------------------------------------------------------------------
    */

    public function getNamaPeriodeAttribute()
    {
        return $this->nama;
    }

    public function setNamaPeriodeAttribute($value)
    {
        $this->attributes['nama'] = $value;
    }

    public function getTanggalMulaiAttribute()
    {
        return $this->tgl_awal;
    }

    public function setTanggalMulaiAttribute($value)
    {
        $this->attributes['tgl_awal'] = $value;
    }

    public function getTanggalSelesaiAttribute()
    {
        return $this->tgl_akhir;
    }

    public function setTanggalSelesaiAttribute($value)
    {
        $this->attributes['tgl_akhir'] = $value;
    }

    public function getStatusAttribute()
    {
        return $this->status_periode;
    }

    public function setStatusAttribute($value)
    {
        $this->attributes['status_periode'] = $value;
    }
}