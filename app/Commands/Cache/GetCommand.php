<?php

namespace App\Commands\Cache;

class GetCommand extends BaseCommand
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'cache:get
                                    {key : The key to get from the cache.}
                                    {--store=file : The cache store to use.}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Retrieve an item from the cache.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $key = $this->argument('key');

        $store = $this->option('store');

        $cache = $this->getCache($store);

        if (! $cache->has($key)) {
            $this->components->error("The item with key '{$key}' does not exist in the cache.");

            return 1;
        }

        $this->line($cache->get($key));

        return 0;
    }
}
