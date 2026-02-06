<?php

namespace App\Support;

class HmacSigner
{
    public static function sign(string $payload): string
    {
        $key = (string) config('app.key');
        $key = str_starts_with($key, 'base64:') ? base64_decode(substr($key, 7)) : $key;

        return hash_hmac('sha256', $payload, $key);
    }

    public static function normalize(array $parts): string
    {
        return implode('|', array_map(static fn ($v) => is_null($v) ? '' : (string) $v, $parts));
    }
}
