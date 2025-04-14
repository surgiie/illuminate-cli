<?php

namespace App\Commands\View;

use App\Commands\BaseCommand;
use App\Concerns\Validation\UsesValidator;

class ClearCompiledCommand extends BaseCommand
{
    use UsesValidator;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'view:clear-compiled
                            {--minutes= : When passed, clear only files older than the given minutes. }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Clear compiled view files.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cacheExpiration = $this->option('minutes');

        $validator = $this->validator([
            'minutes' => $cacheExpiration,
        ], [
            'minutes' => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            $this->fail($validator->errors()->first());
        }

        if (! $cacheExpiration) {
            $cacheExpiration = -1;
        }

        config([
            'laravel-directory-cleanup.directories' => [
                illuminate_cli_workspace_path('compiled-views', 'ILLUMINATE_CLI_VIEW_COMPILED_PATH') => [
                    'deleteAllOlderThanMinutes' => intval($cacheExpiration),
                ],
            ],
        ]);

        $this->call('clean:directories');

        return 0;
    }
}
