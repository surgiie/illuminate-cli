<?php

use App\Services\Encryption\EncryptionService;

it('generates random encryption key', function () {
    $key = base64_encode(random_bytes(32));
    $this->partialMock(EncryptionService::class, function ($mock) use ($key) {
        $mock->shouldReceive('generateEncryptionKey')
            ->andReturn('base64:'.$key);
    });

    $cmd = $this->artisan('encryption:key')
        ->assertExitCode(0);
    $cmd->expectsOutputToContain('base64:'.$key);
});

it('validates cipher option', function () {
    $this->artisan('encryption:key', ['--cipher' => 'invalid'])
        ->assertExitCode(1)
        ->expectsOutputToContain('The encryption cipher is not supported. Supported ciphers are: '.implode(', ', array_keys(EncryptionService::SUPPORTED_CIPHERS)));
});
