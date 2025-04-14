<?php

namespace App\Commands\Encryption;

use App\Commands\BaseCommand;
use App\Concerns\Validation\UsesValidator;
use App\Services\Encryption\EncryptionService;

class KeyCommand extends BaseCommand
{
    use UsesValidator;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'encryption:key {--cipher= : The encryption cipher to generate key for.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Generate a new encryption key in base64 format.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cipher = strtolower($this->option('cipher') ?: config('app.cipher'));

        $validator = $this->validator(['cipher' => $cipher], [
            'cipher' => ['required', 'string', 'in:'.implode(',', array_keys(EncryptionService::SUPPORTED_CIPHERS))],
        ], messages: [
            'cipher.in' => 'The encryption cipher is not supported. Supported ciphers are: '.implode(', ', array_keys(EncryptionService::SUPPORTED_CIPHERS)),
        ]);

        if ($validator->fails()) {
            $this->components->error($validator->errors()->first());

            return 1;
        }

        $service = app(EncryptionService::class);

        $this->line($service->generateEncryptionKey($cipher));

        return 0;
    }
}
