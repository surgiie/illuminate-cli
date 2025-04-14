<?php

namespace App\Commands\Support;

use App\Commands\BaseCommand;
use App\Concerns\Helpers\CallsMethods;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class CollectionCommand extends BaseCommand
{
    use CallsMethods;

    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'support:collection
                                    {collection=[] : The json string to convert to collection.}
                                    {--debug : Output debug information.}';

    /** Determine if the command should have arbitrary options. */
    protected function shouldHaveArbitraryOptions(): bool
    {
        return true;
    }

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Manipulate and format a collection.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {

        $collection = $this->argument('collection');

        if (! Str::isJson($collection)) {
            $this->error('The provided string is not a valid JSON string.');

            return 1;
        }

        $collection = new Collection(json_decode($collection, true));

        foreach ($this->arbitraryOptionsOrdered as $option) {
            $method = $option[0];
            $args = $option[1];
            $args = rtrim($args, ',');
            $collection = $this->callMethodFromCommandArgs($collection, $method, $args);

        }

        $this->line(json_encode($collection, JSON_PRETTY_PRINT));

        return 0;
    }
}
