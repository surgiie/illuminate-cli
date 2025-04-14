<?php

namespace App\Commands\Support;

use App\Commands\BaseCommand;
use App\Concerns\Helpers\CallsMethods;
use Illuminate\Support\Str;

class StrCommand extends BaseCommand
{
    use CallsMethods;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'support:str
                                    {string? : The string to manipulate.}
                                    {--debug : Output debug information.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Manipulate and format a string.';

    /** Determine if the command should have arbitrary options. */
    protected function shouldHaveArbitraryOptions(): bool
    {
        return true;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $string = $this->argument('string');

        foreach ($this->arbitraryOptionsOrdered as $option) {
            $method = $option[0];
            $args = $option[1];
            $args = rtrim($string.','.$args, ',');
            $args = str_replace('@value', $string, $args);
            $string = $this->callMethodFromCommandArgs(Str::class, $method, $args);
        }
        if (! is_string($string)) {
            $string = json_encode($string, JSON_PRETTY_PRINT);
        }

        $this->line($string);

        return 0;
    }
}
