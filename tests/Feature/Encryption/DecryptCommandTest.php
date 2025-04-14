<?php

use App\Services\Encryption\EncryptionService;

it('validates encryption cipher option', function () {
    $this->artisan('encryption:decrypt', ['string' => 'foo', '--cipher' => 'invalid'])
        ->assertExitCode(1)
        ->expectsOutputToContain('The encryption cipher is not supported. Supported ciphers are: '.implode(', ', array_keys(EncryptionService::SUPPORTED_CIPHERS)));
});

it('validates encryption key option', function () {
    $this->artisan('encryption:decrypt', ['string' => 'foo'])
        ->assertExitCode(1)
        ->expectsOutputToContain('The encryption key is required and cannot be empty. Pass --key option or set ILLUMINATE_CLI_ENCRYPTION_KEY in your env variable.');
});

it('decrypts a string with a valid cipher', function () {
    $service = new EncryptionService;
    $key = $service->generateEncryptionKey($cipher = 'aes-128-cbc');
    $service->setKey($key);
    $encrypted = $service->encryptString('foo', $cipher);

    $this->artisan('encryption:decrypt', ['string' => $encrypted, '--cipher' => $cipher, '--key' => $key])
        ->assertExitCode(0)
        ->expectsOutput('foo');
});
