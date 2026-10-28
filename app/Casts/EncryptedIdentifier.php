<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class EncryptedIdentifier implements CastsAttributes
{
    private const PREFIX = 'enc:v1:';

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (! is_string($value) || ! str_starts_with($value, self::PREFIX)) {
            return $value;
        }

        return self::decrypt($value);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($value === null) {
            return null;
        }

        $value = (string) $value;

        return self::encrypt($value);
    }

    public static function encrypt(string $value): string
    {
        if (str_starts_with($value, self::PREFIX)) {
            self::decrypt($value);

            return $value;
        }

        return self::PREFIX.Crypt::encryptString($value);
    }

    private static function decrypt(string $value): string
    {
        return Crypt::decryptString(substr($value, strlen(self::PREFIX)));
    }
}
