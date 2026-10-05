<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'nomor_identitas',
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'kelurahan',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi user dengan wilayah tugas petugas.
     */
    public function petugasWilayah(): HasMany
    {
        return $this->hasMany(
            PetugasWilayah::class,
            'user_id'
        );
    }

    /**
     * Attribute casting.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }
}