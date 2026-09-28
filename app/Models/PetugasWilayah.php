<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PetugasWilayah extends Model
{
    protected $table = 'petugas_wilayah';

    protected $fillable = [
        'user_id',
        'rt_rw_id',
    ];

    /**
     * Penugasan ini dimiliki oleh satu user/petugas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Penugasan ini mengarah ke satu wilayah RT/RW.
     */
    public function rtRw(): BelongsTo
    {
        return $this->belongsTo(RtRw::class, 'rt_rw_id');
    }
}