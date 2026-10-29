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
        if (! is_string($value)) {
            return $value;
        }

        if (str_starts_with($value, self::PREFIX)) {
            return self::decrypt($value);
        }

        if (! self::isLegacyEncrypted($value)) {
            return $value;
        }

        return Crypt::decryptString($value);
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

    private static function isLegacyEncrypted(string $value): bool
    {
        $decoded = base64_decode($value, true);

        if ($decoded === false) {
            return false;
        }

        $payload = json_decode($decoded, true);

        return is_array($payload)
            && isset($payload['iv'], $payload['value'], $payload['mac'])
            && is_string($payload['iv'])
            && is_string($payload['value'])
            && is_string($payload['mac']);
    }
}
