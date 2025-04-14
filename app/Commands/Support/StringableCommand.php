<?php

namespace App\Commands\Support;

use App\Commands\BaseCommand;
use App\Concerns\Helpers\CallsMethods;
use Illuminate\Support\Collection;
use Illuminate\Support\Stringable;

class StringableCommand extends BaseCommand
{
    use CallsMethods;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'support:stringable
                                    {string : The string to manipulate.}
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
     * Call method on each item in the given collection.
     */
    protected function callOnCollection(Collection $collection, string $method, string $args = ''): Collection
    {
        if (! $collection instanceof Collection) {
            throw new \RuntimeException('Method expects a collection.');
        }

        $new = new Collection;
        $strings = $collection->all();

        foreach ($strings as $item) {
            $item = $this->callMethodFromCommandArgs(new Stringable($item), $method, $args);
            $new->push($item);
        }

        return $new;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $string = new Stringable($this->argument('string'));

        foreach ($this->arbitraryOptionsOrdered as $option) {
            $method = $option[0];
            $args = $option[1];
            if ($string instanceof Collection) {
                $string = $this->callOnCollection($string, $method);

                continue;
            }

            $string = $this->callMethodFromCommandArgs($string, $method, $args);

        }

        if ($string instanceof Collection) {
            $this->line(json_encode($string->all(), JSON_PRETTY_PRINT));

            return 0;
        }

        if (is_string($string)) {
            $this->line($string);

            return 0;
        }

        if (! ($string instanceof Stringable)) {
            $this->components->error('Last called method or assignement did not return a Stringable instance.');

            return 1;
        }

        $this->line($string->toString());

        return 0;
    }
}
