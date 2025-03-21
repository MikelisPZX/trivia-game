<?php

namespace App\Services;

class EncryptionService
{
    public function encrypt(string $value): string
    {
        return base64_encode(openssl_encrypt(
            $value,
            'AES-256-CBC',
            config('app.key'),
            0,
            str_repeat("\0", 16)
        ));
    }

    public function decrypt(string $value): string
    {
        return openssl_decrypt(
            base64_decode($value),
            'AES-256-CBC',
            config('app.key'),
            0,
            str_repeat("\0", 16)
        );
    }
} 