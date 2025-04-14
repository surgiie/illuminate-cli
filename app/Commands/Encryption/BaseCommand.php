<?php

namespace App\Commands\Encryption;

use App\Commands\BaseCommand as CommandsBaseCommand;
use App\Concerns\Validation\UsesValidator;
use App\Services\Encryption\EncryptionService;

abstract class BaseCommand extends CommandsBaseCommand
{
    use UsesValidator;

    /**
     * Get the encryption key from option or env variable.
     */
    protected function getEncryptionKey()
    {
        return $this->option('key') ?: config('app.key');
    }

    /**
     * Get the encryption cipher from option or env variable.
     */
    protected function getEncryptionCipher()
    {
        return strtolower($this->option('cipher') ?: config('app.cipher'));
    }

    /**
     * Validate the encryption options.
     */
    protected function validateEncryptionCipherAndKey(): bool
    {
        $cipher = $this->getEncryptionCipher();

        $input = ['cipher' => $cipher];

        $rules = [
            'cipher' => ['required', 'string', 'in:'.implode(',', array_keys(EncryptionService::SUPPORTED_CIPHERS))],
        ];

        if ($this->hasOption('key')) {
            $key = $this->getEncryptionKey();
            $input['key'] = $key;
            $rules['key'] = ['required', 'string'];
        }

        $validator = $this->validator($input, $rules, messages: [
            'cipher.in' => 'The encryption cipher is not supported. Supported ciphers are: '.implode(', ', array_keys(EncryptionService::SUPPORTED_CIPHERS)),
            'key.required' => 'The encryption key is required and cannot be empty. Pass --key option or set ILLUMINATE_CLI_ENCRYPTION_KEY in your env variable.',
        ]);

        if ($validator->fails()) {
            $this->components->error($validator->errors()->first());

            return false;
        }

        return true;
    }
}
