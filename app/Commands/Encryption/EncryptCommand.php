<?php

namespace App\Commands\Encryption;

use App\Concerns\Encryption\UsesEncrypter;
use App\Concerns\Validation\UsesValidator;
use App\Services\Encryption\EncryptionService;

class EncryptCommand extends BaseCommand
{
    use UsesEncrypter, UsesValidator;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'encryption:encrypt
                                    {string : The string to encrypt.}
                                    {--cipher= : The encryption cipher to use.}
                                    {--key= : The encryption key to use.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Encrypt a string.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $valid = $this->validateEncryptionCipherAndKey();
        if (! $valid) {
            return 1;
        }

        $key = $this->getEncryptionKey();
        $cipher = $this->getEncryptionCipher();

        $service = app(EncryptionService::class);

        $service->setKey($key);

        try {
            $encrypted = $service->encryptString($this->argument('string'), $cipher);
        } catch (\RuntimeException $e) {
            $this->components->error('Encryption Failed: '.$e->getMessage());

            return 1;
        }

        $this->line($encrypted);

        return 0;
    }
}
