<?php

namespace App\Models;

use App\Casts\EncryptedIdentifier;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;

class KeluargaPart1 extends Model
{
    protected $table = 'part1_keluarga';

    protected $casts = [
        'nik' => EncryptedIdentifier::class,
        'no_kk' => EncryptedIdentifier::class,
    ];

    protected $fillable = [
        'keluarga_periode_kode',

        'nik',
        'nama_kepala_keluarga',
        'no_kk',
        'jml_keluarga',

        'provinsi',
        'daerah',
        'kecamatan',
        'kelurahan',
        'kode_pos',

        'alamat_lengkap',

        'jalan_rumah',
        'nomor_rumah',

        'is_alamat_sesuai',
        'alasan_tidak_sesuai',
        'geotangging',

        'status',
        'current_part',

        'created_by',
        'updated_by',
        'status',
        'current_part',
    ];

    /**
     * Find rows whose decrypted identifier exactly matches one of the values.
     *
     * @param  array<int, string>  $values
     * @return array<int, int>
     */
    public static function idsMatchingIdentifierValues(string $column, array $values): array
    {
        if (! in_array($column, ['nik', 'no_kk'], true)) {
            throw new InvalidArgumentException('Unsupported identifier column.');
        }

        if ($values === []) {
            return [];
        }

        return static::query()
            ->select(['id', $column])
            ->lazyById(500)
            ->filter(
                static fn (self $record): bool => in_array(
                    (string) $record->getAttribute($column),
                    $values,
                    true
                )
            )
            ->pluck('id')
            ->all();
    }

    /**
     * Find rows whose decrypted identifiers match a SQL-like search term.
     *
     * @return array<int, int>
     */
    public static function idsMatchingIdentifierSearch(string $search): array
    {
        $pattern = '/'.str_replace(
            ['%', '_'],
            ['[\s\S]*', '[\s\S]'],
            preg_quote('%'.$search.'%', '/')
        ).'/iu';

        return static::query()
            ->select(['id', 'nik', 'no_kk'])
            ->lazyById(500)
            ->filter(
                static fn (self $record): bool => preg_match($pattern, (string) $record->nik) === 1
                    || preg_match($pattern, (string) $record->no_kk) === 1
            )
            ->pluck('id')
            ->all();
    }
}
