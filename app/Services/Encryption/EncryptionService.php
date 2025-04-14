<?php

namespace App\Services\Encryption;

use Illuminate\Encryption\Encrypter as BaseEncrypter;

class EncryptionService
{
    protected string $key;

    /** The supported ciphers and their properties.*/
    public const SUPPORTED_CIPHERS = [
        'aes-128-cbc' => ['size' => 16, 'aead' => false],
        'aes-256-cbc' => ['size' => 32, 'aead' => false],
        'aes-128-gcm' => ['size' => 16, 'aead' => true],
        'aes-256-gcm' => ['size' => 32, 'aead' => true],
    ];

    /** Create a new EncryptionService instance. */
    public function __construct(?string $key = null)
    {
        if (! is_null($key)) {
            $this->setKey($key);
        }
    }

    /**
     * Generate a new encryption key in base64 format.
     */
    public function generateEncryptionKey(string $cipher): string
    {
        return 'base64:'.base64_encode(BaseEncrypter::generateKey($cipher));
    }

    /**
     * Get the base encrypter instance.
     */
    public function encrypter(string $cipher): BaseEncrypter
    {
        if (! isset($this->key) || empty($this->key)) {
            throw new \RuntimeException('Encryption key is not set on service. Set the key using setKey method.');
        }

        $encrypter = new BaseEncrypter($this->key, $cipher);

        $previousKeys = array_map(function ($key) {
            return $this->parseKey($key);
        }, config('app.previous_keys', []));

        $encrypter->previousKeys($previousKeys);

        return $encrypter;
    }

    /**
     * Encrypt the given value.
     */
    public function encryptString(string $value, string $cipher): string
    {
        return $this->encrypter($cipher)->encryptString($value);
    }

    /**
     * Encrypt the given value.
     */
    public function decryptString(string $value, string $cipher): string
    {
        return $this->encrypter($cipher)->decryptString($value);
    }

    /**
     * Parse the encryption key and decode it, if it is base64 encoded.
     */
    protected function parseKey(string $key): string
    {
        if (str_starts_with($key, 'base64:')) {
            return base64_decode(substr($key, 7));
        }

        return $key;
    }

    /**
     * Set the encryption key.
     */
    public function setKey(string $key): void
    {
        $this->key = $this->parseKey($key);
    }
}
