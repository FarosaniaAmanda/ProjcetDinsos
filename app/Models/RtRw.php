<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RtRw extends Model
{
    protected $table = 'rt_rws';

    protected $fillable = [
        'kelurahan_id',
        'rt',
        'rw',
    ];

    /**
     * Wilayah ini dapat ditugaskan kepada banyak petugas.
     */
    public function petugasWilayah(): HasMany
    {
        return $this->hasMany(PetugasWilayah::class, 'rt_rw_id');
    }
}